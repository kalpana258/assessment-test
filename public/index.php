<?php
require "../bootstrap.php";
use src\Controller\TransactionController;


header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods:POST");


$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

$tokenVal = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c";


$authHeader = $_SERVER['HTTP_AUTHORIZATION'];

$arr = explode(" ", $authHeader);

$jwt = $arr[1]??'';

if($jwt){

    if($tokenVal !== $jwt){
        http_response_code(401);

        echo json_encode(array(
            "message" => "Access denied."
         
        ));
        exit();
    }
   
}else{
    http_response_code(401);

    echo json_encode(array(
        "message" => "Access denied.Token is missing"
     
    ));
    exit();
}

// all of our endpoints start with /transactions
// everything else results in a 404 Not Found
if ($uri[1] !== 'transactions') {
   
    header("HTTP/1.1 404 Not Found");
    exit();
}

$requestMethod = $_SERVER["REQUEST_METHOD"];
if($requestMethod != "POST"){
    header("HTTP/1.1 405 Method not allowed");
    exit();
}

// pass the request method and user ID to the TransactionController and process the HTTP request:
$controller = new TransactionController($dbConnection, $messageQueue);
$controller->processRequest();