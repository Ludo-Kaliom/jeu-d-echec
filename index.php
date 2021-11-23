<?php
// On inclue le fichier de connexion a la base de donnees
include("database.php");
// On d�marre ou on r�cup�re la session courante
session_start();

// On invalide le cache de session $_SESSION
if(isset($_SESSION['login'])){
	$_SESSION = [];
} if(isset($_POST['login'])){
	// On récupère le nom de l'utilisateur saisi dans le formulaire
	$name = $_POST['name'];
	// On récupère le mot de passe saisi par l'utilisateur et on le crypte (fonction md5)
	$password = md5($_POST['password']);
	// On construit la requete qui permet de retrouver l'utilisateur
	$dbh = new DataBase();
	$sql = "SELECT * FROM player WHERE login=:name AND password=:password";
	$query = $dbh->prepare($sql);
	$query->bindParam(':name', $name, PDO::PARAM_STR);
	$query->bindParam(':password', $password, PDO::PARAM_STR);
	// On execute la requete
	$query->execute();
	$result = $query->fetch(PDO::FETCH_OBJ);
		if(!empty($result)){
		// Si le resultat de recherche n'est pas vide
		// On stocke le login et l'id de l'utilisateur  $_POST['login'] dans $_SESSION
		$_SESSION['login'] = $_POST['name'];
		$_SESSION['id'] = $result->id;
		// On redirige l'utilisateur vers le fichier list.php
		header('location:list.php');
		}
		echo "<script> alert('utilisateur inconnu')</script>";
	}


?>
<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Online Chess</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <h1>Online Chess</h1>
    <hr />

		<div class="content-wrapper">
         <div class="container">
        	<div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line">Login</h4>
            </div>
		</div>
		<div class="row">
			<div class="col-md-10 col-sm-6 col-xs-12 col-md-offset-1">
				<div class="panel panel-info">
				<form name="form" method="post" action="index.php">

					<div class="form-group">
					<label >Votre login</label>

					<h3>Compte de démonstration</h3>
					<p>Login : player1</p>
					<p>Mot de passe : player1</p>

					<p>Login : player2</p>
					<p>Mot de passe : player2</p>
					<hr>
					<input type="text" class="form-control" placeholder="Nom" name="name" required />
					</div>

					<div class="form-group">
					<label>Mot de passe</label>
					<input type="password" class="form-control" placeholder="Mot de passe" name="password" required />
					</div>

					<button type="submit" name='login'  class="btn btn-info">Login</button>
					<!--<a href="signup.php">Je n'ai pas de compte</a>-->
        		</form>
					            
                </div>
             </div>
        </div>
    </div>
</div>


	
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>

