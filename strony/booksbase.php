<?php
    session_start();
    if(!isset($_SESSION['userID']))
    {
        header('Location: ../index.php');
		exit();
    }
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="author" content="Jakub Kittel">
	<meta name="description" content="Portal służy do zarządzania swoją biblioteką domową.">
	<meta name="keywords" content="bibloteka, domowa biblioteka, system zarządzania, zarządzanie, system zarządzania domową biblioteką">
    <title>Baza książęk</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<link href="../css/style.css" rel="stylesheet">
</head>
<body class="container-fluid">
	<div class="row naglowek">
		<h1 class="title col-xl-10 col-lg-8 col-md-6 col-sm-12 col-12 order-2">System zarządzania domową biblioteką</h1>
		<?php
			echo '<h5 class="col-xl-1 col-lg-2 col-md-3 col-sm-12 col-12 order-3">Witaj, '.$_SESSION['login'].'</h5>';
		?>
		<div class="col-xl-1 col-lg-2 col-md-3 col-sm-12 col-12 order-1">
			<div class="btn-group">
				<button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">Menu</button>
				<div class="dropdown-menu">
					<a class="dropdown-item" href="mainmenu.php">Menu główne</a>
					<a class="dropdown-item" href="mybooks.php">Moje książki</a>
					<a class="dropdown-item" href="profile.php">Profil</a>
					<?php
					if($_SESSION['rank']>1)
					{
						echo '<a class="dropdown-item" href="contact.php">Kontakt</a>';
					}
					else
					{
						echo '<a class="dropdown-item" href="messages.php">Wiadomości</a>';
					}
					?>
					<a class="dropdown-item" href="../skrypty/logout.php">Wyloguj</a>
				</div>
			</div>
		</div>
	</div>
	<div class="row buttonsbar">
		<div class="order-1 col-xl-4 col-lg-4 col-md-6 col-sm-6 col-6">
			<?php
				if($_SESSION['rank']<3)
				{
					echo '<a class="btn btn-light text" href="addbook.php">Dodaj książkę</a>';
				}
			?>
		</div>
		<div class="order-2 offset-xl-4 col-xl-4 offset-lg-4 col-lg-4 col-md-6 col-sm-6 col-6">
			<button class="btn btn-light text" onclick="VisibleOn();" style="float: right;">Wyszukaj</button>
		</div>
	</div>
	<div class="row search" id="search">
		<div class="offset-xl-1 col-xl-10 offset-xl-1 offset-lg-1 col-lg-10 offset-lg-1 offset-md-2 col-md-8 offset-md-2 col-sm-12 col-12">
			<form action="booksbase.php" method="GET">
				<label>Tytuł:</label>
				<input type="text form-control" name="Title"><br>
				<label>Seria</label>
				<select name="Serie">
					<option value="0">Wszstkie</option>
					<?php
						$connect = pg_connect();
						if($connect)
						{
							$results = pg_query($connect,"Select serie_id, name From series");
							while ($row = pg_fetch_row($results)) {
								echo '<option value="'.$row[0].'">'.$row[1].'</option>';
							}
						}
						else
						{
							echo "<p class='text'>Nie można połączyć z bazą</p>";
						}
					?>
				</select><br>
				<label>Gatunek:</label>
				<select name="Genre">
					<option value="0">Wszstkie</option>
					<?php
						$connect = pg_connect();
						if($connect)
						{
							$results = pg_query($connect,"select * from genres");
							while ($row = pg_fetch_row($results)) {
								echo '<option value="'.$row[0].'">'.$row[1].'</option>';
							}
						}
						else
						{
							echo "<p class='text'>Nie można połączyć z bazą</p>";
						}
					?>
				</select><br>
				<label>Autorzy:</label><br>
				<?php
					$connect = pg_connect();
					if($connect)
					{
						$results = pg_query($connect,"Select author_id, name, surname From authors");
						while ($row = pg_fetch_row($results)) {
							echo '<input type="checkbox" name="Authors[]" value="'.$row[0].'">';
							echo '<label class="text form-label">'.$row[0].'.'.$row[1].' '.$row[2].'</label>';
							echo '<br>';
						}
					}
					else
					{
						echo "<p class='text'>Nie można połączyć z bazą</p>";
					}
				?>
				<label>Publikacje autorów:</label><br>
				<input type="radio" name="Unity" value="0" checked><label>Wszystkie</label><br>
				<input type="radio" name="Unity" value="1"><label>Wspólne</label><br>
				<label>Język</label>
				<select name="Language">
					<option value="0">Wszstkie</option>
					<?php
						$connect = pg_connect();
						if($connect)
						{
							$results = pg_query($connect,"Select * From languages");
							while ($row = pg_fetch_row($results)) {
								echo '<option value="'.$row[0].'">Język '.$row[1].'</option>';
							}
							pg_close();
						}
						else
						{
							echo "<p class='text'>Nie można połączyć z bazą</p>";
						}
					?>
				</select><br>
				<button class="text btn btn-warning" type="submit">Szukaj</button>
				<button class="text btn btn-warning" type="reset">Usuń kryteria wyszukiwania</button>
			</form>
			<br>
			<button class="btn btn-dark text" onclick="VisibleOff();">Zamknij</button>
		</div>
	</div>
	<div class="row main">
		<?php
			search();
		?>
	</div>
	<div class="row stopka bg-secondary">
		<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 order-1">
			<label class="form-label text">Zmień rozmiar czionki:</label>
			<input class="form-range" type="range" onchange="changeSizeText(this.value);" min="0" max="6" default="3" step="1">
		</div>
		<div class="col-xl-4 col-lg-4 col-md-0 col-sm-0 col-0 order-2">
		</div>
		<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 order-3">
			<p class="text">Copyright © 2022 . All rights reserved. Jakub Kittel</p>
		</div>
	</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
	<script src="../skrypty/changeSizeText.js"></script>
	<script src="../skrypty/VOnElement.js"></script>
	<script src="../skrypty/VOffElement.js"></script>
