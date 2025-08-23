<?php
    $mysqli = new mysqli("localhost", "root", "", "homeLibrary");
	session_start();
	if(!$mysqli->connect_error)
    {
        $query = $mysqli->prepare("UPDATE series SET name=? WHERE serie_id=?");
        $query->bind_param("si",$_POST['Name'],$_POST['SerieID']);
        $query->execute();
        if(!empty($_POST['Description']))
        {
            $query = $mysqli->prepare("UPDATE series SET description=? WHERE serie_id=?");
            $query->bind_param("si",$_POST['Description'],$_POST['SerieID']);
            $query->execute();
        }
        $mysqli->close();
        header('Location: ../strony/seriesinfo.php?id='.$_POST['SerieID'].'');
		exit();
    }
    else
    {
        echo '<p>Błąd połączenia z bazą danych</p>';
        echo '<a href="javascript:history.go(-1);">Cofnij</a>';
    }
?>