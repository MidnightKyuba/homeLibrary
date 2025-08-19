<?php
    session_start();
    if(!isset($_SESSION['userID']))
    {
        header('Location: ../index.php');
		exit();
    }
	else
	{
		if($_SESSION['rank'] != 1)
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
    <title>Wiadomości</title>
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
					<a class="dropdown-item" href="../skrypty/logout.php">Wyloguj</a>
				</div>
			</div>
		</div>
	</div>
	<div class="row main">
		<div class="offset-xl-2 col-xl-8 offset-xl-2 offset-lg-2 col-lg-8 offset-lg-2 offset-md-1 col-md-10 offset-md-1 col-sm-12 col-12 mainlight">
			<h1 class="title" style="color:black;">Widomości od użytkowników</h1>
			<hr color="red">
			<?php
				$mysqli = new mysqli("localhost", "root", "", "homeLibrary");
				if(!$mysqli->connect_error)
				{
					$results = $mysqli->query("Select title, content, date, login, email, message_id From messages natural join users");
					$number = 1;
					while($row1 = $results->fetch_row())
					{
						echo '<a name="'.$number.'">Wiadomość '.$number.'</a>';
						echo '<dl class="row">';
						echo '<dt class="text col-xl-5 col-lg-5 col-md-4 col-sm-3 col-3">Login użytkownika:</dt>';
						echo '<dd class="text col-xl-7 col-lg-7 col-md-8 col-sm-9 col-9" style="background-color:white;">'.$row1[3].'</dd>';
						echo '<dt class="text col-xl-5 col-lg-5 col-md-4 col-sm-3 col-3">Email użytkownika:</dt>';
						echo '<dd class="text col-xl-7 col-lg-7 col-md-8 col-sm-9 col-9" style="background-color:white;">'.$row1[4].'</dd>';
						echo '<dt class="text col-xl-5 col-lg-5 col-md-4 col-sm-3 col-3">Tytuł:</dt>';
						echo '<dd class="text col-xl-7 col-lg-7 col-md-8 col-sm-9 col-9" style="background-color:white;">'.$row1[0].'</dd>';
						echo '<dt class="text col-xl-5 col-lg-5 col-md-4 col-sm-3 col-3">Treść:</dt>';
						echo '<dd class="text col-xl-7 col-lg-7 col-md-8 col-sm-9 col-9" style="background-color:white;">'.$row1[1].'</dd>';
						echo '<dt class="text col-xl-5 col-lg-5 col-md-4 col-sm-3 col-3">Data wysłania:</dt>';
						echo '<dd class="text col-xl-7 col-lg-7 col-md-8 col-sm-9 col-9" style="background-color:white;">'.$row1[2].'</dd>';
						echo '</dl>';
						echo '<form action="../skrypty/deleteMessage.php" method="post" autocomplete="on">';
						echo '<input type="hidden" name="MessageId" value='.$row1[5].'>';
						echo '<input type="hidden" name="Number" value='.$number.'>';
						echo '<button class="btn btn-danger text form-control" type="submit">Usuń</button>';
						echo '</form>';
						echo '<br>';
						echo '<hr color="red">';
						$number=$number+1;
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