<?php
session_start();
ini_set('display_errors', 1);
$host_name = 'db5014852654.hosting-data.io';
$database = 'dbs12339433';
$user_name = 'dbu1139207';
$password = '^h6!-vJAmpQ_Cpg';

try{
$pdo = new PDO('mysql:host=db5014852654.hosting-data.io;dbname=dbs12339433', $user_name, $password);
} catch (PDOException $e){
    echo "e";
}
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="main/style-main.css">
        <link rel="stylesheet" href="main/register.css">
        <link rel="icon" type="image/x-icon" href="/main/favicon.ico">
        <title>Kryptosim</title>
        <h1>Neuen Account Registrieren</h1>
    </head>
    <body>
    <?php
    if(isset($_POST['test'])){
        ?> <p id="responsetonode">
        <?php
        $statement = $pdo->prepare("INSERT INTO nodes (last_seen) VALUES (CURRENT_TIMESTAMP)");
        $result = $statement->execute();
        $r = $pdo->lastInsertId();
        echo($r);
        ?>
        </p>
        <p id="blockchaindata">
        <?php
        $statement = $pdo->prepare("SELECT blockchaindata FROM nodes WHERE blockchaindata != '' ORDER BY last_seen DESC");
        $result = $statement->execute();
        $e = $statement->fetch();
        echo($e[0]);
    };
    ?>
    <form action="?registernode">
        <input type="text" id="test" name="test" placeholder="e">
    </form>

</body>
</html>
