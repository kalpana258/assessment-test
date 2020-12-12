<?php

require 'vendor/autoload.php';

use src\Config\DatabaseConnector;
use src\services\MessageQueueService;

$dbConnection = (new DatabaseConnector())->getConnection();

$messageQueue = new MessageQueueService();