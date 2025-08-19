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
    <title>Edytuj informacje o książcę</title>
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
		<div class="offset-xl-3 col-xl-6 offset-xl-3 offset-lg-3 col-lg-6 offset-lg-3 offset-md-3 col-md-6 offset-md-3 col-sm-12 col-12 mainlight">
			 <form action="../skrypty/editbook.php" method="post" enctype="multipart/form-data" autocomplete="on">
				<fieldset class="border border-5 border-danger rounded-3">
					<legend class="w-auto">Wymagane</legend>
					<br>
					<input type="number" value=
					<?php
						echo '"'.$_GET['id'].'"'; 
					?> name="BookID" hidden>
					<label class="text form-label">Tytuł:</label>
					<input type="text" name="Title" class="form-control" minlength="1" required value=
					<?php
						$mysqli = new mysqli("localhost", "root", "", "homeLibrary");
						if(!$mysqli->connect_error)
						{
							$results = $mysqli->prepare("Select title From allbooks where all_book_id=?");
							$results->bind_param("i",$_GET['id']);
							$results->execute();
							$row = $results->fetch_row();
							echo '"'.$row[0].'"';
							$mysqli->close();
						}
						else
						{
							echo "<p class='text'>Nie można połączyć z bazą</p>";
						}
					?>
					>
					<label class="text form-label">Wybierz język książki:</label>
					<select name="Language" required>
						<?php
							$mysqli = new mysqli("localhost", "root", "", "homeLibrary");
							if(!$mysqli->connect_error)
							{
								$results = $mysqli->query("Select * From languages");
								$clanguage = pg_query("Select language_id From allbooks Where all_book_id=?");
								$clanguage->bind_param("i",$_GET['id']);
								$clanguage->execute();
								$row2 = $clanguage->fetch_row();
								while($row1 = $results->fetch_row())
								{
									if($row1[0] != $row2[0])
									{
										echo '<option value="'.$row1[0].'">Język '.$row1[1].'</option>';
									}
									else
									{
										echo '<option value="'.$row1[0].'" selected>Język '.$row1[1].'</option>';
									}
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
								$results = $mysqli->query($connect,"Select * From genres");
								$cgenre = $mysqli->prepare("Select genre_id From allbooks Where all_book_id=?");
								$cgenre->bind_param("i",$_GET['id']);
								$cgenre->execute();
								$row2 = $cgenre->fetch_row();
								while($row1 = $results->fetch_row())
								{
									if($row1[0] != $row2[0])
									{
										echo '<option value="'.$row1[0].'">'.$row1[1].'</option>';
									}
									else
									{
										echo '<option value="'.$row1[0].'" selected>'.$row1[1].'</option>';
									}
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
					<a class="text" target="_blank" href="addgenre.php">Dodaj gatunek, jeśli nie znalazłeś go na liście i odśwież</a>
					<br>
					<label class="text form-label">Wybierz autora książki:</label>
					<?php
						$mysqli = new mysqli("localhost", "root", "", "homeLibrary");
						if(!$mysqli->connect_error)
						{
							$results = $mysqli->query($connect,"Select author_id, name, surname From authors");
							while($row1 = $results->fetch_row())
							{
								echo '<br>';
								echo '<input class="check" type="checkbox" name="Authors[]" value="'.$row1[0].'"';
								$results2 = $mysqli->prepare("Select author_id From authorship where all_book_id=?");
								$results2->bind_param("i",$_GET['id']);
								$results2->execute();
								while($row2 = $results2->fetch_row())
								{
									if($row1[0] == $row2[0])
									{
										echo "checked";
									}
								}
								echo '>';
								echo '<label class="text form-label">'.$row1[0].'.'.$row1[1].' '.$row1[2].'</label>';
							}
							$mysqli->close();
						}
						else
						{
							echo "<p class='text'>Nie można połączyć z bazą</p>";
						}
					?>
					<br>
					<a class="text" target="_blank" href="addauthor.php">Dodaj autora, jeśli nie znalazłeś go na liście i odśwież stronę</a>
					<br>
					<label class="text form-label">Wybierz serie, do której książka należy:</label>
					<select name="Serie" required>
						<?php
							$mysqli = new mysqli("localhost", "root", "", "homeLibrary");
							if(!$mysqli->connect_error)
							{
								$results = $mysqli->query($connect,"Select * From series");
								$cserie = $mysqli->prepare("Select serie_id From allBooks where all_book_id=?");
								$cserie->bind_param("i",$_GET['id']);
								$cserie->execute();
								$row2 = $cserie->fetch_row();
								while($row1 = pg_fetch_row($results))
								{
									if($row1[0] != $row2[0])
									{
										echo '<option value="'.$row1[0].'">'.$row1[1].'</option>';
									}
									else
									{
										echo '<option value="'.$row1[0].'" selected>'.$row1[1].'</option>';
									}
								}
								$mysqli->close();
							}
							else
							{
								echo "<p class='text'>Nie można połączyć z bazą</p>";
							}
						?>]
					</select>
					<br>
					<a class="text" target="_blank" href="addseries.php">Dodaj serie, jeśli nie znalazłeś jej na liście i odśwież stronę</a>
					<br>
				</fieldset>
				<label class="text form-label">Wydawca:</label>
				<input class="form-control" type="text" minlength="1" name="Publisher" value=
				<?php
					$mysqli = new mysqli("localhost", "root", "", "homeLibrary");
					if(!$mysqli->connect_error)
					{
						$result = $mysqli->prepare("Select publisher From allbooks Where all_book_id=?");
						$result->bind_param("i",$_GET['id']);
						$result->execute();
						$row = $result->fetch_row();
						echo '"'.$row[0].'"';
						$mysqli->close();
					}
					else
					{
						echo "<p class='text'>Nie można połączyć z bazą</p>";
					}
				?>
				>
				<label class="text form-label">Data wydania:</label>
				<input class="form-control" type="date" name="PublishDate" value=
				<?php
					$mysqli = new mysqli("localhost", "root", "", "homeLibrary");
					if(!$mysqli->connect_error)
					{
						$result = $mysqli->prepare("Select publish_date From allbooks Where all_book_id=?");
						$result->bind_param("i",$_GET['id']);
						$result->execute();
						$row = $result->fetch_row();
						echo '"'.$row[0].'"';
						$mysqli->close();
					}
					else
					{
						echo "<p class='text'>Nie można połączyć z bazą</p>";
					}
				?>
				>
				<label class="text form-label">Ilość stron:</label>
				<input class="form-control" type="number" value=
				<?php
					$mysqli = new mysqli("localhost", "root", "", "homeLibrary");
					if(!$mysqli->connect_error)
					{
						$result = $mysqli->prepare("Select pages From allbooks Where all_book_id=?");
						$result->bind_param("i",$_GET['id']);
						$result->execute();
						$row = $result->fetch_row();
						echo '"'.$row[0].'"';
						$mysqli->close();
					}
					else
					{
						echo "<p class='text'>Nie można połączyć z bazą</p>";
					}
				?>
				>
				<label class="text form-label">Opis:</label>
				<textarea class="form-control" rows="10" name="Description">
				<?php
					$mysqli = new mysqli("localhost", "root", "", "homeLibrary");
					if(!$mysqli->connect_error)
					{
						$result = $mysqli->prepare("Select description From allbooks Where all_book_id=?");
						$result->bind_param("i",$_GET['id']);
						$result->execute();
						$row = $result->fetch_row();
						echo $row[0];
						$mysqli->close();
					}
					else
					{
						echo "<p class='text'>Nie można połączyć z bazą</p>";
					}
				?>
				</textarea>
				<label class="text form-label">Nowa okładka:</label>
				<input name="Cover" type="file" accept="image/*">
				<br>
				<button class="btn btn-primary text" type="submit">Edytuj książkę</button> <button class="btn btn-secondary text" type="reset">Wyczyść</button>
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