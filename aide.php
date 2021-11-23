<?

// On construit la requete qui permet de retrouver l'utilisateur
	$sql = new DataBase('blog');
	$datas = $db->query('SELECT * FROM articles');
	var_dump($datas);

	$post = $db->prepare('SELECT * FROM article WHERE id= ?', [$_GET['id']], 'Database', true);

	$sql = new DataBase();
	$test = $db->prepare("SELECT * FROM player WHERE login=:name AND password=:password", [$_GET['id']], 'Database', true);

	$sql = "SELECT * FROM player WHERE login=:name AND password=:password";
	$query = $dbh->prepare($sql);

	$sql = new DataBase();
	$datas = query("SELECT * FROM player WHERE login=:name AND password=:password");

	$test = $sql->prepare("SELECT * FROM player WHERE login=:name AND password=:password", '$test' , 'Database', true);
	$test->bindParam(':name', $name, PDO::PARAM_STR);
	$test->bindParam(':password', $password, PDO::PARAM_STR);
	$result = $datas;


    ###### config.ini ######
db_driver=mysql
db_user=root
db_password=924892xp

[dsn]
host=localhost
port=3306
dbname=localhost

[db_options]
PDO::MYSQL_ATTR_INIT_COMMAND=set names utf8

[db_attributes]
ATTR_ERRMODE=ERRMODE_EXCEPTION
############

<?php class Database {
    private static $link = null ;

    private static function getLink ( ) {
        if ( self :: $link ) {
            return self :: $link ;
        }

        $ini = _BASE_DIR . "config.ini" ;
        $parse = parse_ini_file ( $ini , true ) ;

        $driver = $parse [ "db_driver" ] ;
        $dsn = "${driver}:" ;
        $user = $parse [ "db_user" ] ;
        $password = $parse [ "db_password" ] ;
        $options = $parse [ "db_options" ] ;
        $attributes = $parse [ "db_attributes" ] ;

        foreach ( $parse [ "dsn" ] as $k => $v ) {
            $dsn .= "${k}=${v};" ;
        }

        self :: $link = new PDO ( $dsn, $user, $password, $options ) ;

        foreach ( $attributes as $k => $v ) {
            self :: $link -> setAttribute ( constant ( "PDO::{$k}" )
                , constant ( "PDO::{$v}" ) ) ;
        }

        return self :: $link ;
    }

    public static function __callStatic ( $name, $args ) {
        $callback = array ( self :: getLink ( ), $name ) ;
        return call_user_func_array ( $callback , $args ) ;
    }
} ?>

<?php // examples
$stmt = Database :: prepare ( "SELECT 'something' ;" ) ;
$stmt -> execute ( ) ;
var_dump ( $stmt -> fetchAll ( ) ) ;
$stmt -> closeCursor ( ) ;
?>