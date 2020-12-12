<?php

require "bootstrap.php";

$tokenVal = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c";

for($i=0;$i<1000000;$i++){
    
    $data = json_encode([
        "id"=> uniqid(),
        "sku"=> rand(),
        "variant_id"=>uniqid(),
        "title"=>"tshirt"
    ]);
   
    $ch = curl_init();
  
    if(curl_errno($ch)){
        echo 'Curl error: ' . curl_error($ch);
    }
    curl_setopt($ch, CURLOPT_URL, "http://192.168.0.105:81/transactions");
    $authorization = "Authorization: Bearer ".$tokenVal; 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); 
    curl_setopt($ch, CURLOPT_POST, 1);// set post data to true
    curl_setopt($ch, CURLOPT_POSTFIELDS,$data);   // post data
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $json = curl_exec($ch);
   $httpcode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
   if($httpcode == 201){
          echo "success";
   }else{
    echo 'Curl error: ' . curl_error($ch);
   }

     curl_close($ch);
    
}

