<?php
    $connect = pg_connect();
	session_start();
	if($connect)
    {
        pg_query($connect,"Delete from messages where message_id=".$_POST['MessageId']."");
        pg_close();
        header('Location: ../strony/messages.php#'.$_POST['Number'].'');
        exit();
    }
    else
    {
        echo '<p>Błąd połączenia z bazą danych</p>';
        echo '<a href="javascript:history.go(-1);">Cofnij</a>';
    }
?>