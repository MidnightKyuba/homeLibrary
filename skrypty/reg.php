<?php
    $connect = pg_connect();
	session_start();
    if($connect)
    {   
        $result = pg_query($connect,"SELECT user_id FROM users WHERE login ='".$_POST['Login']."'");
		$userID = pg_fetch_row($result);
        if(empty($userID[0]))
        {
            if($_POST['Password'] == $_POST['ControlPassword'])
            {
				$password = password_hash($_POST['Password'],PASSWORD_BCRYPT);
                pg_query($connect,"INSERT INTO users(login, password, rank_id, email) VALUES ('".$_POST['Login']."','".$password."', 3,'".$_POST['Email']."')");
                $result = pg_query($connect,"SELECT user_id FROM users WHERE Login = '".$_POST['Login']."'");
				$userID = pg_fetch_row($result);
				$result = pg_query($connect,"SELECT rank_id FROM users WHERE user_id = ".$userID[0]."");
				$rank = pg_fetch_row($result);
				pg_close();
				$_SESSION['userID'] = $userID[0];
				$_SESSION['login'] = $_POST['Login'];
				$_SESSION['rank'] = $rank[0];
				header('Location: ../strony/mainmenu.php');
				exit();
            }
            else
            {
                $_SESSION['error'] = 'RF';
                pg_close();
				header('Location: ../strony/reg.php');
				exit();
            }
        }
        else
        {
            $_SESSION['error'] = 'RF';
            pg_close();
			header('Location: ../strony/reg.php');
			exit();
        }
    }
    else
    {
        $_SESSION['error'] = 'CF';
        header('Location: ../strony/reg.php');
		exit();
    }
?>