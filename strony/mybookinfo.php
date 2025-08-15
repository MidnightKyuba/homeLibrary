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
    <title>Informacje o mojej książce</title>
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
					<a class="dropdown-item" href="mybooks.php">Moje książki</a>
					<a class="dropdown-item" href="profile.php">Profil</a>
					<?php
					if($_SESSION['rank']>1)
					{
						echo '<a class="dropdown-item" href="contact.php">Kontakt</a>';
					}
					else
					{
						echo '<a class="dropdown-item" href="#">Wiadomości</a>';
					}
					?>
					<a class="dropdown-item" href="../skrypty/logout.php">Wyloguj</a>
				</div>
			</div>
		</div>
	</div>
	<div class="row main">
		<div class="offset-xl-3 col-xl-6 offset-lg-2 col-lg-8 offset-md-1 col-ml-10 col-sm-12 col-12 mainlight">
			<?php
				$connect = pg_connect();
				if($connect)
				{
					$result1 = pg_query($connect,"Select a.all_book_id, cover, title, l.name as language, g.name as genre, a.serie_id, s.name as serie, publisher, publish_date, pages, a.description from allbooks a inner join copies c on a.all_book_id = c.all_book_id inner join languages l on l.language_id = a.language_id inner join genres g on g.genre_id = a.genre_id inner join series s on s.serie_id = a.serie_id where copy_id=".$_GET['id']."");
					$row1 = pg_fetch_row($result1);
					$results2 = pg_query($connect,"Select author_id, name, surname from authors natural join authorship where all_book_id=".$row1[0]);
					if(!empty($row1[1]))
					{
						echo '<img class="image-fluid" src="../grafika/okladki/'.$row1[1].'?'.filemtime('../grafika/okladki/'.$row1[1]).'">';
					}
					else
					{
						echo '<img class="image-fluid" src="../grafika/null.png">';
					}
					echo '<p class="text">Tytuł: '.$row1[2].'<br>Autorzy: <br>';
					while($row2 = pg_fetch_row($results2))
					{
						echo '<a href="authorinfo.php?id='.$row2[0].'">'.$row2[1].' '.$row2[2].'</a>';
						echo '<br>';
					}
					echo 'Język: '.$row1[3].'<br>
					Gatunek: '.$row1[4].'<br>
					Seris: <a href="seriesinfo.php?id='.$row1[5].'">'.$row1[6].'</a><br>
					Wydawnictwo: '.$row1[7].'<br>
					Data wydania: '.$row1[8].'<br>
					Ilość stron: '.$row1[9].'<br>
					Opis: '.$row1[10].'</p><hr>';
					echo '<p class="text">Obecnie czyta: ';
					$result = pg_query($connect,"Select who_reading from copies where copy_id=".$_GET['id']."");
					$row3 = pg_fetch_row($result);
					echo $row3[0];
					echo '</p>';
					echo '<form action="../skrypty/readMyBook.php" method="post">
					<input type="number" value="'.$_GET['id'].'" name="CopyID" hidden>
					<label class="text form-label">Podaj imię czytającego: </label>
					<input type="text" min="1" max="25" class="form-control" name="Reader">
					<button type="submit" class="text btn btn-primary">Zmień czytającego</button>
					</form>';
					echo '<form action="../skrypty/deleteFromMyBook.php" method="post">
					<input type="number" value="'.$_GET['id'].'" name="CopyID" hidden>
					<button type="submit" class="text btn btn-primary">Usuń z moich książek</button>
					</form>';
				}
				else
				{
					echo "<p class='text'>Nie można połączyć z bazą</p>";
				}
			?>
		</div>
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
</body>
</html>