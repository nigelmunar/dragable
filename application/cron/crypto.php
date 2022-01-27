<?php

    declare(strict_types=1);

    require_once __DIR__ . '/../config/config.php';
    require_once ROOT_PATH . 'application/includes/init.php';
    require_once ROOT_PATH . 'application/factories/CryptoDatabaseFactory.php';


    $cryptoDatabase = \CryptoDatabaseFactory::create();

    $response = file_get_contents(ROOT_PATH . 'application/json/crypto.json');
    $data = json_decode($response);

    $cryptoDatabase->insertData($data);
