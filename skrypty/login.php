<?php
    session_start();
    $mysqli = new mysqli("localhost", "root", "", "homeLibrary");
    if(!$mysqli->connect_error)
    {
        $result = $mysqli->prepare("SELECT user_id FROM users WHERE Login = ?");
        $result->bind_param("s",$_POST['Login']);
        $result->execute();
        $result = $result->get_result();
		$userID = $result->fetch_row();
        if(!empty($userID[0]))
        {
			$result = $mysqli->prepare("SELECT password FROM users WHERE user_id = ?");
            $result->bind_param("s",$userID[0]);
            $result->execute();
            $result = $result->get_result();
			$checkPassword = $result->fetch_row();
            if(password_verify($_POST['Password'],$checkPassword[0]))
            {
				$result = $mysqli->prepare("SELECT rank_id FROM users WHERE user_id = ?");
                $result->bind_param("i", $userID[0]);
                $result->execute();
                $result = $result->get_result();
				$rank = $result->fetch_row();
				$_SESSION['login'] = $_POST['Login'];
                $_SESSION['userID'] = $userID[0];
				$_SESSION['rank'] = $rank[0];
                $mysqli->close();
                header('Location: ../strony/mainmenu.php');
				exit();
            }
			else
			{
				$_SESSION['error'] = 'LF';
				$mysqli->close();
				header('Location: ../index.php');
				exit();
			}
        }
        else
        {
            $_SESSION['error'] = 'LF';
            $mysqli->close();
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