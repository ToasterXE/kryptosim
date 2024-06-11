<?php

if (!isset($_SERVER['HTTPS'])){
    header('Location: https://kryptosim.eu/message');
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
        <a href="/pool"><button>pool</button></a>
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
                <div class="transparent">
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
                <?php
            }
            ?>

            <a href="/user"><button>profile</button></a>

        </div>
       
        <div class="main" id="test">

            <div class="messagefeld">
                <textarea id="message" placeholder="your message" name="message"></textarea>
                <!-- <input type="text" id="message" placeholder="your message"> -->
                <input type="text" id="key" placeholder="your key (1)" onpaste="fixinput(event)">
                <input type="text" id="space" placeholder="your key (2)">
            </div>

            <div class = "edbuttons">
                <button onclick=encryptmessage()>encrypt</button>
                <button onclick=decryptmessage()>decrypt</button>
            </div>

            <div class="messagefeld">
            <button onclick="copy('result')">copy</button>
                <div class="result">
                    <p id="result">
                        result
                    </p>
                </div>
            </div>

            <h2>send message to pool</h2>
          <form class="sendmsg" action="?post=1" method="post">
                <div class="messagefeld">
                    <textarea id="message" placeholder="your message" name="message"></textarea>
                    <input type="text" id="sender" name="sender" class="smaller" placeholder="sender public key">
                    <input type="text" id="receiver" name="receiver" class="smaller" placeholder="reciever public key">
                    <label class="checkboxlabel">signed
                        <input type="checkbox" id="signed" name="signed">
                    </label>
                    <button type="submit">post</button>
                </div>
                
            </form>
            

        </div>


    </body>


<?php

if(isset($_GET['post'])){
    $error = 0;
    if(!$_POST['message']){
        $error = 1;
    }
    $chars = str_split($_POST['message']);
    foreach ($chars as $c) {
        if((ord($c)-48)>=10){
            $error = 1;
        }
    }
    if($error){
        echo("plain text cannot be sent");
    }
    else{
        $signed = $_POST['signed'];
        if($signed== "on" && $_POST['sender']){
            $signed = 1;
        }
        else{
            $signed = 0;
        }
        $statement = $pdo->prepare("INSERT INTO messages (sender, receiver, text, signed) VALUES (:sender, :receiver, :text, :signed)");
        $result = $statement->execute(array('sender' => $_POST['sender'], 'receiver' => $_POST['receiver'], 'text' => $_POST['message'], 'signed' => $signed));
        if($result){
            echo("message sent to pool!");
        }
    }
}

?>
</html>
  