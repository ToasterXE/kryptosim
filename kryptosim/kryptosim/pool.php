<?php

if (!isset($_SERVER['HTTPS'])){
    header('Location: https://kryptosim.eu/pool');
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
        <link rel="stylesheet" href="./main/style-pool.css">
        <!-- <link rel="stylesheet" href="./main/style-message.css"> -->
        <link rel="icon" type="image/x-icon" href="/main/favicon.ico">
        <script src="main/e.js"></script>
        <script src="main/encrypt.js"></script>

        <title>Kryptosim</title>
        <h1>Message Pool</h1>
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
       
            <div class="search" style="width: 100%;">
            
                <div class="forms">
                    <p>search:</p>
                    <form method="post" style="all: unset" action="?order=<?php print($order); ?>&sort=<?php print($sortby); if(!empty($search)){?>&search=<?php print($search);}?>">
                        <input class="lightborder" type="text" name="usersearch" placeholder="name, id, key..."></input>
                    </form>
                    <?php
                    if($search != ""){
                        // $search = ($_POST['usersearch']);
                        ?><p>searching for: </p><p style="margin-left: 0px;" class="highlighted"><?php print($search) ?></p>
                        <?php
                    }
                    ?>


                </div>
            </div>
            
        <div class="main">

        
            <?php
                $statement = $pdo->prepare("SELECT * FROM messages ORDER BY date DESC");
                $result = $statement->execute(array());
                $count = 0;
                while($messages = $statement->fetch()){
                    $count++;
                    ?>
                    <div class="message <?php if($count%2){ print("tablelight"); }?>">
                        <div class="information">
                            <p style="width: 30%;"><span class="emph">sender:</span>
                                <?php
                                    if($messages['sender'] == 0){ ?><span class="emph"><?php echo("hidden");?></span><?php }
                                    else{
                                        echo($messages['sender']);  
                                        if($messages['signed']){
                                            ?><span class="emph"> <?php echo("signed") ?> </span> <?php
                                        }
                                    }
                                ?>
                            </p>
                            <p style="width: 50%;"><span class="emph">receiver:</span>
                                <?php
                                    if($messages['receiver'] == 0){ ?><span class="emph"><?php echo("hidden");?></span><?php }
                                    else{echo($messages['receiver']);};  
                                ?>
                            </p>
                            <p style="width: 20%;"><span class="emph">date:</span>
                                <?php
                                    echo($messages['date']);  
                                ?>
                            </p>
                        </div>
                        <div class="text<?php print($count); ?>">
                            <textarea readonly class="text" id="text<?php print($count); ?>">
                                <?php
                                    echo($messages['text']);
                                ?>
                            </textarea>
                        </div>
                        <div>
                            <textarea readonly class="hidden" id="result<?php print($count); ?>">
                            </textarea>
                        </div>
                        <div class = "options">
                            <input type="text" id="key<?php print($count); ?>" placeholder="key (1)" onpaste="fixinput(event, 'key<?php print($count); ?>', 'space<?php print($count); ?>')">
                            <input type="text" id="space<?php print($count); ?>" placeholder="key (2)">
                            <button onclick="decryptmessage('text<?php print($count); ?>', 'key<?php print($count); ?>', 'space<?php print($count); ?>', 'result<?php print($count); ?>')">decrypt</button>
                        </div>
                    </div>
                    <?php
                }
            ?>

        </div>


    </body>
</html>