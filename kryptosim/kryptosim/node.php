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

        if($node['block_id'] != 0){
            ?>
            <p id="block_id"> <?php echo($node['block_id'])?></p>
            <?php
        }
        if($node['transaction_id'] != 0){
            $trans_id = $node['transaction_id'];

            if(isset($_POST['verify'])){
                ?>
                <p id="transaction_id"> <?php echo(sprintf("answer to node #{$id}: success"))?></p>
                <?php
                $statement = $pdo->prepare("UPDATE nodes SET transaction_id = 0 WHERE id = $id");
                $result = $statement->execute();
                $set = false;
                $statement = $pdo->prepare("SELECT * FROM nodes WHERE id > $id and last_seen >= NOW() - INTERVAL 10 SECOND");
                $result = $statement->execute();
                $newnode = $statement->fetch();
                if($newnode){
                    $newid = $newnode['id'];
                    $statement = $pdo->prepare("UPDATE nodes SET transaction_id = $trans_id WHERE id = $newid");
                    $result = $statement->execute();
                    $set = true;
                }
                if(!$set and $trans_id != 33){
                    $statement = $pdo->prepare("UPDATE messages SET valid = 1 WHERE id = $trans_id");
                    $result = $statement->execute();
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

    };
    ?>
    <form action="?registernode">
        <input type="text" id="nodeid" name="nodeid">
        <input type="text" id="verify" name="verify">
    </form>

</body>
</html>
