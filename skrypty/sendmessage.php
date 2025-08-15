<?php
    $connect = pg_connect();
	session_start();
	if($connect)
    {
        $currentDate = date("Y-m-d");
        pg_query($connect, "INSERT INTO messages (user_id, title, content, date) VALUES (".$_SESSION['userID'].", '".str_replace("'","''",$_POST['Title'])."', '".str_replace("'","''",$_POST['Content'])."', '".$currentDate."')");
        pg_close();
        header('Location: ../strony/contact.php?m=send');
        exit;
    }
    else
    {
        echo '<p>Błąd połączenia z bazą danych</p>';
        echo '<a href="javascript:history.go(-1);">Cofnij</a>';
    }
?>