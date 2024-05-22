<?php

if (!isset($_SERVER['HTTPS'])){
    header('Location: https://kryptosim.eu');
 // page is called from https
 // Connection is secured
}


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
        <link rel="stylesheet" href="./main/style-main.css">
        <link rel="stylesheet" href="./main/register.css">
        <link rel="stylesheet" href="./main/style-message.css">
        <link rel="icon" type="image/x-icon" href="/main/favicon.ico">
        <script src="main/e.js"></script>
        <script src="main/encrypt.js"></script>

        <title>Kryptosim</title>
        <h1>encrypt/decrypt message</h1>
    </head>
    <body onload="init()">

    <div class="kopfzeile">

       
        <a href="/index"><button>home</button></a>
        <a href="/blockchain"><button>blockchain</button></a>
        <a href="/message"><button>encrypt/decrypt</button></a>
        <a href="/register"><button>register</button></a>


        <?php
            if(isset($_GET['login'])) {
                $email = $_POST['email'];
                $password = $_POST['password'];

                $statement = $pdo->prepare("SELECT * FROM benutzer WHERE email = :email");
                $result = $statement->execute(array('email' => $email));
                $user = $statement->fetch();    
                
                if($user && password_verify($password, $user['password'])) {
                    $_SESSION['userid'] = $user['id'];
                }    
                else{
                    ?>
                    <div class="alert">
                        unbekannte benutzerdaten
                    </div>                
                    <?php
                }
            }    

            if($_SESSION['userid'] != ""){
                ?>
                <a href="logout.php"><button>log out</button></a>        
                <?php        
            }
            
            else{
                ?>
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
                <?php
            }
            ?>

            <a href="/user"><button>profile</button></a>

        </div>
       
        <div class="main" id="test">

            <div class="messagefeld">
                <input type="text" id="message" placeholder="your message">
                <input type="text" id="key" placeholder="your key (1)">
                <input type="text" id="space" placeholder="your key (2)">
            </div>

            <div class = "edbuttons">
                <button onclick=encryptmessage()>encrypt</button>
                <button onclick=decryptmessage()>decrypt</button>
            </div>

            <div class="messagefeld">
                <div class="result">
                    <p id="result">
                        result
                    </p>
                </div>
            </div>

        </div>


    </body>
</html>