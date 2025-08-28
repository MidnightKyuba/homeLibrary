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
    <title>Informacje o serii</title>
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
						echo '<a class="dropdown-item" href="messages.php">Wiadomości</a>';
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
				$mysqli = new mysqli("localhost", "root", "", "homeLibrary");
				if(!$mysqli->connect_error)
				{
					$results = $mysqli->prepare("Select name, description from  series where serie_id=?");
					$results->bind_param("i", $_GET['id']);
					$results->execute();
					$results = $results->get_result();
					$row1 = $results->fetch_row();
					echo '<p class="text">Nazwa serii: '.$row1[0].'<br>Opis serii: '.$row1[1].'<br>Ile książek: ';
					$results = $mysqli->prepare("Select count(all_book_id) from allbooks where serie_id=?");
					$results->bind_param("i", $_GET['id']);
					$results->execute();
					$results = $results->get_result();
					$row2 = $results->fetch_row();
					echo $row2[0];
					echo '</p>';
					echo '<a class="btn btn-primary text" href="editserie.php?id='.$_GET['id'].'">Edytuj informacje</a>';
					echo '<hr>';
					$results = $mysqli->prepare("Select all_book_id, cover, title, s.name as serie from allbooks a inner join series s on a.serie_id = s.serie_id where a.serie_id=?");
					$results->bind_param("i", $_GET['id']);
					$results->execute();
					$results = $results->get_result();
					while($row3 = $results->fetch_row())
					{
						echo '<div class="card col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">';
						if(!empty($row[1]))
						{
							echo '<img class="card-img-top" src="../grafika/okladki/'.$row3[1].'?'.filemtime('../grafika/okladki/'.$row3[1]).'">';
						}
						else
						{
							echo '<img class="card-img-top" src="../grafika/null.png">';
						}
						echo '<div class="card-body">';
						echo '<h5 class="card-title">'.$row3[2].'</h5>';
						echo '<p class="card-text text">Autor: ';
						$results2 = $mysqli->prepare("Select authors.name, authors.surname from authors natural join authorship where all_book_id=?");
						$results2->bind_param("i", $row3[0]);
						$results2->execute();
						$results2 = $results2->get_result();
						while($row4 = $results2->fetch_row())
						{
							echo $row4[0].' '.$row4[1].', ';
						}
						echo '<br>Seria: '.$row3[3].'</p>';
						echo '<a href="bookinfo.php?id='.$row3[0].'" class="btn btn-primary">Więcej</a>';
						echo '</div>';
						echo '</div>';
					}
					$mysqli->close();
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