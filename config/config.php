<!-- To connect php and mysql -->
<?php 

define('MYSQL_HOST','localhost');

define('MYSQL_DATABASE','blog');

define('MYSQL_USER','root');

define('MYSQL_PASSWORD','');

// PDO in PHP (PHP Data Objects) is a lightweight, consistent framework for accessing databases in PHP. 

// To see error about php and mysql
$pdoOptions = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
);

//connection code
$pdo = new PDO(
    // parameter ၄ ခု
    'mysql:host='.MYSQL_HOST.
    ';dbname='.MYSQL_DATABASE,
    MYSQL_USER,
    MYSQL_PASSWORD,
    $pdoOptions
);
?>