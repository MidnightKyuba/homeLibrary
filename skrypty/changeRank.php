<?php
    $connect = pg_connect();
	session_start();
	if($connect)
    {
        pg_query($connect, "Update users set rank_id=".$_POST['RankId']." where user_id=".$_POST['UserId']."");
        pg_close();
        header('Location: ../strony/profile.php?m=changeRank');
        exit();
    }
    else
    {
        echo '<p>Błąd połączenia z bazą danych</p>';
        echo '<a href="javascript:history.go(-1);">Cofnij</a>';
    }
?>