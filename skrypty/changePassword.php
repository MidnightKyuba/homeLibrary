<?php
    $mysqli = new mysqli("localhost", "root", "", "homeLibrary");
	session_start();
	if(!$mysqli->connect_error)
    {
        if($_POST['NewPassword'] == $_POST['ControlNewPassword'])
        {
            $result = $mysqli->prepare("SELECT password FROM users WHERE user_id = ?");
            $result->bind_param("i",$_SESSION['userID']);
            $result->execute();
			$checkPassword = $result->fetch_row();
            if(password_verify($_POST['OldPassword'],$checkPassword[0]))
            {
                $savePassword = password_hash($_POST['NewPassword'],PASSWORD_BCRYPT);
                $result = $mysqli->prepare("Update users set password=? where user_id=?");
                $result->bind_param("si",$savePassword, $_SESSION['userID']);
                $result->execute();
                $mysqli->close();
                header('Location: ../strony/profile.php?m=changePassword');
				exit();
            }
            else
            {
                $mysqli->close();
                echo '<h1>Błąd</h1>';
                echo '<p>Nieprawidłowe starę hasło</p>';
                echo '<a href="javascript:history.go(-1);">Cofnij</a>';
            }
        }
        else
        {
            $mysqli->close();
            echo '<h1>Błąd</h1>';
            echo '<p>Oba pole nowego hasła muszą się zgadzać</p>';
            echo '<a href="javascript:history.go(-1);">Cofnij</a>';
        }
    }
    else
    {
        echo '<p>Błąd połączenia z bazą danych</p>';
        echo '<a href="javascript:history.go(-1);">Cofnij</a>';
    }
?>