<!-- untwichtig -->
<?php

session_start();
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
  <title>Registrierung</title>    
</head> 
<body>

<?php
$showFormular = true; //Variable ob das Registrierungsformular anezeigt werden soll
 
if(isset($_GET['register'])) {
    $error = false;
    $email = $_POST['email'];
    $passwort = $_POST['passwort'];
    $passwort2 = $_POST['passwort2'];
  
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
        echo "tewst";
        if($user !== false) {
            echo 'Diese E-Mail-Adresse ist bereits vergeben<br>';
            $error = true;
        }    
    }
    
    //Keine Fehler, wir können den Nutzer registrieren
    if(!$error) {    
        $passwort_hash = password_hash($passwort, PASSWORD_DEFAULT);
        
        $statement = $pdo->prepare("INSERT INTO benutzer (email, password) VALUES (:email, :password)");
        $result = $statement->execute(array('email' => $email, 'password' => $passwort_hash));
        
        if($result) {        
            echo 'Du wurdest erfolgreich registriert. <a href="login.php">Zum Login</a>';
            // $showFormular = false;
        } else {
            echo 'Beim Abspeichern ist leider ein Fehler aufgetreten<br>';
        }
    } 
}

if($showFormular) {
    ?>
     
    <form action="?register=1" method="post">
    E-Mail:<br>
    <input type="email" size="40" maxlength="250" name="email"><br><br>
     
    Dein Passwort:<br>
    <input type="password" size="40"  maxlength="250" name="passwort"><br>
     
    Passwort wiederholen:<br>
    <input type="password" size="40" maxlength="250" name="passwort2"><br><br>
     
    <input type="submit" value="Abschicken">
    </form>
     
    <?php
    } //Ende von if($showFormular)
    ?>
     
    </body>
    </html>
