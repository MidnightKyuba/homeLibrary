<?php
	$mysqli = new mysqli("localhost", "root", "", "homeLibrary");
	session_start();
	if(!$mysqli->connect_error)
    {
        $name = $_POST['Name'];
		$surname = $_POST['Surname'];
        $query = $mysqli->prepare("INSERT INTO authors (name, surname, birth_date, death_date, life) VALUES (?,?,?,?,?)");
        if(!empty($_POST['Birth']))
        {
            $birth = $_POST['Birth'];
        }
        else
        {
            $birth = null;
        }
        if($_POST['ifDeath'] == 'true')
        {
            if(!empty($_POST['Death']))
            {
                $death = $_POST['Death'];
            }
            else
            {
                $death = null;
            }
        }
        else
        {
            $death = null;
        }
        if(!empty($_POST['Life']))
        {
            $life = $_POST['Life'];
        }
        else
        {
            $life = null;
        }
        $query->bind_param("sssss",$name,$surname,$birth,$death,$life);
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