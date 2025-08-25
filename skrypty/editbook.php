<?php
    $mysqli = new mysqli("localhost", "root", "", "homeLibrary");
	session_start();
	if(!$mysqli->connect_error)
    {
        $query = $mysqli->prepare("UPDATE allbooks SET title=?, language_id=?, genre_id=?, serie_id=? where all_book_id=?");
        $query->bind_param("siiii",$_POST['Title'],$_POST['Language'],$_POST['Genre'],$_POST['Serie'],$_POST['BookID']);
        $query->execute();
        if(!empty($_POST['Pages']))
        {
            $query = $mysqli->prepare("UPDATE allbooks SET pages=? where all_book_id=?");
            $query->bind_param("ii",$_POST['Pages'],$_POST['BookID']);
            $query->execute();
        }
        else
        {
            $query = $mysqli->prepare("UPDATE allbooks SET pages=null where all_book_id=?");
            $query->bind_param("i",$_POST['BookID']);
            $query->execute();
        }
        if(!empty($_POST['Publisher']))
        {
            $query = $mysqli->prepare("UPDATE allbooks SET publisher=? where all_book_id=?");
            $query->bind_param("si",$_POST['Publisher'],$_POST['BookID']);
            $query->execute();
        }
        else
        {
            $query = $mysqli->prepare("UPDATE allbooks SET publisher=null where all_book_id=?");
            $query->bind_param("i",$_POST['BookID']);
            $query->execute();
        }
        if(!empty($_POST['PublishDate']))
        {
            $query = $mysqli->prepare("UPDATE allbooks SET publish_date=? where all_book_id=?");
            $query->bind_param("si",$_POST['PublishDate'],$_POST['BookID']);
            $query->execute();
        }
        else
        {
            $query = $mysqli->prepare("UPDATE allbooks SET publish_date=null where all_book_id=?");
            $query->bind_param("i",$_POST['BookID']);
            $query->execute();
        }
        if(!empty($_POST['Description']))
        {
            $query = $mysqli->prepare("UPDATE allbooks SET description=? where all_book_id=?");
            $query->bind_param("si",$_POST['Description'],$_POST['BookID']);
            $query->execute();
        }
        else
        {
            $query = $mysqli->prepare("UPDATE allbooks SET description=null where all_book_id=?");
            $query->bind_param("i",$_POST['BookID']);
            $query->execute();
        }
        if(is_uploaded_file($_FILES['Cover']['tmp_name']))
        {
            $result = $mysqli->prepare("SELECT cover FROM allbooks WHERE all_book_id=?");
            $result->bind_param("i",$_POST['BookID']);
            $result->execute();
            $cover = $result->fetch_row();
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
            $query = $mysqli->prepare("UPDATE allbooks SET cover=? where all_book_id=?");
            $query->bind_param("si",$coverFileName,$_POST['BookID']);
            $query->execute();
            move_uploaded_file($_FILES['Cover']['tmp_name'], $folderPath.$coverFileName);
        }
        $query = $mysqli->prepare("DELETE FROM authorship WHERE all_book_id=?");
        $query->bind_param("i",$_POST['BookID']);
        $query->execute();
        foreach($_POST['Authors'] as $author)
        {
            $query = $mysqli->prepare("INSERT INTO authorship (all_book_id, author_id) VALUES (?,?)");
            $query->bind_param("ii",$_POST['BookID'],$author);
            $query->execute();
        }
        $mysqli->close();
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