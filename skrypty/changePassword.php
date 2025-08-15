<?php
    $connect = pg_connect();
	session_start();
	if($connect)
    {
        if($_POST['NewPassword'] == $_POST['ControlNewPassword'])
        {
            $result = pg_query($connect,"SELECT password FROM users WHERE user_id = ".$_SESSION['userID']."");
			$checkPassword = pg_fetch_row($result);
            if(password_verify($_POST['OldPassword'],$checkPassword[0]))
            {
                $savePassword = password_hash($_POST['NewPassword'],PASSWORD_BCRYPT);
                pg_query($connect, "Update users set password='".$savePassword."' where user_id=".$_SESSION['userID']."");
                pg_close();
                header('Location: ../strony/profile.php?m=changePassword');
				exit();
            }
            else
            {
                pg_close();
                echo '<h1>Błąd</h1>';
                echo '<p>Nieprawidłowe starę hasło</p>';
                echo '<a href="javascript:history.go(-1);">Cofnij</a>';
            }
        }
        else
        {
            pg_close();
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