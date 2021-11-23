<?php
include("database.php");


// On inclue le fichier de configuration et de connexion à la base de données
$dbh = new DataBase();
if(!empty($_GET["login"])){
    // On récupère dans $_GET le login soumis par l'utilisateur
	$login = $_GET["login"];
    // On prépare la requete qui recherche la présence du login dans la table player
	$sql = "SELECT login FROM player WHERE login=:login";
	$query = $dbh->prepare($sql);
	$query->bindParam(':login', $login, PDO::PARAM_STR);
	$query->execute();
	$result = $query->fetch(PDO::FETCH_OBJ);
    if(!empty($result)){
        echo "<span style='color:red'>Ce login existe déjà</span>";
    }else{
        // Sinon on signale a l'utlisateur que le login est disponible et on active le bouton du formulaire
        echo "<span style='color:red'>Ce login est disponible</span>";
    }
}

?>
