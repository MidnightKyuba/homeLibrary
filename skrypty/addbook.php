<?php
    $connect = pg_connect();
	session_start();
	if($connect)
    {
        $query = "INSERT INTO allbooks (title, language_id, genre_id, serie_id, pages, publisher, publish_date, description, cover) VALUES ('".str_replace("'","''",$_POST['Title'])."',".$_POST['Language'].",".$_POST['Genre'].",".$_POST['Serie'].",";
        if(!empty($_POST['Pages']))
        {
            $query = $query.$_POST['Pages'].",";
        }
        else
        {
            $query = $query."null,";
        }
        if(!empty($_POST['Publisher']))
        {
            $query = $query."'".str_replace("'","''",$_POST['Publisher'])."',";
        }
        else
        {
            $query = $query."null,";
        }
        if(!empty($_POST['PublishDate']))
        {
            $query = $query."'".$_POST['PublishDate']."',";
        }
        else
        {
            $query = $query."null,";
        }
        if(!empty($_POST['Description']))
        {
            $query = $query."'".str_replace("'","''",$_POST['Description'])."',";
        }
        else
        {
            $query = $query."null,";
        }
        if(is_uploaded_file($_FILES['Cover']['tmp_name']))
        {
            $array = explode('.', $_FILES['Cover']['name']);
            $ext = end($array);
            $folderPath = '../grafika/okladki/';
            $file = glob($folderPath . '*');
            $countFile = 0;
            if ($file != false)
            {
                $countFile = count($file);
            }
            echo $countFile;
            for($i =1; $i <= $countFile; $i++)
            {
                if(file_exists($folderPath.'cover'.$i.'.'.$ext))
                {
                }
                else
                {
                    break;
                }
            }
            $coverFileName = 'cover'.$i.'.'.$ext;
            $query = $query."'".$coverFileName."')";
            move_uploaded_file($_FILES['Cover']['tmp_name'], $folderPath.$coverFileName);
        }
        else
        {
            $query = $query."null) ";
        }
        $query = $query." RETURNING all_book_id";
        $result = pg_query($connect, $query);
        $bookid = pg_fetch_row($result);
        foreach($_POST['Authors'] as $author)
        {
            pg_query($connect, "INSERT INTO authorship (all_book_id, author_id) VALUES (".$bookid[0].",".$author.")");
        }
        pg_close();
        header('Location: ../strony/booksbase.php');
        exit();
    }
    else
    {
        echo '<h1>Błąd</h1>';
        echo '<p>Błąd połączenia z bazą danych</p>';
        echo '<a href="javascript:history.go(-1);">Cofnij</a>';
    }
?>