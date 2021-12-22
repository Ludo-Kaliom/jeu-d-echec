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

    if (isset($_GET['del'])) {
		$id = $_GET['del'];
		$sql = "DELETE from chess_game WHERE id=:id";
		$query = $dbh->prepare($sql);
		$query->bindParam(':id', $id, PDO::PARAM_INT);
		$query-> execute();
		
		$sql2 = "DELETE FROM chess_gamedata WHERE game=:id";
		$query2 = $dbh->prepare($sql2);
		$query2->bindParam(':id', $id, PDO::PARAM_INT);
		$query2-> execute();
	}

    $sql = "SELECT * from chess_game";
	$query = $dbh->prepare($sql);
	$query->execute();
	$results = $query->fetchAll(PDO::FETCH_OBJ);
	
	$sql2 = "SELECT id, login from chess_player";
	$query2 = $dbh->prepare($sql2);
	$query2->execute();
	$players = $query2->fetchAll(PDO::FETCH_OBJ);
	$buffer = [];
	$gamesToPrint = [];
	
	if (!empty($players)) {
		foreach($players AS $player) {
			$buffer[$player->id] = $player->login;
		}
	}
	
	if (!empty($results)) {
		foreach($results AS $result) {
			$idWhite = $result->white;
			$idBlack = $result->black;
			$gamesToPrint[$result->id] = [$buffer[$idWhite], $buffer[$idBlack]];
		}
	}

    if (isset($_POST['play'])){
        $player2=$_POST['opponent'];
        $playerColor=$_POST['playerColor'];
        if ($playerColor == 0){
            $sql= "INSERT INTO chess_game (white, black) VALUES (:playerColor, :player2)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':playerColor',$playerid, PDO::PARAM_STR);
            $query->bindParam(':player2', $player2, PDO::PARAM_STR);
            $query->execute();
            $lastInsertId = $dbh->lastInsertId();
            $_SESSION['idgame']=$lastInsertId;
            $_SESSION['white']=$login;

            $sql2 = "SELECT login FROM chess_player WHERE id=:player2";
            $query2 = $dbh->prepare($sql2);
            $query2->bindParam(':player2', $player2, PDO::PARAM_STR);
            $query2->execute();
            $result=$query2->fetch(PDO::FETCH_OBJ);
            $_SESSION['black'] = $result->login;
            header('location:game.php');

        } elseif ($playerColor == 1) {
            $sql= "INSERT INTO chess_game (white, black) VALUES (:player2, :playerColor)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':playerColor', $playerid, PDO::PARAM_STR);
            $query->bindParam(':player2', $player2, PDO::PARAM_STR);
            $query->execute();
            $lastInsertId = $dbh->lastInsertId();
            $_SESSION['idgame']=$lastInsertId;
            $_SESSION['black']=$login;

            $sql2 = "SELECT login FROM chess_player WHERE id=:player2";
            $query2 = $dbh->prepare($sql2);
            $query2->bindParam(':player2', $player2, PDO::PARAM_STR);
            $query2->execute();
            $result=$query2->fetch(PDO::FETCH_OBJ);
            $_SESSION['white'] = $result->login;

            header('location:game.php');
        }
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
                            <select class="form-control" name="playerColor" required>
                                <option value="">Choisir une couleur</option>
                                 <option value="0" >Blanc</option>
                                <option value="1" >Noir</option>
                            </select>
                            </div>

                            <div class="form-group">
                                <label>Votre adversaire</label>
                                <select class="form-control" name="opponent" required>
                                        <option value="">Choisir un adversaire</option>
                                        <?php 
                                        if(count($buffer) > 0) {
                                            foreach($buffer as $id => $opponent) { 
                                                if ($opponent !== $_SESSION['login']) {?>  
                                                <option value="<?php echo $id;?>"><?php echo $opponent;?></option>
                                        <?php 
                                                }
                                            }
                                        } ?> 
                                </select>
              			    </div>


                                <button type="submit" name="play" class="btn btn-info" id="submit">Créer une partie</button>
                            </form>
                            <br><br><br>
                            <table class="table table-striped table-bordered ">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Blanc</th>
                                        <th>Noir</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                    $cnt=1;

                                    foreach($gamesToPrint as $ind => $gameToPrint){ ?>                                      
                                    <tr>
                                        <td class="center"><?php echo $cnt;?></td>
                                        <td class="center"><?php echo $gameToPrint[0];?></td>
                                        <td class="center"><?php echo $gameToPrint[1];?></td>
                                                    
                                    <?php if ($_SESSION['login'] == $gameToPrint[0] || $_SESSION['login'] == $gameToPrint[1]) {?>
                                        <td class="center">
                                            <a href="game.php?white=<?php echo $gameToPrint[0];?>&black=<?php echo $gameToPrint[1];?>&idgame=<?php echo $ind;?>"><button class="btn btn-info">Rejoindre</button></a>
                                            <a href="list.php?del=<?php echo $ind;?>" onclick="return confirm('Etes vous sur de vouloir supprimer cette partie ?');"><button class="btn btn-danger">Supprimer</button></a>
                                        </td>           
                                    <?php } ?>
                                                    
                                    </tr>
                                    <?php $cnt++;
                                        }// Fin foreach
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

