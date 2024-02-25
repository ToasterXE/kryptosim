<!-- unwichtig -->
<?php
$password = "$2y$10$.g1iYEEjH3UVCLOwAGjb1.v9uHKGk1GW4BzADW60ZCrjQgYRyQiZO$";
$e = "eeeeeee";

function generateRandomString($length) {
    return substr(str_shuffle(str_repeat($x='abcdefghijklmnopqrstuvwxyz', ceil($length/strlen($x)) )),1,$length);
}



while(!password_verify($e, $password)){
    $e = generateRandomString(7);
}
echo ($e);
?>
<html>
    e
</html>