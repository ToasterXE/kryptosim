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

$search = isset($_GET['search']) ? $_GET['search'] : '';
if(isset($_POST['usersearch'])){
    $search = ($_POST['usersearch']);
}
$search = trim($search);
$sortby = isset($_GET['sort']) ? $_GET['sort'] : 0;
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
        <script src="main/page.js"></script>

        <title>Kryptosim</title>
        <?php if($sortby == 1){ ?>
            
            <h1>Message Pool</h1>
        <?php
        }
        else{?>
        <h1>Transaction Pool</h1>
        <?php
        }?>
    </head>
    <body onload="init(); page(0, 5, 'message')">

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
            <form method="post" style="all: unset" action="?sort=<?php print(($sortby) ? 0 : 1 ); if(!empty($search)){?>&search=<?php print($search);}?>">

                <button type="submit" name="transactionsonly" id="transactionsonly">
                <?php if(isset($_POST['transactionsonly'])){
                    if($sortby == 1){
                        ?>
                        show messages
                        <?php
                    }
                    else if($sortby == 0){
                        ?>
                        show transactions
                        <?php
                    }
                }
                else{
                    ?>
                    show transactions
                    <?php
                }
                ?>    
                </button>
            </form>

                    <p>search:</p>
                    <form method="post" action="?<?php if(!empty($search)){?>search=<?php print($search);}?>">
                        <input class="lightborder" type="text" name="usersearch" placeholder="sender/reciever key..."></input>
                    </form>
                    <?php
                    if($search != ""){
                        // $search = ($_POST['usersearch']);
                        ?><p>searching for: <span class="emph"><?php print($search) ?></span></p>
                        <?php
                    }
                    ?>


                </div>
            </div>
            
        <div class="main">
            <?php
                if(isset($_POST['verify'])){
                    $requestid = $_GET['request'];
                    ?><p>sending verification data of transaction #<?php echo($requestid)?> to nodes...</p> <?php
                    
                    $statement = $pdo->prepare("SELECT * FROM nodes WHERE last_seen >= NOW() - INTERVAL 10 SECOND ORDER BY id");
                    $result = $statement->execute();
                    $node = $statement->fetch();
                    if($node){
                        $newid = $node['id'];
                        $statement = $pdo->prepare("UPDATE nodes SET transaction_id = $requestid WHERE id = $newid");
                        $result = $statement->execute();
                        $set = true;
                    }
                    if(!$set){
                        ?><p>could not find any available nodes. Try again in a minute.</p><?php
                    }
                }
            ?>
        
            <?php
                $statement = $pdo->prepare("SELECT * FROM messages WHERE
                                            sender LIKE '%{$search}%' OR
                                            receiver LIKE '%{$search}%' 
                                            ORDER BY date DESC");
                $result = $statement->execute(array());
                $count = 0;
                $block_wait =  [];
                while($messages = $statement->fetch()){
                    if($messages['transaktion'] != $sortby){continue;}
                    $count++;
                    ?>
                    
                    <div class="message <?php if($count%2){ print("tablelight");} if($messages['blockid']!=0){print(" inblock");} if($messages['transaktion']){ print(" transaction");}?>">
                        <?php
                            if($sortby){
                                ?>
                                <p class="<?php print($messages['valid'] ? "verified" : "")?>" style="margin: 0px;">
                                    <?php 
                                    echo("status: ".($messages['valid'] ? "verified" : "waiting for verification"));
                                    ?>
                                </p>   
                                <p style="margin: 0px;">
                                    <?php 
                                    echo("block: ".($messages['block_id'] ? $messages['block_id'] : "waiting for block"));
                                    ?>
                                </p>                      
                                <?php
                                if($messages['valid'] && $messages['block_id'] == 0){
                                    $block_wait[] = $messages['id'];
                                }
                            }                        
                        ?>
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
                            <?php if($sortby){ ?>
                            <form method="post" style="unset:all" action="?sort=<?php print($sortby); if(!empty($search)){?>&search=<?php print($search);}?>&request=<?php print($messages['id'])?>">
                                <button type="submit" name="verify" id="verify">verify</button>
                            </form>
                            <?php } ?>
                        </div>
                    </div>
                    <?php
                }
                ?><div class="miniform"><p><?php
                echo("Transactions waiting for block: ".count($block_wait));?></p><?php
                if(count($block_wait)>= 3){
                    ?>  
                    <form  method="post" action="block.php?t1=<?php print($block_wait[0])?>&t2=<?php print($block_wait[1])?>&t3=<?php print($block_wait[2])?>">
                        <button type="submit" >create new block</button>
                    </form>
                    <?php
                }
                ?></div>
            
            <div style="width: 100%;">
                <p id="pagenum">Page 0 of 0</p>
                <button id="prev" onclick="page(-1, 5, 'message')">&lt;&lt;previous page</button>
                <button id="next" onclick="page(1, 5, 'message')">next page>></button>
            </div>
        </div>

    </body>
</html>