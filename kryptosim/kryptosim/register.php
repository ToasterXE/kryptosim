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

    <div class="kopfzeile">

       
    <div class="test">
        <a href="/index"><button>home</button></a>
    </div>

    </div>
    <div class="main">
    <div class="register">
        <form action="?register=1" method="post" id="registerform">
            <div class="feld">
                <label for="username">username:</label>
                <input required autocomplete="off" type="text" id="username" placeholder="username" name="e">
                <br>
            </div>
            <div class="feld">
                <label for="email">E-mail:</label>
                <input required  autocomplete="off" type="email" id="email" placeholder="email@example.com" name="email">         
                <br>
            </div>
            <div class="feld">
               <label for="password">password:</label>
                <input required class="lightborder" type="password" id="password" placeholder="password" name="password">
                <br>
            </div>
            <div class="feld">
                <label for="password">password wiederholen: </label>
                <input required type="password" id="password2" placeholder="password" name="password2">
                <br>
            </div>
            <button type="submit" name="loginbutton">Register</button>
        </form>
        <br>
    </div>
    </div>
       
       


        <script src="main/e.js"></script>
    </body>
</html>


<?php

if(isset($_GET['register'])) {
    $error = false;
    $email = $_POST['email'];
    $username = $_POST['username'];
    $passwort = $_POST['password'];
    $passwort2 = $_POST['password2'];
  
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 'Bitte eine gültige E-Mail-Adresse eingeben<br>';
        $error = true;
    }     
    if(strlen($passwort) == 0) {
        echo 'Bitte ein Passwort angeben<br>';
        $error = true;
    }
    if($passwort != $passwort2) {
        echo 'Die Passwörter müssen übereinstimmen<br>';
        $error = true;
    }
    
    //Überprüfe, dass die E-Mail-Adresse noch nicht registriert wurde
    if(!$error) { 
        
        $statement = $pdo->prepare("SELECT * FROM benutzer WHERE email = :email");

        $result = $statement->execute(array('email' => $email));
        $user = $statement->fetch();
        
        if($user !== false) {
            echo 'Diese E-Mail-Adresse ist bereits vergeben<br>';
            $error = true;
        }    

        $statement = $pdo->prepare("SELECT * FROM benutzer WHERE first_name = :first_name");
        $result = $statement->execute(array('first_name' => $username));
        $name = $statement->fetch();

        if($name){
            echo 'Dieser Benutzername ist bereits vergeben<br>';
            $error = true;
        }
    }
    
    //Keine Fehler, wir können den Nutzer registrieren
    if(!$error) {    
        $passwort_hash = password_hash($passwort, PASSWORD_DEFAULT);
        
        $statement = $pdo->prepare("INSERT INTO benutzer (email, password, first_name) VALUES (:email, :password, :first_name)");
        $result = $statement->execute(array('email' => $email, 'password' => $passwort_hash, 'first_name' => $username));
        
        if($result) {        
            echo 'Du wurdest erfolgreich registriert.<br> Zum <a href="./index.php">Start</a>';
        } else {
            echo 'Beim Abspeichern ist leider ein Fehler aufgetreten<br>';
        }
    } 
}

?>