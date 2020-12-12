<?php
namespace src\services;

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Channel\AMQPChannel;

class MessageQueueService {
    private $connection = null;

    public function __construct()
    {
        try {
            $this->connection = new AMQPConnection('192.168.0.105', 5672, 'guest', 'guest');
        } catch (\Exception $e) {
            echo "it cam ein exception";
            exit($e->getMessage());
        }
    }
 
    public function createQueue($input){
        try{
        $channel = new AMQPChannel($this->connection);

        $channel = $this->connection->channel();
       
         $channel->queue_declare(
             'demoQueue',    
             false,          
             true,          #durable - make sure that RabbitMQ will never lose our queue if a crash occurs - the queue will survive a broker restart
             false,          #exclusive - used by only one connection and the queue will be deleted when that connection closes
             false           #autodelete - queue is deleted when last consumer unsubscribes
             );
         $msg = new AMQPMessage($input);
         
         $channel->basic_publish($msg, '','demoQueue');
         $response['status_code_header'] = 'HTTP/1.1 201 Created';
         $response['body'] = "success";
         $channel->close();
         $this->connection->close();

         return $response;
            }catch(\Exception $e){
                $response['status_code_header'] = 'HTTP/1.1 500 Internal Server Error';
                $response['body'] = "something went wrong";
                return $response;
            }
    }

     
 

}