<?php
    session_start();
    $connect = pg_connect();
    if($connect)
    {
        $result = pg_query($connect,"SELECT user_id FROM users WHERE Login = '".$_POST['Login']."'");
		$userID = pg_fetch_row($result);
        if(!empty($userID[0]))
        {
			$result = pg_query($connect,"SELECT password FROM users WHERE user_id = ".$userID[0]."");
			$checkPassword = pg_fetch_row($result);
            if(password_verify($_POST['Password'],$checkPassword[0]))
            {
				$result = pg_query($connect,"SELECT rank_id FROM users WHERE user_id = ".$userID[0]."");
				$rank = pg_fetch_row($result);
				$_SESSION['login'] = $_POST['Login'];
                $_SESSION['userID'] = $userID[0];
				$_SESSION['rank'] = $rank[0];
                pg_close();
                header('Location: ../strony/mainmenu.php');
				exit();
            }
			else
			{
				$_SESSION['error'] = 'LF';
				pg_close();
				header('Location: ../index.php');
				exit();
			}
        }
        else
        {
            $_SESSION['error'] = 'LF';
            pg_close();
            header('Location: ../index.php');
			exit();
        }
    }
    else
    {
        $_SESSION['error'] = 'CF';
        header('Location: ../index.php');
		exit();
    }
?>