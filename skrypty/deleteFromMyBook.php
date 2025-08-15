<?php
    $connect = pg_connect();
	session_start();
	if($connect)
    {
        pg_query($connect,"Delete from copies where copy_id=".$_POST['CopyID']."");
        pg_close();
        header('Location: ../strony/mybooks.php');
        exit();
    }
    else
    {
        echo '<p>Błąd połączenia z bazą danych</p>';
        echo '<a href="javascript:history.go(-1);">Cofnij</a>';
    }
?>