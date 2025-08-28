<?php
    session_start();
    if(!isset($_SESSION['userID']))
    {
        header('Location: ../index.php');
		exit();
    }
	else
	{
		if($_SESSION['rank'] === 3)
		{
			header('Location: ../index.php');
			exit();
		}
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
    <title>Dodaj książkę</title>
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
					<a class="dropdown-item" href="mainmenu.php">Menu Główne</a>
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
		<div class="offset-xl-3 col-xl-6 offset-xl-3 offset-lg-3 col-lg-6 offset-lg-3 offset-md-3 col-md-6 offset-md-3 col-sm-12 col-12 mainlight">
			 <form action="../skrypty/addbook.php" method="post" enctype="multipart/form-data" autocomplete="on">
				 <fieldset class="border border-5 border-danger rounded-3">
					<legend class="w-auto">Wymagane:</legend>
					<br>
					<label class="text form-label">Tytuł:</label>
					<input class="form-control" type="text" minlength="1" name="Title" required>
					<label class="text form-label">Wybierz język książki:</label>
					<select name="Language" required>
						<?php
							$mysqli = new mysqli("localhost", "root", "", "homeLibrary");
    						if(!$mysqli->connect_error)
							{
								$results = $mysqli->query("Select * From languages");
								while ($row = $results->fetch_row()) 
								{
									echo '<option value="'.$row[0].'">Język '.$row[1].'</option>';
								}
								$mysqli->close();
							}
							else
							{
								echo "<p class='text'>Nie można połączyć z bazą</p>";
							}
						?>
					</select>
					<br>
					<label class="text form-label">Wybierz gatunek książki:</label>
					<select name="Genre" required>
						<?php
							$mysqli = new mysqli("localhost", "root", "", "homeLibrary");
    						if(!$mysqli->connect_error)
							{
								$results = $mysqli->query("Select * From genres");
								while ($row = $results->fetch_row())
								{
									echo '<option value="'.$row[0].'">'.$row[1].'</option>';
								}
								$mysqli->close();
							}
							else
							{
								echo "<p class='text'>Nie można połączyć z bazą</p>";
							}
						?>
					</select>
					<br>
					<a class="text" target="_blank" href="addgenre.php">Dodaj gatunek, jeśli nie znalazłeś go na liście i odśwież stronę</a>
					<br>
					<label class="text form-label">Wybierz autora książki:</label>
					<br>
					<?php
						$mysqli = new mysqli("localhost", "root", "", "homeLibrary");
						if(!$mysqli->connect_error)
						{
							$results = $mysqli->query("Select author_id, name, surname From authors");
							while($row = $results->fetch_row())
							{
								echo '<input class="check" type="checkbox" name="Authors[]" value="'.$row[0].'" required>';
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
					<a class="text" target="_blank" href="addauthor.php">Dodaj autora, jeśli nie znalazłeś go na liście i odśwież stronę</a>
					<br>
					<label class="text form-label">Wybierz serie, do której książka należy:</label>
					<select name="Serie" required>
						<?php
							$mysqli = new mysqli("localhost", "root", "", "homeLibrary");
							if(!$mysqli->connect_error)
							{
								$results = $mysqli->query("Select serie_id, name From series");
								while($row = $results->fetch_row())
								{
									echo '<option value="'.$row[0].'">'.$row[1].'</option>';
								}
							}
							else
							{
								echo "<p class='text'>Nie można połączyć z bazą</p>";
							}
						?>
					</select>
					<br>
					<a class="text" target="_blank" href="addseries.php">Dodaj serie, jeśli nie znalazłeś jej na liście i odśwież stronę</a>
					<br>
				</fieldset>
				<label class="text form-label">Wydawca:</label>
				<input class="form-control" type="text" minlength="1" name="Publisher">
				<label class="text form-label">Data wydania:</label>
				<input class="form-control" type="date" name="PublishDate">
				<label class="text form-label">Ilość stron:</label>
				<input class="form-control" type="number" min="0" name="Pages">
				<label class="text form-label">Opis:</label>
				<textarea class="form-control" rows="10" name="Description"></textarea>
				<label class="text form-label">Okładka:</label>
				<input name="Cover" type="file" accept="image/*">
				<br>
				<button class="btn btn-primary text" type="submit">Dodaj książkę</button> <button class="btn btn-secondary text" type="reset">Wyczyść</button>
			 </form>
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
	<script type="text/javascript">
		$(document).ready(function(){
			var checkboxes = $('.check');
			checkboxes.change(function(){
				if($('.check:checked').length>0) 
				{
					checkboxes.removeAttr('required');
				} 
				else 
				{
					checkboxes.attr('required', 'required');
				}
			});
		});
	</script>
</body>
</html>