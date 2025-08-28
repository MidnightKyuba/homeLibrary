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
    <title>Baza moich książek</title>
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
					<a class="dropdown-item" href="booksbase.php">Baza książek</a>
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
				echo '<a class="btn btn-light text" href="booksbase.php">Dodaj książkę</a>';
			?>
		</div>
		<div class="order-2 offset-xl-4 col-xl-4 offset-lg-4 col-lg-4 col-md-6 col-sm-6 col-6">
			<button class="btn btn-light text" onclick="VisibleOn();" style="float: right;">Wyszukaj</button>
		</div>
	</div>
	<div class="row search" id="search">
		<div class="offset-xl-1 col-xl-10 offset-xl-1 offset-lg-1 col-lg-10 offset-lg-1 offset-md-2 col-md-8 offset-md-2 col-sm-12 col-12">
			<form action="mybooks.php" method="GET">
				<label>Tytuł:</label>
				<input type="text form-control" name="Title"><br>
				<label>Seria</label>
				<select name="Serie">
					<option value="0">Wszstkie</option>
					<?php
						$mysqli = new mysqli("localhost", "root", "", "homeLibrary");
						if(!$mysqli->connect_error)
						{
							$results = $mysqli->query("Select serie_id, name From series");
							while ($row = $results->fetch_row()) {
								echo '<option value="'.$row[0].'">'.$row[1].'</option>';
							}
							$mysqli->close();
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
						$mysqli = new mysqli("localhost", "root", "", "homeLibrary");
						if(!$mysqli->connect_error)
						{
							$results = $mysqli->query("select * from genres");
							while ($row = $results->fetch_row()) {
								echo '<option value="'.$row[0].'">'.$row[1].'</option>';
							}
							$mysqli->close();
						}
						else
						{
							echo "<p class='text'>Nie można połączyć z bazą</p>";
						}
					?>
				</select><br>
				<label>Autorzy:</label><br>
				<?php
					$mysqli = new mysqli("localhost", "root", "", "homeLibrary");
					if(!$mysqli->connect_error)
					{
						$results = $mysqli->query("Select author_id, name, surname From authors");
						while ($row = $results->fetch_row()) {
							echo '<input type="checkbox" name="Authors[]" value="'.$row[0].'">';
							echo '<label class="text form-label">'.$row[0].'.'.$row[1].' '.$row[2].'</label>';
							echo '<br>';
						}
						$mysqli->close();
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
						$mysqli = new mysqli("localhost", "root", "", "homeLibrary");
						if(!$mysqli->connect_error)
						{
							$results = $mysqli->query("Select * From languages");
							while ($row = $results->fetch_row()) {
								echo '<option value="'.$row[0].'">Język '.$row[1].'</option>';
							}
							$mysqli->close();
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
			<button class="btn btn-light text" onclick="VisibleOff();">Zamknij</button>
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
			<input class="form-range" type="range" onchange="changeSizeText(this.value)" min="0" max="6" default="3" step="1">
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
		$mysqli = new mysqli("localhost", "root", "", "homeLibrary");
		if(!$mysqli->connect_error)
		{
			$titleResult = [];
			$serieResult = [];
			$genreResult = [];
			$languageResult = [];
			$authorResult = [];
			if(!empty($_GET['Title']))
			{
				$result = $mysqli->prepare("Select all_book_id from allbooks where title Like ?");
				$title = "%".$_GET['Title']."%";
				$result->bind_param("s", $title);
				$result->execute();
				$result = $result->get_result();
				while($row = $result->fetch_row())
				{
					$titleResult[] = $row;
				}
				if(empty($titleResult))
				{
					return;
				}
			}
			if(!empty($_GET['Serie']))
			{
				$result = $mysqli->prepare("Select all_book_id from allbooks where serie_id=?");
				$result->bind_param("i", $_GET['Serie']);
				$result->execute();
				$result = $result->get_result();
				while($row = $result->fetch_row())
				{
					$serieResult[] = $row;
				}
				if(empty($serieResult))
				{
					return;
				}
			}
			if(!empty($_GET['Genre']))
			{
				$result = $mysqli->prepare("Select all_book_id from allbooks where genre_id=?");
				$result->bind_param("i", $_GET['Genre']);
				$result->execute();
				$result = $result->get_result();
				while($row = $result->fetch_row())
				{
					$genreResult[] = $row;
				}
				if(empty($genreResult))
				{
					return;
				}
			}
			if(!empty($_GET['Language']))
			{
				$result = $mysqli->prepare("Select all_book_id from allbooks where language_id=?");
				$result->bind_param("i", $_GET['Language']);
				$result->execute();
				$result = $result->get_result();
				while($row = $result->fetch_row())
				{
					$languageResult[] = $row;
				}
				if(empty($languageResult))
				{
					return;
				}
			}
			if(!empty($_GET['Authors']))
			{
				foreach($_GET['Authors'] as $author)
				{
					$result = $mysqli->prepare("Select all_book_id from authorship where author_id=?");
					$result->bind_param("i", $author);
					$result->execute();
					$result = $result->get_result();
					$temporaryresult = [];
					while($row = $result->fetch_row())
					{
						$temporaryresult[] = $row;
					}
					if($_GET['Unity'] == 0)
					{
						$authorResult = array_merge($authorResult, $temporaryresult);
					}
					else
					{
						$authorResult = array_intersect($authorResult, $temporaryresult);
					}
				}
				if(empty($authorResult))
				{
					return;
				}
			}
			$bookIdIntersect = [];
			$arrayResults = array ($titleResult, $serieResult, $genreResult, $languageResult, $authorResult);
			$iffirst = 0;
			foreach($arrayResults as $array)
			{
				if(!empty($array))
				{
					if($iffirst == 0)
					{
						$bookIdIntersect = array_merge($bookIdIntersect, $array);
						$iffirst = 1;
					}
					else
					{
						$bookIdIntersect = array_intersect($bookIdIntersect, $array);
					}
				}
			}

			if(!empty($bookIdIntersect))
			{
				$results = $mysqli->prepare("Select copy_id, a.all_book_id, a.cover, a.title, s.name as serie from copies c inner join allbooks a on c.all_book_id = a.all_book_id inner join series s on s.serie_id = a.serie_id where c.user_id=? and c.all_book_id IN (?)");
				$ids = implode(",",$bookIdIntersect[0]);
				$results->bind_param("is", $_SESSION['userID'], $ids);
			}
			else
			{
				$results = $mysqli->prepare("Select copy_id, a.all_book_id, a.cover, a.title, s.name as serie from copies c inner join allbooks a on c.all_book_id = a.all_book_id inner join series s on s.serie_id = a.serie_id where c.user_id=?");
				$results->bind_param("i", $_SESSION['userID']);
			}
			$results->execute();
			$results = $results->get_result();
			if(!empty($results))
			{
				while($row1 = $results->fetch_row())
				{
					echo '<div class="card col-12 col-sm-12 col-md-4 col-lg-3 col-xl-2 mainlight">';
						if(!empty($row1[2]))
						{
							echo '<img class="card-img-top" src="../grafika/okladki/'.$row1[2].'?'.filemtime('../grafika/okladki/'.$row1[2]).'">';
						}
						else
						{
							echo '<img class="card-img-top" src="../grafika/null.png">';
						}
						echo '<div class="card-body">';
						echo '<h5 class="card-title text">'.$row1[3].'</h5>';
						echo '<p class="card-text text">Autor:<br>';
						$results2 = $mysqli->prepare("Select name, surname from authors natural join authorship where all_book_id=?");
						$results2->bind_param("i", $row1[1]);
						$results2->execute();
						$results2 = $results2->get_result();
						while($row2 = $results2->fetch_row())
						{
							echo $row2[0].' '.$row2[1].'<br>';
						}
						echo 'Seria: '.$row1[4].'</p>';
						echo '<a href="mybookinfo.php?id='.$row1[0].'" class="text btn btn-primary">Więcej</a>';
						echo '</div>';
					echo '</div>';
				}
			}
			$mysqli->close();
		}
		else
		{
			echo "<p class='text'>Nie można połączyć z bazą</p>";
		}	
	}
?>