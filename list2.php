<?php
include("database.php");

session_start();

if(strlen($_SESSION['login'])==0) {
	header('location:index.php');
} else {
    $login=$_SESSION['login'];
    $playerid=$_SESSION['id'];
    var_dump($_SESSION);

    $dbh = new DataBase();
    $sql = "SELECT * FROM chess_player WHERE login NOT IN(:login)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':login', $login, PDO::PARAM_STR);
    $query->execute();
    $results=$query->fetchAll(PDO::FETCH_OBJ);

    $sql1 = "SELECT * FROM chess_game";
    $query1 = $dbh->prepare($sql1);
    $query1->execute();
    $results1=$query1->fetchAll(PDO::FETCH_OBJ);

    if(isset($_GET['del'])) {
        $delete = $_GET["del"];
        $del = "DELETE FROM chess_game WHERE id=:delete";
        $query = $dbh->prepare($del);
        $query->bindParam(':delete', $delete, PDO::PARAM_INT);
        // On execute la requete
        $query->execute();
        // On redirige l'utilisateur vers la page manage-books.php
        header('location:list.php');
}

    if (isset($_POST['play'])){
        var_dump($_POST);
        $playerColor=$_POST['playerColor'];
        $player2=$_POST['player2'];

        $sql= "INSERT INTO chess_game (white, black)
                VALUES (:playerColor, :player2)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':playerColor', $playerColor, PDO::PARAM_STR);
        $query->bindParam(':player2', $player2, PDO::PARAM_STR);
        $query->execute();
        $lastInsertId = $dbh->lastInsertId();
        $_SESSION['idgame']=$lastInsertId;
		
		
        $sql2 = "SELECT login FROM chess_player WHERE id=:playerColor";
        $query2 = $dbh->prepare($sql2);
        $query2->bindParam(':playerColor', $playerColor, PDO::PARAM_STR);
        $query2->execute();
        $white=$query2->fetch(PDO::FETCH_OBJ);
        $_SESSION['white'] = $white->login;

        $sql3 = "SELECT login FROM chess_player WHERE id=:player2";
        $query3 = $dbh->prepare($sql3);
        $query3->bindParam(':player2', $player2, PDO::PARAM_STR);
        $query3->execute();
        $black=$query3->fetch(PDO::FETCH_OBJ);
        $_SESSION['black'] = $black->login;

        
        $_SESSION['login'] = $login;
        $_SESSION['Id'] = $playerid;
        header('location:game.php');

    }
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

<div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Online Chess</h4>
                    <p>Vous êtes connecté en tant que <?php echo $login ?></p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-9 offset-md-1">
                    <div class="panel panel-danger">
                        <div class="panel-body">
                            <form name="play" method="post" action="list">

                            <label>Votre couleur</span></label>
                            <select name="playerColor" required>
                                <option value="">Choisir une couleur</option>
                                 <option value="<?php echo $playerid ?>" >Blanc</option>
                                <option value="<?php echo $playerid ?>" >Noir</option>
                            </select>
                            </div>

                            <label>Adversaire</label>
                            <select name="player2" required>
                                <option value="">Choisir un adversaire</option>

                                <?php 
                                if (count($results) > 0) {
                                    foreach($results AS $result) {
                                ?>
                                <option value="<?php echo $result->id?>"> <?php echo $result->login?></option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                            </div>

                          
                                <button type="submit" name="play" class="btn btn-info" id="submit">Créer une partie</button>
                            </form>


                            <table class="table table-striped table-bordered text-center">
                        <thead class="thead-dark">
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">Blanc</th>
                            <th scope="col">Noir</th>
                            <th scope="col">Action</th>
                            </tr>
                        </thead>      
                        <tbody>
                            <?php 
                                if (is_array($results1)){
                                    $cnt= 1;
                                    foreach ($results1 AS $result1){
                                        $sqla = "SELECT login FROM chess_player WHERE id=".$result1->white;
                                        $sqlb = "SELECT login FROM chess_player WHERE id=".$result1->black;
            
                                        $querya = $dbh->prepare ($sqla);
                                        $querya->execute();
                                        $resulta = $querya->fetch(PDO::FETCH_OBJ);
            
                                        $queryb = $dbh->prepare ($sqlb);
                                        $queryb->execute();
                                        $resultb = $queryb->fetch(PDO::FETCH_OBJ);
                                    ?>
                                    <tr>
                                    <td><?php echo $cnt ?> </td>
                                    <td><?php echo $resulta->login; ?></td>
                                    <td><?php echo $resultb->login;?></td>
                                    <td> <?php if ($resulta->login == $login){?>
                                        <a href="game.php?id=<?php echo $result1->id ?>">
                                        <button class="btn btn-primary">Rejoindre</button>
                                        <a href="list.php?del=<?php echo $result1->id ?>" onClick="return confirm('Etes-vous sûr ?')">
                                        <button class="btn btn-danger">Supprimer</button>
                                    </a>
                                    <?php } else if ($resultb->login == $login){ ?>
                                        <a href="game.php?id=<?php echo $result1->id ?>">
                                        <button class="btn btn-primary">Rejoindre</button>
                                        <a href="list.php?del=<?php echo $result1->id ?>" onClick="return confirm('Etes-vous sûr ?')">
                                        <button class="btn btn-danger">Supprimer</button>
                                    <?php } else {

                                    }
                                    
                                    ?>
                                    </td>
                                    </tr>
                                    <?php
                                    $cnt++; 
                                    }
                                }
                            ?>   
                            </tbody> 
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    
 
    <!-- CORE JQUERY  -->
      <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>

