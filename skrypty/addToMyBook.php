<?php
    $connect = pg_connect();
	session_start();
	if($connect)
    {
        pg_query($connect, "INSERT INTO copies (all_book_id, user_id) VALUES (".$_POST['BookID'].",".$_SESSION['userID'].")");
        pg_close();
        header('Location: ../strony/bookinfo.php?id='.$_POST['BookID'].'');
		exit();
    }
    else
    {
        echo '<p>Błąd połączenia z bazą danych</p>';
        echo '<a href="javascript:history.go(-1);">Cofnij</a>';
    }
?>