<?php
	$mysqli = new mysqli("localhost", "root", "", "homeLibrary");
	session_start();
	if(!$mysqli->connect_error)
    {
        $query = $mysqli->prepare("UPDATE users SET email=? WHERE user_id=?");
        $query->bind_param("si",$_POST['Email'],$_SESSION['userID']);
        $query->execute();
        $mysqli->close();
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