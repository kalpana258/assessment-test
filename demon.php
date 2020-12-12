<?php
if (PHP_SAPI != "cli") {
  die("Please run this script from the command line");
}

require "bootstrap.php";

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Channel\AMQPChannel;


define('DB_HOST', 'mysql-box');
define('DB_NAME', 'lovebinto');
define('DB_USER', 'root');
define('DB_PASSWORD', 'example');
ini_set("display_errors", 1);
error_reporting(E_ALL & ~E_NOTICE);
define('LOG_KEEP', true);
define('LOG_FILE', 'daemon.log');
function addlog ($message="") {
  error_log(
    "[" . date("Y-m-d H:i:s") . "] " . $message . PHP_EOL,
    3, LOG_FILE
  );
}
define('LOOP_CYCLE', 1);
//Establish connection AMQP
$connection = new AMQPConnection('rabbitmq', 5672, 'guest', 'guest');
$channel = $connection->channel();
$channel->basic_consume(
  'demoQueue',                    
  '',                         
  false,                          
  true,                          
  false,                          
  false,                          
  'processData');
  
// while(count($channel->callbacks)) {
//   $channel->wait();
// }
while ($channel->is_consuming()) {
  $channel->wait();
}
$channel->close();
$connection->close();
 function processData($msg)
    {
      $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
      $current = json_decode($msg->getBody());
   
      $id= $current->id;
      $sku= $current->sku;
      $title= $current->title;
      $variant_id= $current->variant_id;
  


$sql1 = "SELECT sku from products WHERE sku=$sku";

$result_sql = mysqli_query($conn, $sql1);

if(mysqli_num_rows($result_sql) <= 0){
  $sql = "INSERT INTO products(id, sku,title)
  VALUES('$id',$sku ,'$title')";
    if(mysqli_query($conn, $sql)) {
     // echo "insertion is successfullu";
      $success = "Successful!";
    } 
    else {
     // echo "insertion is falied";
      $failure = "Unable to INSERT into DB: " . mysqli_error($conn);
    }
}else{
  // log errors
  if (LOG_KEEP) { addlog( "cannot progress record already exists for product."); }

}
$sqlVariantCheck = "SELECT id from variants WHERE id='$variant_id'";

$result_variant= mysqli_query($conn, $sqlVariantCheck);
if(mysqli_num_rows($result_variant) <= 0){
  $sql = "INSERT INTO variants(id, color,size)
  VALUES('$variant_id','testcolor' ,'testSize')";
    if(mysqli_query($conn, $sql)) {
      if (LOG_KEEP) { addlog( "Successfully inserted "); }
    } 
    else {
   if (LOG_KEEP) { addlog( "Unable to insert into product table "); }
    }
}else{
  // log errors
  if (LOG_KEEP) { addlog( "cannot progress record already exists for variant"); }
 
}
  mysqli_close($conn);

    }

