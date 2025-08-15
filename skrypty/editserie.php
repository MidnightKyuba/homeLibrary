<?php
    $connect = pg_connect();
	session_start();
	if($connect)
    {
        pg_query($connect,"UPDATE series SET name='".str_replace("'","''",$_POST['Name'])."' WHERE serie_id=".$_POST['SerieID']."");
        if(!empty($_POST['Description']))
        {
            pg_query($connect,"UPDATE series SET description='".str_replace("'","''",$_POST['Description'])."' WHERE serie_id=".$_POST['SerieID']."");
        }
        pg_close();
        header('Location: ../strony/seriesinfo.php?id='.$_POST['SerieID'].'');
		exit();
    }
    else
    {
        echo '<p>Błąd połączenia z bazą danych</p>';
        echo '<a href="javascript:history.go(-1);">Cofnij</a>';
    }
?>