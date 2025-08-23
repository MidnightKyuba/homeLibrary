<?php
    $mysqli = new mysqli("localhost", "root", "", "homeLibrary");
	session_start();
	if(!$mysqli->connect_error)
    {
        $query = $mysqli->prepare("Delete from copies where copy_id=?");
        $query->bind_param("i",$_POST['CopyID']);
        $query->execute();
        $mysqli->close();
        header('Location: ../strony/mybooks.php');
        exit();
    }
    else
    {
        echo '<p>Błąd połączenia z bazą danych</p>';
        echo '<a href="javascript:history.go(-1);">Cofnij</a>';
    }
?>