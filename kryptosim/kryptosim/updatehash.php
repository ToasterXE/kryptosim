<?php
session_start();
ini_set('display_errors', 1);
$host_name = 'db5014852654.hosting-data.io';
$database = 'dbs12339433';
$name = 'dbu1139207';
$pass = '^h6!-vJAmpQ_Cpg';

try{
$pdo = new PDO('mysql:host=db5014852654.hosting-data.io;dbname=dbs12339433', $name, $pass);
} catch (PDOException $e){
    echo $e;
}
?>


