<?php
require 'bootstrap.php';

$statement = <<<MySQL_QUERY
    CREATE TABLE IF NOT EXISTS products (
        id VARCHAR(30) NOT NULL,
        sku int(50) NOT NULL,
        title VARCHAR(100),
        created_at timestamp DEFAULT current_timestamp(),
        updated_at timestamp DEFAULT current_timestamp()
      ) ENGINE=INNODB;
	  
	  CREATE TABLE IF NOT EXISTS variants (
        id VARCHAR(30) NOT NULL,
        color VARCHAR(80) NOT NULL,
        size VARCHAR(25) NOT NULL
      ) ENGINE=INNODB;
   
MySQL_QUERY;

try {
	//print_r($statement);
	//exit();
    $createTable = $dbConnection->exec($statement);
	
    echo "Success!\n";
} catch (\PDOException $e) {
    exit($e->getMessage());
}