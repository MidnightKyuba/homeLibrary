<?php
    $connect = pg_connect();
	session_start();
	if($connect)
    {
        pg_query($connect, "UPDATE allbooks SET title='".str_replace("'","''",$_POST['Title'])."', language_id=".$_POST['Language'].", genre_id=".$_POST['Genre'].", serie_id=".$_POST['Serie']." where all_book_id=".$_POST['BookID']);
        if(!empty($_POST['Pages']))
        {
            pg_query($connect, "UPDATE allbooks SET pages=".$_POST['Pages']." where all_book_id=".$_POST['BookID']);
        }
        else
        {
            pg_query($connect, "UPDATE allbooks SET pages=null where all_book_id=".$_POST['BookID']);
        }
        if(!empty($_POST['Publisher']))
        {
            pg_query($connect, "UPDATE allbooks SET publisher='".str_replace("'","''",$_POST['Publisher'])."' where all_book_id=".$_POST['BookID']);
        }
        else
        {
            pg_query($connect, "UPDATE allbooks SET publisher=null where all_book_id=".$_POST['BookID']);
        }
        if(!empty($_POST['PublishDate']))
        {
            pg_query($connect, "UPDATE allbooks SET publish_date='".$_POST['PublishDate']."' where all_book_id=".$_POST['BookID']);
        }
        else
        {
            pg_query($connect, "UPDATE allbooks SET publish_date=null where all_book_id=".$_POST['BookID']);
        }
        if(!empty($_POST['Description']))
        {
            pg_query($connect, "UPDATE allbooks SET description='".str_replace("'","''",$_POST['Description'])."' where all_book_id=".$_POST['BookID']);
        }
        else
        {
            pg_query($connect, "UPDATE allbooks SET decription=null where all_book_id=".$_POST['BookID']);
        }
        if(is_uploaded_file($_FILES['Cover']['tmp_name']))
        {
            $result = pg_query($connect, "SELECT cover FROM allbooks WHERE all_book_id=".$_POST['BookID']);
            $cover = pg_fetch_row($result);
            if(!empty($cover[0]))
            {
                if(file_exists("../grafika/okladki/".$cover[0]))
                {
                    unlink("../grafika/okladki/".$cover[0]);
                }
            }
            $array = explode('.', $_FILES['Cover']['name']);
            $ext = end($array);
            $folderPath = '../grafika/okladki/';
            $file = glob($folderPath . '*');
            $countFile = 0;
            if ($file != false)
            {
                $countFile = count($file);
            }
            for($i = 1;$i <= $countFile; $i++)
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
            pg_query($connect, "UPDATE allbooks SET cover='".$coverFileName."' where all_book_id=".$_POST['BookID']);
            move_uploaded_file($_FILES['Cover']['tmp_name'], $folderPath.$coverFileName);
        }
        pg_query($connect, "DELETE FROM authorship WHERE all_book_id=".$_POST['BookID']);
        foreach($_POST['Authors'] as $author)
        {
            pg_query($connect, "INSERT INTO authorship (all_book_id, author_id) VALUES (".$_POST['BookID'].",".$author.")");
        }
        pg_close();
        header('Location: ../strony/bookinfo.php?id='.$_POST['BookID']);
        exit();
    }
    else
    {
        echo '<h1>Błąd</h1>';
        echo '<p>Błąd połączenia z bazą danych</p>';
        echo '<a href="javascript:history.go(-1);">Cofnij</a>';
    }
?>