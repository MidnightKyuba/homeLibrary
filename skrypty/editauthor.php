<?php
    $mysqli = new mysqli("localhost", "root", "", "homeLibrary");
	session_start();
	if(!$mysqli->connect_error)
    {
        $query = $mysqli->prepare("UPDATE authors SET name=? WHERE author_id=?");
        $query->bind_param("si",$_POST['Name'],$_POST['AuthorID']);
        $query->execute();
        $query = $mysqli->prepare("UPDATE authors SET surname=? WHERE author_id=?");
        $query->bind_param("si",$_POST['Surname'],$_POST['AuthorID']);
        $query->execute();
        if(!empty($_POST['Birth']))
        {
            $query = $mysqli->prepare("UPDATE authors SET birth_date=? WHERE author_id=?");
            $query->bind_param("si",$_POST['Birth'],$_POST['AuthorID']);
            $query->execute();
        }
        else
        {
            $query = $mysqli->prepare("UPDATE authors SET birth_date=null WHERE author_id=?");
            $query->bind_param("i",$_POST['AuthorID']);
            $query->execute();
        }
        if($_POST['ifDeath'] == 'true')
        {
            if(!empty($_POST['Death']))
            {
                $query = $mysqli->prepare("UPDATE authors SET death_date=? WHERE author_id=?");
                $query->bind_param("si",$_POST['Death'],$_POST['AuthorID']);
                $query->execute();
            }
            else
            {
                $query = $mysqli->prepare("UPDATE authors SET death_date=null WHERE author_id=?");
                $query->bind_param("i",$_POST['AuthorID']);
                $query->execute();
            }
        }
        else
        {
            $query = $mysqli->prepare("UPDATE authors SET death_date=null WHERE author_id=?");
            $query->bind_param("i",$_POST['AuthorID']);
            $query->execute();
        }
        if(!empty($_POST['Life']))
        {
            $query = $mysqli->prepare("UPDATE authors SET life=? WHERE author_id=?");
            $query->bind_param("si",$_POST['Life'],$_POST['AuthorID']);
            $query->execute();
        }
        else
        {
            $query = $mysqli->prepare("UPDATE authors SET life=null WHERE author_id=?");
            $query->bind_param("i",$_POST['AuthorID']);
            $query->execute();
        }
        $mysqli->close();
        header('Location: ../strony/authorinfo.php?id='.$_POST['AuthorID'].'');
		exit();
    }
    else
    {
        echo '<p>Błąd połączenia z bazą danych</p>';
        echo '<a href="javascript:history.go(-1);">Cofnij</a>';
    }
?>