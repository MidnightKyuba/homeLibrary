<?php
    $mysqli = new mysqli("localhost", "root", "", "homeLibrary");
	session_start();
	if(!$mysqli->connect_error)
    {
        $query = $mysqli->prepare("INSERT INTO copies (all_book_id, user_id) VALUES (?,?)");
        $query->bind_param("ii",$_POST['BookID'],$_SESSION['userID']);
        $query->execute();
        $mysqli->close();
        header('Location: ../strony/bookinfo.php?id='.$_POST['BookID'].'');
		exit();
    }
    else
    {
        echo '<p>Błąd połączenia z bazą danych</p>';
        echo '<a href="javascript:history.go(-1);">Cofnij</a>';
    }
?>