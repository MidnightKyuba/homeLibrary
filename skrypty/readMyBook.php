<?php
    $connect = pg_connect();
	session_start();
	if($connect)
    {
        pg_query($connect, "UPDATE copies SET who_reading='".$_POST['Reader']."' where copy_id=".$_POST['CopyID']."");
        pg_close();
        header('Location: ../strony/mybookinfo.php?id='.$_POST['CopyID'].'');
        exit;
    }
    else
    {
        echo '<p>Błąd połączenia z bazą danych</p>';
        echo '<a href="javascript:history.go(-1);">Cofnij</a>';
    }
?>