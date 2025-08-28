<?php
    $mysqli = new mysqli("localhost", "root", "", "homeLibrary");
	session_start();
    if(!$mysqli->connect_error)
    {   
        $result = $mysqli->prepare("SELECT user_id FROM users WHERE login = ?");
        $result->bind_param("s",$_POST['Login']);
        $result->execute();
        $result = $result->get_result();
		$userID = $result->fetch_row();
        if(empty($userID[0]))
        {
            if($_POST['Password'] == $_POST['ControlPassword'])
            {
				$password = password_hash($_POST['Password'],PASSWORD_BCRYPT);
                $query = $mysqli->prepare("INSERT INTO users(login, password, rank_id, email) VALUES (?,?, 3,?)");
                $query->bind_param("sss",$_POST['Login'],$password,$_POST['Email']);
                $query->execute();
                $result = $mysqli->prepare("SELECT user_id, login FROM users WHERE Login = ?");
                $result->bind_param("s",$_POST['Login']);
                $result->execute();
                $result = $result->get_result();
				$user = $result->fetch_row();
				$result = $mysqli->prepare("SELECT rank_id FROM users WHERE user_id = ?");
                $result->bind_param("i",$user[0]);
                $result->execute();
                $result = $result->get_result();
				$rank = $result->fetch_row();
				$mysqli->close();
				$_SESSION['userID'] = $user[0];
				$_SESSION['login'] = $user[1];
				$_SESSION['rank'] = $rank[0];
				header('Location: ../strony/mainmenu.php');
				exit();
            }
            else
            {
                $_SESSION['error'] = 'RF';
                $mysqli->close();
				header('Location: ../strony/reg.php');
				exit();
            }
        }
        else
        {
            $_SESSION['error'] = 'RF';
            $mysqli->close();
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