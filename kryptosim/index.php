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
        <script src="main/e.js"></script>

        <title>Kryptosim</title>
        <h1>Willkommen bei Kryptosim!</h1>
    </head>
    <body>

    <div class="kopfzeile">

       
        <div class="test">
            <a href="/index"><button>home</button></a>
           </div>

        <div>
            <a href="/blockchain"><button>blockchain</button></a>
        </div>
        <div class="register">
            <a href="/register"><button>register</button></a>
        </div>

        <div class="login">
        <button onclick="showlogin()">login</button>
            <div id="dtl" class="dropdownlogin">
                <form action="?login=1" method="post" id="loginform">
                    <label for="email">E-mail</label>
                    <br>
                    <input type="email" id="email" placeholder="karlos@großratte.de" name="email">         
                    <br>
                    
                    <label for="password">password</label>
                    <br>
                    <input type="password" id="password" placeholder="dootlord01" name="password">
                    <br>
                    
                    <button type="submit" name="loginbutton">login</button>
                </form>
            </div>
       </div>

    </div>   
       
       <p>eeeeeeeeeeeeeeaeea</p>
       
    </body>
</html>


<?php

if(isset($_GET['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $statement = $pdo->prepare("SELECT * FROM benutzer WHERE email = :email");
    $result = $statement->execute(array('email' => $email));
    $user = $statement->fetch();    
                
    if($user && password_verify($password, $user['password'])) {
        $_SESSION['userid'] = $user['id'];
        die('Erfolgreich angemeldet');
    }    
    else{
        echo("Unbekannte Benutzerdaten");
    }
    }    

?>