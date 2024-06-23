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
        <link rel="stylesheet" href="main/block.css">
        <link rel="icon" type="image/x-icon" href="/main/favicon.ico">
        <script src="main/e.js"></script>
        <script src="main/block.js"></script>

        <title>Kryptosim</title>
        <h1>Blockchain</h1>
    </head>
    <body>

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
    <div class="main">   
        <div class="blockchain">
                <?php
                    $statement = $pdo->prepare("SELECT * FROM blocks ORDER BY id DESC");
                    $statement->execute();
                    while($block = $statement->fetch(PDO::FETCH_ASSOC)){
                        $id = $block["id"];
                        $hash = $block
                        ?>
                        <div class='block'>
                            <div class="header">
                                <div id="header" class="headerid">
                                <?php print($block['header']); ?>
                                </div>
                            </div>    
                            <div class="liste">
                                <?php
                                if($block['t1_id'] != 0){?>
                                    <textarea readonly name="t1" class="t" id="t1"><?php print(trim(getjason($block['t1_id'], $pdo)))?></textarea>
                                    <textarea readonly name="t2" class="t" id="t2"><?php print(trim(getjason($block['t2_id'], $pdo)))?></textarea>
                                    <textarea readonly name="t3" class="t" id="t3"><?php print(trim(getjason($block['t3_id'], $pdo)))?></textarea>
                                <?php } ?>
                                <textarea readonly name="reward" class="t" id="reward"><?php print(json_encode(["receiver" => $block['miner'],"text" => $block['rewardtext']], JSON_PRETTY_PRINT))?></textarea>
                            </div>         
                            <div class="header pow">
                                <?php
                                    print($block['pow']);
                                ?>
                            </div>
                            <div class="header">
                            <div class="hash" id="hash">
                                <?php
                                    print($block['hash']);
                                ?>
                            </div>
                            </div>
                            
                            </div>                    
                        <?php
                        }

                        //test

                        // $transactionids = [];
                        // $deltabalance = [];
                        // $statement = $pdo->prepare("SELECT t1_id, t2_id, t3_id FROM blocks WHERE valid = 1");
                        // $statement->execute();
                        // while($values = $statement->fetch(PDO::FETCH_NUM)){
                        //     foreach($values as $v){
                        //         array_push($transactionids, $v);
                        //     }
                        // }
                        // $where_in = implode(',', $transactionids);
                        // $statement = $pdo->prepare("SELECT sender, receiver, sum FROM messages WHERE id IN ($where_in)");
                        // $statement->execute();
                        // while($action = $statement->fetch(PDO::FETCH_ASSOC)){
                        //     updatebalance($action['sum'],$action['receiver']);
                        //     updatebalance(-$action['sum'],$action['sender']);
                        // }
                        // $statement = $pdo->prepare("SELECT reward, miner FROM blocks WHERE valid = 1");
                        // $statement->execute();
                        // while($reward = $statement->fetch()){
                        //     updatebalance($reward['reward'], $reward['miner']);
                        // }

                        // foreach(array_keys($deltabalance) as $key){
                        //     $keys = explode(" ",$key);
                        //     $statement = $pdo->prepare("UPDATE benutzer SET balance = $deltabalance[$key] WHERE public_key = $keys[0] AND key_n = $keys[1]");
                        //     $statement->execute();
                        // }

                    ?>
        </div>
            <button onclick="verify_blocks()">verify</button>
            <!-- <button onclick="update_blocks()">update</button> -->
    </div>
       
    </body>
</html>
<?php
    function getjason($id, &$pdo){
        $statement = $pdo->prepare("SELECT id, sender, receiver, text, date FROM messages WHERE id = $id");
        $result = $statement->execute();
        $data = $statement->fetch(PDO::FETCH_ASSOC);
        $json = json_encode(($data),JSON_PRETTY_PRINT);
        return $json;
    }

    function updatebalance($sum, $key){
        global $deltabalance;
        if(array_key_exists($key, $deltabalance)){
            $deltabalance[$key] += $sum;
        }
        else{
            $deltabalance[$key] = $sum;
        }
        
    }

?>
