<?php
	$connect = pg_connect();
	session_start();
	if($connect)
    {
        $query = "INSERT INTO authors (name, surname, birth_date, death_date, life) VALUES ('";
        $query =$query.str_replace("'", "''", $_POST['Name']);
		$query =$query."','";
		$query =$query.str_replace("'", "''",$_POST['Surname']);
		$query =$query."',";
        if(!empty($_POST['Birth']))
        {
            $query = $query."'".$_POST['Birth']."',";
        }
        else
        {
            $query = $query."null,";
        }
        if($_POST['ifDeath'] == 'true')
        {
            if(!empty($_POST['Death']))
            {
                $query = $query."'".$_POST['Death']."',";
            }
            else
            {
                $query = $query."null,";
            }
        }
        else
        {
            $query = $query."null,";
        }
        if(!empty($_POST['Life']))
        {
            $query = $query."'".str_replace("'","''",$_POST['Life'])."')";
        }
        else
        {
            $query = $query."null)";
        }
        pg_query($connect,$query);
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