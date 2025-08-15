<?php
    session_start();
    if(isset($_SESSION['userID']))
    {
        header('Location: ../strony/mainmenu.php');
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
    <title>Rejestracja</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<link href="../css/style.css" rel="stylesheet">
</head>
<body class="container-fluid">
	<div class="naglowek row">
		<h1 class="title">System zarządzania domową biblioteką</h1>
	</div>
	<div class="row main backgroundone">
		<div class="offset-md-3 col-md-6 offset-md-3 col-sm-12 col-12">
			<?php
				if(isset($_SESSION['error']))
				{
					if($_SESSION['error'] =='RF')
					{
						echo 'Błąd rejestracji';
					}
					else if($_SESSION['error'] =='CF')
					{
						echo 'Błąd połączenia z bazą danych';
					}
				}
			?>
			<form action="../skrypty/reg.php" method="post" autocomplete="on">
				<label class="form-label text">Nazwa urzytkownika </label>
				<input class="form-control" type="text" required name="Login" minlength="3" maxlength="30">
				<br>
				<label class="form-label text">E-mail </label>
				<input class="form-control" type="email" required name="Email">
				<br>
				<label class="form-label text">Hasło </label>
				<input class="form-control" type="password" required name="Password" minlength="8" maxlength="30">
				<br>
				<label class="form-label text">Powtórz hasło </label>
				<input class="form-control" type="password" required name="ControlPassword" minlength="8" maxlength="30">
				<br>
				<button class="btn btn-primary text" type="submit">Wyślij</button> 
				<button class="btn btn-primary text" type="reset">Wyczyść</button>
			</form>
			<br>
			<a class="btn btn-secondary text" href="../index.php">Wróć do logowania</a>
		</div>
	</div>
	<div class="row stopka bg-secondary">
		<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 order-1">
			<label class="form-label text">Zmień rozmiar czionki:</label>
			<input class="form-range" type="range" onchange="changeSizeText(this.value)" min="0" max="6" value="3" step="1">
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