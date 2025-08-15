<?php
	$connect = pg_connect();
    if($connect)
	{
		$results = pg_query($connect,"SELECT author_id FROM authorship WHERE all_book_id=1");
		$userID = pg_fetch_row($results);
		echo '<p>'.$userID[0].'</p>';
	}
    else
	{
		echo "<p class='text'>Nie można połączyć z bazą</p>";
	}
?>