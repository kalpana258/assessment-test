<?php

namespace src\Controller;

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Channel\AMQPChannel;
class TransactionController {

    public $mesageQueueService;
    public $db;
    
    public function __construct($db,$messageQueue)
    {
         $this->db = $db;
         $this->mesageQueueService = $messageQueue;
    }

    public function processRequest()
    {
    $input = file_get_contents('php://input');
    if (!$this->checkInput($input)) {
       
        $response =  $this->unprocessableEntityResponse();
    }else{
         $response = $this->mesageQueueService->createQueue($input);
    }
    header($response['status_code_header']);
    if ($response['body']) {
        echo $response['body'];
    }
}


 private function checkInput($input)
    {
        $input  = json_decode($input,true);
        if (!isset($input['id'])) {
            return FALSE;
        }
        if (! isset($input['sku'])) {
            return FALSE;
        }
        if (! isset($input['variant_id'])) {
            return FALSE;
        }
        if (! isset($input['title'])) {
            return FALSE;
        }
        return TRUE;
    }

 private function unprocessableEntityResponse()
    {
        
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([
            'error' => 'Invalid input'
        ]);
  
        return $response;
    }

}