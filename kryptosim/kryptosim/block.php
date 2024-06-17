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
        <link rel="stylesheet" href="./main/style-block.css">
        <link rel="icon" type="image/x-icon" href="/main/favicon.ico">
        <script src="main/e.js"></script>
        <script src="main/encrypt.js"></script>
        <script src="main/generatemessage.js"></script>
        <title>new block - kryptosim</title>
        <h1>new block</h1>
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
       
        <div class="main" id="test" style="flex-wrap: wrap;">
            <?php
                if(isset($_GET['send'])){
                    $header = $_POST['header'];
                    $t1 = $_POST['t1'];
                    $t2 = $_POST['t2'];
                    $t3 = $_POST['t3'];
                    $t1json = json_decode($t1);
                    $t1_id = $t1json->id;
                    $t2json = json_decode($t2);
                    $t2_id = $t2json->id;
                    $t3json = json_decode($t3);
                    $t3_id = $t3json->id;
                    $miner = $_POST['miner'];
                    $reward = $_POST['reward'];
                    $rewardnum = explode(" ",$reward)[0];
                    $pow = trim($_POST['POW']);
                    $string = $header.$t1.$t2.$t3.'{"receiver":"'.$miner.'","text":"'.$reward.'"}';
                    $string = preg_replace('/\s+/', '', $string);
                    $pstring = $string.$pow;
                    $pstring = preg_replace('/\s+/', '', $pstring);
                    $hash = hash('sha256', $pstring);
                    $statement = $pdo->prepare("INSERT INTO blocks (header, t1_id, t2_id, t3_id, miner, reward, pow, hash) VALUES(:header, $t1_id, $t2_id, $t3_id, :miner, $rewardnum, $pow, :hash)");
                    $result = $statement->execute(array('header' => $header, 'miner' => $miner, 'hash' => $hash));
                    $blockid = $pdo->lastInsertId();


                    $statement = $pdo->prepare("SELECT * FROM nodes WHERE id > $id and last_seen >= NOW() - INTERVAL 10 SECOND  and transaction_id = 0");
                    $result = $statement->execute();
                    $newnode = $statement->fetch();
                    if($newnode){
                        $newid = $newnode['id'];
                        $statement = $pdo->prepare("UPDATE nodes SET block_id = $blockid WHERE id = $newid");
                        $result = $statement->execute();
                        $set = true;
                        echo("block #".$blockid." sent to network");
                    }
                    else{
                        echo("could not find any available nodes. Try again in a minute.");
                    }
                    //für nach verify
                    // $statement = $pdo->prepare("UPDATE messages WHERE id IN ($t1_id, $t2_id, $t3_od) SET block_id = $blockid");
                    // $statement->execute();

                    // if($result){
                    //     echo("e");
                    // }
                }
            ?>


            <form class="block" method="post" action="?send=1">
                <div class="header">
                    <input required type="textarea" name="header" id="prevhash" placeholder="header" value="<?php print(getlastid($pdo))?>">
                </div>
                <div class="transactions">
                    <textarea required readonly type="textarea" id="t1" name="t1" placeholder="t1"><?php print((isset($_GET['t1'])) ? trim(getjason($_GET['t1'], $pdo)) : "")?></textarea>
                    <textarea required readonly type="textarea" id="t2" name="t2" placeholder="t2"><?php print((isset($_GET['t2'])) ? trim(getjason($_GET['t2'], $pdo)) : "")?></textarea>
                    <textarea required readonly type="textarea" id="t3" name="t3" placeholder="t3"><?php print((isset($_GET['t3'])) ? trim(getjason($_GET['t3'], $pdo)) : "")?></textarea>
                    <textarea required type="textarea" name="miner" id="miner" placeholder="miner"></textarea>
                    <textarea required readonly type="textarea" name="reward" id="reward" placeholder="reward"></textarea>
                    <button type="button" onclick="getrewardtext()">generate reward text</button>
                    <button type="button" onclick="copyhashtext()">copy hash text</button>
                    <p id="error"></p>
                </div>
                <div class="header">
                    <input required type="textarea" name="POW" placeholder="POW">
                </div>
                <button type="submit">send block to nodes</button>
            </form>
           
        </div>



    <?php
    function getjason($id, &$pdo){
        $statement = $pdo->prepare("SELECT id, sender, receiver, text, date FROM messages WHERE id = $id");
        $result = $statement->execute();
        $data = $statement->fetch(PDO::FETCH_ASSOC);
        $json = json_encode(($data),JSON_PRETTY_PRINT);
        return $json;
    }

    function getlastid(&$pdo){
        $statement = $pdo->prepare("SELECT hash FROM blocks ORDER BY id DESC");
        $result = $statement->execute();
        $hash = $statement->fetch();
        // echo($hash['hash']);
        return($hash['hash']);
    }
?>
    </body>

</html>
