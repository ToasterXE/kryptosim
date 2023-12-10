<?php
    if($_SERVER["REQUEST_METHOD"]== "post" ){
        $username = $_POST["username"];
        $password = $_POST["email"];


    $response = [
        "status" => "success",
        "message" => "test bestenden"
    ];

    header("Content-Type: applicaion/json");
    echo json_encode($response);
    }
?>