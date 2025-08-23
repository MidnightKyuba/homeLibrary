<?php
    $mysqli = new mysqli("localhost", "root", "", "homeLibrary");
	session_start();
	if(!$mysqli->connect_error)
    {
        $currentDate = date("Y-m-d");
        $query = $mysqli->prepare("INSERT INTO messages (user_id, title, content, date) VALUE (?,?,?,?)");
        $query->bind_param("isss",$_SESSION['userID'],$_POST['Title'],$_POST['Content'],$currentDate);
        $query->execute();
        $mysqli->close();
        header('Location: ../strony/contact.php?m=send');
        exit;
    }
    else
    {
        echo '<p>Błąd połączenia z bazą danych</p>';
        echo '<a href="javascript:history.go(-1);">Cofnij</a>';
    }
?>