<?php
    $mysqli = new mysqli("localhost", "root", "", "homeLibrary");
	session_start();
	if(!$mysqli->connect_error)
    {
        $query = $mysqli->prepare("UPDATE copies SET who_reading=? where copy_id=?");
        $query->bind_param("si",$_POST['Reader'],$_POST['CopyID']);
        $query->execute();
        $mysqli->close();
        header('Location: ../strony/mybookinfo.php?id='.$_POST['CopyID'].'');
        exit;
    }
    else
    {
        echo '<p>Błąd połączenia z bazą danych</p>';
        echo '<a href="javascript:history.go(-1);">Cofnij</a>';
    }
?>