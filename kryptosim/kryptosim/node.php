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
    if(isset($_POST['nodeid'])){
        $id = $_POST['nodeid'];
        $statement = $pdo->prepare("UPDATE nodes SET last_seen = CURRENT_TIMESTAMP WHERE id = $id");
        $result = $statement->execute();
        $statement = $pdo->prepare("SELECT * FROM nodes WHERE id = :id");
        $result = $statement->execute(array('id'=>$id));
        $node = $statement->fetch();
        if($node['transaction_id'] != 0){
            $trans_id = $node['transaction_id'];

            if(isset($_POST['verify'])){
                $statement = $pdo->prepare("UPDATE nodes SET transaction_id = 0 WHERE id = $id");
                $result = $statement->execute();
                if($_POST['verify']){
                    ?>
                    <p id="transaction_id"> <?php echo(sprintf("answer to node #{$id}: success"))?>
                    <?php
                    $set = false;
                    $statement = $pdo->prepare("SELECT * FROM nodes WHERE id > $id and last_seen >= NOW() - INTERVAL 10 SECOND  and transaction_id = 0");
                    $result = $statement->execute();
                    $newnode = $statement->fetch();
                    if($newnode){
                        $newid = $newnode['id'];
                        $statement = $pdo->prepare("UPDATE nodes SET transaction_id = $trans_id WHERE id = $newid");
                        $result = $statement->execute();
                        $set = true;
                        echo("sending transaction to next node");
                    }
                    if(!$set and $trans_id != 33){
                        $statement = $pdo->prepare("UPDATE messages SET valid = 1 WHERE id = $trans_id");
                        $result = $statement->execute();
                        echo("transaction verified by network");
                    }
                    ?></p><?php
                }
            }

            else{
                $statement = $pdo->prepare("SELECT * FROM messages WHERE id = $trans_id");
                $result = $statement->execute();
                $transaction = $statement->fetch(PDO::FETCH_ASSOC);
                $json = json_encode($transaction);

                ?>
                <p id="transaction_id"> <?php echo($json)?></p>
                <?php
            }
        }

        if($node['block_id'] != 0){
            $blockid = $node['block_id'];
            if(isset($_POST['verifyblock'])){
                $statement = $pdo->prepare("UPDATE nodes SET block_id = 0 WHERE id = $id");
                $result = $statement->execute();
                if($_POST['verifyblock']){
                    ?><p id="block_id"><?php
                    $set = false;
                    $statement = $pdo->prepare("SELECT * FROM nodes WHERE id > $id and last_seen >= NOW() - INTERVAL 10 SECOND  and block_id = 0");
                    $result = $statement->execute();
                    $newnode = $statement->fetch();
                    if($newnode){
                        $newid = $newnode['id'];
                        $statement = $pdo->prepare("UPDATE nodes SET block_id = $blockid WHERE id = $newid");
                        $result = $statement->execute();
                        $set = true;
                        echo("answer to node #{$id}: sending block to next node");
                    }
                    if(!$set){
                        echo("answer to node #{$id}: block verified!");
                        $statement = $pdo->prepare("UPDATE blocks SET valid = 1 WHERE id = $blockid");
                        $result = $statement->execute();
                        $statement = $pdo->prepare("SELECT t1_id, t2_id, t3_id FROM blocks WHERE id = $blockid");
                        $result = $statement->execute();
                        $t = $statement->fetch();
                        $t1 = $t['t1_id'];
                        $t2 = $t['t2_id'];
                        $t3 = $t['t3_id'];
                        $statement = $pdo->prepare("UPDATE messages SET block_id = $blockid WHERE id = $t1 OR id = $t2 OR id = $t3");
                        $result = $statement->execute();

                        $transactionids = [];
                        $deltabalance = [];
                        $statement = $pdo->prepare("SELECT t1_id, t2_id, t3_id FROM blocks WHERE valid = 1");
                        $statement->execute();
                        while($values = $statement->fetch(PDO::FETCH_NUM)){
                            foreach($values as $v){
                                array_push($transactionids, $v);
                            }
                        }
                        $where_in = implode(',', $transactionids);
                        $statement = $pdo->prepare("SELECT sender, receiver, sum FROM messages WHERE id IN ($where_in)");
                        $statement->execute();
                        while($action = $statement->fetch(PDO::FETCH_ASSOC)){
                            updatebalance($action['sum'],$action['receiver']);
                            updatebalance(-$action['sum'],$action['sender']);
                        }
                        $statement = $pdo->prepare("SELECT reward, miner FROM blocks WHERE valid = 1");
                        $statement->execute();
                        while($reward = $statement->fetch()){
                            updatebalance($reward['reward'], $reward['miner']);
                        }

                        foreach(array_keys($deltabalance) as $key){
                            $keys = explode(" ",$key);
                            $statement = $pdo->prepare("UPDATE benutzer SET balance = $deltabalance[$key] WHERE public_key = $keys[0] AND key_n = $keys[1]");
                            $statement->execute();
                        }




                    }
                }
                else{
                    ?><p id="block_id"><?php
                    echo("discarding block ".$blockid);
                    $statement = $pdo->prepare("DELETE FROM blocks WHERE id = $blockid");
                    $result = $statement->execute();
                }
                ?>
                    </p>
                <?php

            }
            else{
                $statement = $pdo->prepare("SELECT * FROM blocks WHERE id = $blockid");
                $result = $statement->execute();
                $block = $statement->fetch(PDO::FETCH_ASSOC);
                $json = json_encode($block);
                ?>
                <p id="block_id"> <?php echo($json) ?> </p>
                <?php
                $t1 = $block['t1_id'];
                $t2 = $block['t2_id'];
                $t3 = $block['t3_id'];
                echo("e".$t1);
                $statement = $pdo->prepare("SELECT id, sender, receiver, text, date FROM messages WHERE id IN ($t1, $t2, $t3) ORDER BY id DESC");    
                $result = $statement->execute();
                $count = 1;
                while($t = $statement->fetch(PDO::FETCH_ASSOC)){
                    $json = json_encode(($t));
                    ?>
                    <p id="t<?php print($count)?>"> <?php echo($json) ?> </p>
                    <?php
                    $count++;
                }
        }
            
        }
        ?>
        <p id="feedback">
        <?php
        if(isset($_POST['blockchaindata'])){
            $data = trim($_POST['blockchaindata']);
            $statement = $pdo->prepare("UPDATE nodes SET blockchaindata = :data WHERE id = $id");
            $result = $statement->execute(array('data' =>$data));

        }

        if(isset($_POST['requestdata'])){
            $statement = $pdo->prepare("SELECT blockchaindata FROM nodes WHERE last_seen >= NOW() - INTERVAL 10 MINUTE and blockchaindata != '' ");
            $result = $statement->execute();
            $returndata = " ";
            while($currentdata = $statement->fetch(PDO::FETCH_ASSOC)){
                $returndata = $returndata." DATA: ". ($currentdata['blockchaindata']);
            }
            echo($returndata);
        }

        ?>
        </p>
        <?php


    };
    ?>
    <form action="?registernode">
        <input type="text" id="nodeid" name="nodeid">
        <input type="text" id="verify" name="verify">
        <input type="text" id="verifyblock" name="verifyblock">
        <input type="text" id="blockchaindata" name="blockchaindata">
        <input type="text" id="requestdata" name="requestdata">
    </form>

</body>
</html>