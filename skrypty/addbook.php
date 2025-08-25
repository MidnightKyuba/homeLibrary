<?php
    $mysqli = new mysqli("localhost", "root", "", "homeLibrary");
	session_start();
	if(!$mysqli->connect_error)
    {
        $query = $mysqli->prepare("INSERT INTO allbooks (title, language_id, genre_id, serie_id, pages, publisher, publish_date, description, cover) VALUES (?,?,?,?,?,?,?,?,?) RETURNING all_book_id");
        if(!empty($_POST['Pages']))
        {
            $pages = $_POST['Pages'];
        }
        else
        {
            $pages = null;
        }
        if(!empty($_POST['Publisher']))
        {
            $publisher = $_POST['Publisher'];
        }
        else
        {
            $publisher = null;
        }
        if(!empty($_POST['PublishDate']))
        {
            $publishDate = $_POST['PublishDate'];
        }
        else
        {
            $publishDate = null;
        }
        if(!empty($_POST['Description']))
        {
            $description = $_POST['Description'];
        }
        else
        {
            $description = null;
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
            move_uploaded_file($_FILES['Cover']['tmp_name'], $folderPath.$coverFileName);
        }
        else
        {
            $coverFileName = null;
        }
        $query->bind_param("siiiissss",$_POST['Title'],$_POST['Language'],$_POST['Genre'],$_POST['Serie'],$pages,$publisher,$publishDate,$description,$coverFileName);
        $query->execute();
        $bookid = $query->fetch_row();
        foreach($_POST['Authors'] as $author)
        {
            $query = $mysqli->prepare("INSERT INTO authorship (all_book_id, author_id) VALUES (?,?)");
            $query->bind_param("ii",$bookid[0],$author);
            $query->execute();
        }
        $mysqli->close();
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