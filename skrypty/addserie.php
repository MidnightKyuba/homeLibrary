<?php
    $connect = pg_connect();
	session_start();
	if($connect)
    {
        pg_query($connect, "INSERT INTO series (name, description) VALUES ('".str_replace("'","''",$_POST['Name'])."','".str_replace("'","''",$_POST['Description'])."')");
        pg_close();
        echo '<script type="text/javascript">';
        echo 'window.close();';
        echo '</script>';
		exit();
    }
    else
    {
        echo '<p>Błąd połączenia z bazą danych</p>';
        echo '<a href="javascript:history.go(-1);">Cofnij</a>';
    }
?>