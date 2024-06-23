<?php

if (!isset($_SERVER['HTTPS'])){
    header('Location: https://kryptosim.eu');
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
        <link rel="stylesheet" href="./main/style-home.css">
        <link rel="icon" type="image/x-icon" href="/main/favicon.ico">
        <script src="main/e.js"></script>

        <title>kryptosim - home</title>
        <h1>Willkommen bei kryptosim.eu!</h1>
        <h2>Eine Website zur Simulation einer Kryptowährung</h2>
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
        <div class="weblink">
                <a href="/register" class="title">register -></a>
                <p class="description">Hier lässt sich zur Verwaltung des eigenen Schlüsselpaars ein Account erstellen.</p>
            </div>
            <div class="weblink">
                <a href="/user" class="title">user -></a>
                <p class="description">Hier finden sich Details über die eigenen Accountdaten und Schlüssel sowie eine Übersicht über alle Benutzer.</p>
            </div>
            <div class="weblink">
                <a href="/message" class="title">encrypt/decrypt -></a>
                <p class="description">Hier können Nachrichten verfasst und mit beliebigen Schlüsseln ent- und verschlüsselt werden. <br> Verschlüsselte Nachrichten können in den Pool gesendet werden. <br> Transaktionen können getätigt und zur weiteren Verifizierung in den Transaktionenpool gesendet werden.</p>
            </div>
            <div class="weblink">
                <a href="/pool" class="title">pool -></a>
                <p class="description">Im öffentlichen Nachrichtenpool befinden sich verschlüsselte Nachrichten, welche nur mit einem bestimmten Schlüssel gelesen werden können. <br> Im Transaktionenpool finden sich bereits abgeschlossene sowie wartende Transaktionen. Von hier können sie zur Verifizierung in das Netzwerk geschickt werden und von einem Miner in einen neuen Block aufgenommen werden.</p>
            </div>
            <div class="weblink">
                <a href="/block" class="title">block -></a>
                <p class="description">Auf dieser Seite werden über den Transaktionenpool Informationen über einen Block zusammengestellt, sodass der Proof of Work berechnet werden kann. <br> Der Miner kann den fertigen Block dann zur Verifizierung in das Netzwerk schicken und erhält für einen korrekten Block den Miner's reward.</p>
            </div>
            <div class="weblink">
                <a href="/blockchain" class="title">blockchain -></a>
                <p class="description">Hier lässt sich die gesamte Blockchain einsehen und lokal verifizieren.</p>
            </div>
        <h2>downloads</h2>
        <div class="weblink">
            <a href="/files/node_v1_2.zip" download class="title">node.py -></a>
            <p class="description">Dies ist das Programm für Netzwerkknoten, mit welchem sich leicht ein eigener Netzwerkknoten integrieren lässt. <br> Anforderungen: Windows/Linux Rechner mit Internetzugang <br> Installationshinweise finden sich im Download. </p>
        </div>
        <div class="weblink">
            <a href="/files/Proof_of_Work_Calculator_v1.zip" download class="title">POW.exe -></a>
            <p class="description">Programm, um den Proof of Work eines Blockes zu berechnen. <br> Anforderungen: Windows Rechner <br> Zu beachten ist, dass dieses Programm sehr aufwendige Berechnungen durchführt und sich dementsprechend auf die Systemressourcen auswirkt. </p>
        </div>
        </div>

    </body>
</html>


