<?php
	$connect = pg_connect();
	session_start();
	if($connect)
    {
        pg_query($connect,"UPDATE users SET email='".$_POST['Email']."' WHERE user_id=".$_SESSION['userID']."");
        pg_close();
		header('Location: ../strony/profile.php');
		exit();
    }
    else
    {
        $_SESSION['error'] = 'CF';
        header('Location: ../strony/profile.php');
		exit();
    }
?>