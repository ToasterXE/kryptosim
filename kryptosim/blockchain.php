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
        <h1>Blockchain (test)</h1>
    </head>
    <body>

    <div class="kopfzeile">

       
<a href="/index"><button>home</button></a>
<a href="/blockchain"><button>blockchain</button></a>
<a href="/register"><button>register</button></a>
<a href="/message"><button>encrypt/decrypt</button></a>


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
                    $statement = $pdo->prepare("SELECT * FROM blocks");
                    $statement->execute();
                    while($block = $statement->fetch(PDO::FETCH_ASSOC)){
                        $id = $block["id"];
                        $header = $block["header"];
                        $hash = $block
                        ?>
                        <div class='block'>
                            <div class="header">
                                <div id="header">
                                <?php echo($block['header()']); ?>
                                </div>
                            </div>    
                            <div class="liste">
                                <textarea readonly name="t1" id="t1"><?php print((isset($_GET['t1'])) ? trim(getjason($block['t1_id'], $pdo)) : "")?></textarea>
                                <textarea readonly name="t2" id="t2"> </textarea>
                                <textarea readonly name="t3" id="t3"> </textarea>
                            </div>         
                            <div class="header">
                            <div id="hash">
                                (noch berechnen)
                            </div>
                            </div>
                            
                            </div>                    
                        <?php
                        }
                        ?>
        </div>
            <button onclick="verify_blocks()">verify</button>
            <button onclick="update_blocks()">update</button>
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
?>