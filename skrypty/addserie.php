<?php
    $mysqli = new mysqli("localhost", "root", "", "homeLibrary");
	session_start();
	if(!$mysqli->connect_error)
    {
        $query = $mysqli->prepare("INSERT INTO series (name, description) VALUES (?,?)");
        $query->bind_param('ss',$_POST['Name'],$_POST['Description']);
        $query->execute();
        $mysqli->close();
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