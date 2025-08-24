<?php
    $mysqli = new mysqli("localhost", "root", "", "homeLibrary");
	session_start();
	if(!$mysqli->connect_error)
    {
        $query = $mysqli->prepare("Update users set rank_id=? where user_id=?");
        $query->bind_param("ii",$_POST['RankId'],$_POST['UserId']);
        $query->execute();
        $mysqli->close();
        header('Location: ../strony/profile.php?m=changeRank');
        exit();
    }
    else
    {
        echo '<p>Błąd połączenia z bazą danych</p>';
        echo '<a href="javascript:history.go(-1);">Cofnij</a>';
    }
?>