</body>
</html>
<?php
	function search()
	{
		$query1 = "Select all_book_id from allbooks";
		$iffirst = 0;
		$iffirsta = 0;
		if(!empty($_GET['Title']))
		{
			if($iffirst == 0)
			{
				$query1 = $query1." where";
				$iffirst = 1;
			}
			else
			{
				$query1 = $query1." and";
			}
			$query1 = $query1." title Like '%".$_GET['Title']."%'";
		}
		if(!empty($_GET['Serie']))
		{
			if($iffirst == 0)
			{
				$query1 = $query1." where";
				$iffirst = 1;
			}
			else
			{
				$query1 = $query1." and";
			}
			$query1 = $query1." serie_id =".$_GET['Serie']."";
		}
		if(!empty($_GET['Genre']))
		{
			if($iffirst == 0)
			{
				$query1 = $query1." where";
				$iffirst = 1;
			}
			else
			{
				$query1 = $query1." and";
			}
			$query1 = $query1." genre_id=".$_GET['Genre']."";
		}
		if(!empty($_GET['Language']))
		{
			if($iffirst == 0)
			{
				$query1 = $query1." where";
				$iffirst = 1;
			}
			else
			{
				$query1 = $query1." and";
			}
			$query1 = $query1." language_id=".$_GET['Language']."";
		}
		$query2 ="Select all_book_id from authorship";
		if(!empty($_GET['Authors']))
		{
			$oquery2 = $query2;
			foreach($_GET['Authors'] as $author)
			{
				if($iffirsta == 0)
				{
					$query2 = $query2." where";
					$iffirsta = 1;
					$query2 = $query2." author_id=".$author."";
				}
				else
				{
					if($_GET['Unity'] == 0)
					{
						$query2 = $query2." union ".$oquery2." where author_id=".$author."";
					}
					else
					{
						$query2 = $query2." intersect ".$oquery2." where author_id=".$author."";
					}
				}
			}
		}
		$query = "Select all_book_id, cover, title, s.name as serie from allbooks a inner join series s on a.serie_id = s.serie_id";
		if($iffirst == 1 and $iffirsta == 1)
		{
			$query = $query." where all_book_id in (".$query1.") intersect ".$query." where all_book_id in (".$query2.")";
		}
		elseif($iffirst == 0 and $iffirsta == 1)
		{
			$query = $query." where all_book_id in (".$query2.")";
		}
		elseif($iffirst == 1 and $iffirsta == 0)
		{
			$query = $query." where all_book_id in (".$query1.")";
		}
		else
		{
			$query = $query;
		}
		$connect = pg_connect();
		if($connect)
		{
			$results = pg_query($connect, $query);
			if(!empty($results))
			{
				while ($row = pg_fetch_row($results)) {
					echo '<div class="card col-12 col-sm-12 col-md-4 col-lg-3 col-xl-2 mainlight">';
						if(!empty($row[1]))
						{
							echo '<img class="card-img-top" src="../grafika/okladki/'.$row[1].'?'.filemtime('../grafika/okladki/'.$row[1]).'">';
						}
						else
						{
							echo '<img class="card-img-top" src="../grafika/null.png">';
						}
						echo '<div class="card-body">';
							echo '<h5 class="card-title text">'.$row[2].'</h5>';
							echo '<p class="card-text text">Autor:<br>';
								$results2 = pg_query($connect, "Select name, surname from authors natural join authorship where all_book_id=".$row[0]);
								while($row2 = pg_fetch_row($results2))
								{
										echo $row2[0].' '.$row2[1].'<br>';
								}
								echo 'Seria: '.$row[3].'</p>';
							echo '<a href="bookinfo.php?id='.$row[0].'" class="text btn btn-primary">Więcej</a>';
						echo '</div>';
					echo '</div>';
				}
			}
		}
		else
		{
			echo "<p class='text'>Nie można połączyć z bazą</p>";
		}				
	}
?>