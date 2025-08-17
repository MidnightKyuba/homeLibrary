<?php
	$mysqli = new mysqli("localhost", "root", "", "homeLibrary");
    if(!$mysqli->connect_error)
	{
		$results = $mysqli->query("SELECT rank_name FROM ranks WHERE rank_id=3");
		$rankName = $results->fetch_row();
		echo '<p>'.$rankName[0].'</p>';
		$mysqli->close();
	}
    else
	{
		echo "<p class='text'>Nie można połączyć z bazą</p>";
	}
?>