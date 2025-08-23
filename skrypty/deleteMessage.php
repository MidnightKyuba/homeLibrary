<?php
    $mysqli = new mysqli("localhost", "root", "", "homeLibrary");
	session_start();
	if(!$mysqli->connect_error)
    {
        $query = $mysqli->prepare("Delete from messages where message_id=?");
        $query->bind_param("i",$_POST['MessageId']);
        $query->execute();
        $mysqli->close();
        header('Location: ../strony/messages.php#'.$_POST['Number'].'');
        exit();
    }
    else
    {
        echo '<p>Błąd połączenia z bazą danych</p>';
        echo '<a href="javascript:history.go(-1);">Cofnij</a>';
    }
?>