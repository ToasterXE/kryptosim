<?php

if (!isset($_SERVER['HTTPS'])){
    header('Location: https://kryptosim.eu/user');
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
        <link rel="stylesheet" href="main/style-main.css">
        <link rel="stylesheet" href="main/style-user.css">
        <link rel="stylesheet" href="main/register.css">
        
        <link rel="icon" type="image/x-icon" href="/main/favicon.ico">
        <script src="main/e.js"></script>
        <script src="main/page.js"></script>
        <title>Kryptosim - user</title>
        <h1>Profile</h1>
    </head>
    <body onload="page(0)">
    <div>

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
                else if(!isset($_POST['generate_button'])){
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
       
        <div class="main">
            <div class="userinfo">
                <h2>User information

                </h2>

                <?php
                    if($_SESSION['userid']==""){
                        echo("log in to view profile");
                    }

                    else{
                    $id = $_SESSION['userid'];
                    $statement = $pdo->prepare("SELECT * FROM benutzer WHERE id = :id ");
                    $result = $statement->execute(array('id' => $id));
                    $user = $statement->fetch();
                   ?>

                <table class="bigtable">
                <tr>
                    <td class="left">
                        username:
                    </td>
                    <td class="right">
                        <?php
                        echo($user['first_name']);
                        ?>

                    </td>
                </tr>
                <tr>
                    <td class="left">
                        email:
                    </td>
                    <td class="right">
                        <?php
                        echo($user['email']);
                        ?>

                    </td>
                </tr>
                <tr>
                    <td class="left">
                        account creation:
                    </td>
                    <td class="right">
                        <?php
                        echo($user['created_at']);
                        ?>

                    </td>
                </tr>
                <tr>
                    <td class="left">
                        user id:
                    </td>
                    <td class="right">
                        <?php
                        echo($user['id']);
                        ?>

                    </td>
                </tr>
                <tr>
                    <td class="left">
                        current ip address:
                    </td>
                    <td class="right">
                        <?php
                        echo($_SERVER['REMOTE_ADDR']);
                        ?>

                    </td>
                </tr>
                </table>

            </div>

            <div class="walletinfo">
                <h2>Wallet</h2>    
            

                <table class="bigtable">
                <tr>
                    <td class="left">
                        public key:
                    </td>
                    <td class="right" id="public_key">
                        <?php
                        if($user['key_n']!=0){
                            echo($user['public_key']." ".$user['key_n']);
                        }
                        ?>
                    </td>
                    <td class="right">
                        <button onclick="copy('public_key')">copy</button>
                    </td>
                </tr>
                <tr>
                    <td class="left">
                        private key:
                    </td>
                    <td class="right" id ="private_key">
                        <?php
                        if($user['key_n'] != 0){
                            echo($user['private_key']." ".$user['key_n']);
                        }
                        ?>
                    </td>
                    <td class="right">
                        <button onclick="copy('private_key')">copy</button>
                    </td>
                </tr>
                    <td class="left">
                        balance:
                    </td>
                    <td class="right">
                        <?php
                        echo($user['balance']);
                        ?>

                    </td>
                </tr>
                </table>

                <?php
                    if($user['public_key']==0){
                        ?>
                        <form method="post" id="hidden">
                        <button type="submit" name="generate_button" value="e">generate keypair</button>
                        </form>
                        <?php

                        if(isset($_POST['generate_button'])){
                            
                            $a =generate_pair();
                            $private_d = $a[0];
                            $public_e = $a[1];
                            $modul_n = $a[2];

                            // echo($private_d." ".$public_e." ".$modul_n);

                            $statement = $pdo->prepare("UPDATE benutzer SET private_key = :private_key, public_key = :public_key, key_n = :key_n WHERE id = $id");
                            $result = $statement->execute(array('private_key' => $private_d, 'public_key' => $public_e, 'key_n' => $modul_n));
                            if($result) {
                                header('Location: /user');
                                die();
                            }
                        }
                    }
                ?>

            </div>
        </div>
        
        <?php
        }
        $sortby = isset($_GET['sort']) ? $_GET['sort'] : 'id';
        $order = isset($_GET['order']) ? $_GET['order'] : 'ASC'; 
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        if(isset($_POST['usersearch'])){
            $search = ($_POST['usersearch']);
        }
        ?>
        </div>
    </div>
    <h2>global users</h2>
    <div class="forms">
        <div class="forms">
        <p>sort by:</p>
        <form method="post" style="all: unset" action="?order=<?php print(($order)); if(!empty($search)){?>&search=<?php print($search);}?>">

            <button type="submit" name="namesort" id="namesort">name 
            <?php if(isset($_POST['namesort'])){
                $sortby = 'first_name';
                ?>
                &check;
                <?php
            }
            ?>    
            </button>
            <button type="submit" name="creationsort" id="creationsort">account creation        
                <?php if(isset($_POST['creationsort'])){
                    $sortby = 'created_at';
                ?>
                    &check;
                <?php
            }
            ?>  
            </button>
            <button type="submit" name="keysort" id="keysort">key     
                <?php if(isset($_POST['keysort'])){
                    $sortby = 'public_key';
                ?>
                &check;
                <?php
            }
            ?>  
            </button>
            <button formaction="?order=<?php print(($order == 'ASC') ? 'DESC' : 'ASC'); ?>&sort=<?php print($sortby); if(!empty($search)){?>&search=<?php print($search);}?>" type="submit" name="changeorder" id="changeorder">
                <?php 
                    if($order == 'ASC'){
                        ?>&uarr;<?php
                    }
                    else{ 
                        ?>&darr;<?php
                    }               
                ?>
                
            </button>
        </form>
        </div>

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
    <div class="userliste">
        <br>
        <?php



            $statement = $pdo->prepare("SELECT * FROM benutzer WHERE 
                                        first_name LIKE '%{$search}%' 
                                        OR id LIKE '%{$search}%' 
                                        OR public_key LIKE '%{$search}%' 
                                        OR key_n LIKE '%{$search}%' 
                                        ORDER BY $sortby $order");
            $result = $statement->execute(array());
            $count = 0;
            while($messages = $statement->fetch()){
                $count++;
                ?>
                <div class="useritem lightborder">
                    <table>
                    <tr>
                        <td class="left">
                            username:
                        </td>
                        <td class="right">
                            <?php
                            echo($messages['first_name']);
                            ?>

                        </td>
                        <td class="right">
                            <button class="tablebutton">message</button>
                        </td>
                    </tr>
                    <tr>
                        <td class="left">
                            account creation:
                        </td>
                        <td class="right">
                            <?php
                            echo($messages['created_at']);
                            ?>

                        </td>
                    </tr>
                    <tr>
                        <td class="left">
                            user id:
                        </td>
                        <td class="right">
                            <?php
                            echo($messages['id']);
                            ?>

                        </td>
                    </tr>

                    <tr>
                        <td class="left">
                            public key:
                        </td>
                        <td class="right" id="public_key<?php print($messages['id']) ?>">
                            <?php
                            if($messages['key_n']!=0){
                                echo($messages['public_key']." ".$messages['key_n']);
                            }
                            ?>
                        </td>
                        <td class="right">
                            <button class="tablebutton" onclick="copy('public_key<?php print($messages['id']) ?>')">copy</button>
                        </td>
                    </tr>
                    <!-- </tr>
                        <td class="left">
                            balance:
                        </td>
                        <td class="right">
                            <?php
                            echo($messages['balance']);
                            ?>

                        </td>
                    </tr> -->
                    </table>
                    </div>

                <?php
            }
        ?>
    </div>
    <div>
        <p id="pagenum">Page 0 of 0</p>
        <button id="prev" onclick="page(-1)">&lt;&lt;previous page</button>
        <button id="next" onclick="page(1)">next page>></button>
    </div>
       <!-- <p>eeeeeeeeeeeeeeaeea</p> -->




<?php
function generate_pair(){
    $p = getprime();
    $q = getprime();
    $n = $p*$q;
    // echo($p." ".$q." ".$n." ");

    $ph = ($p-1)*($q-1);
    // echo($ph);
    // echo("ph ");
    $e = getprime(max($p, $q), $ph);

    // echo($e);
    // echo("e ");
    // echo($ph);
    // echo("ph ");
    // echo($ph);
    // echo("ph ");
    $d = getd($ph,$e);
    // echo("d ");
    // echo($ph);
    // echo("ph ");
    return array($d, $e, $n);

}

function getd($phi = 1000000000*1000000000,$e){
    $temp = 1;
    $p = $phi;
    // echo($phi);
    // echo("phfunc ");
    while($temp % $e != 0){
        $temp += $p;
    }
    // echo(" e".$temp." ".$phi."e ");

    return $temp/$e;

}

function phi($n){
    $sq = sqrt($n)+1;
    $ans = 0;
    for($i = 1; $i <$sq; $i += 1){
        if($n%$i!=0){
                $ans++;
        }
    }
    return $ans;

}

function getprime($min=100000000, $max=999999999){
    $num = random_int(100000,999999);
    $counter = 0;
    while(!isprime($num)){

        $num = random_int(100000,999999);
        $counter += 1;

    }
    // echo($counter."\r\n");

    return $num;

}
function isprime($n){
    switch(gmp_prob_prime($n)){
        case 0:
            return false;
        case 1:

            $sq = sqrt($n)+1;
            for($i = 1; $i <$sq; $i += 6){
                if($n%$i!=0){
                    return false;
                }
            }
            return true;

        case 2:
            return true;

   }
}

?>

</body>
</html>