<?php
  $host_name = 'db5014852654.hosting-data.io';
  $database = 'dbs12339433';
  $user_name = 'dbu1139207';
  $password = '^h6!-vJAmpQ_Cpg';

  $link = new mysqli($host_name, $user_name, $password, $database);

  if ($link->connect_error) {
    die('<p>Verbindung zum MySQL Server fehlgeschlagen: '. $link->connect_error .'</p>');
  } 
  else {
    echo '<p>Verbindung zum MySQL Server erfolgreich aufgebaut.</p>';
  }
?>