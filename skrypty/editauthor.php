<?php
    $connect = pg_connect();
	session_start();
	if($connect)
    {
        pg_query($connect,"UPDATE authors SET name='".str_replace("'","''",$_POST['Name'])."' WHERE author_id=".$_POST['AuthorID']."");
        pg_query($connect,"UPDATE authors SET surname='".str_replace("'","''",$_POST['Surname'])."' WHERE author_id=".$_POST['AuthorID']."");
        if(!empty($_POST['Birth']))
        {
            pg_query($connect,"UPDATE authors SET birth_date='".$_POST['Birth']."' WHERE author_id=".$_POST['AuthorID']."");
        }
        else
        {
            pg_query($connect,"UPDATE authors SET birth_date=null WHERE author_id=".$_POST['AuthorID']."");
        }
        if($_POST['ifDeath'] == 'true')
        {
            if(!empty($_POST['Death']))
            {
                pg_query($connect,"UPDATE authors SET death_date='".$_POST['Death']."' WHERE author_id=".$_POST['AuthorID']."");
            }
            else
            {
                pg_query($connect,"UPDATE authors SET death_date=null WHERE author_id=".$_POST['AuthorID']."");
            }
        }
        else
        {
            pg_query($connect,"UPDATE authors SET death_date=null WHERE author_id=".$_POST['AuthorID']."");
        }
        if(!empty($_POST['Life']))
        {
            pg_query($connect,"UPDATE authors SET life='".str_replace("'","''",$_POST['Life'])."' WHERE author_id=".$_POST['AuthorID']."");
        }
        else
        {
            pg_query($connect,"UPDATE authors SET life=null WHERE author_id=".$_POST['AuthorID']."");
        }
        pg_close();
        header('Location: ../strony/authorinfo.php?id='.$_POST['AuthorID'].'');
		exit();
    }
    else
    {
        echo '<p>Błąd połączenia z bazą danych</p>';
        echo '<a href="javascript:history.go(-1);">Cofnij</a>';
    }
?>