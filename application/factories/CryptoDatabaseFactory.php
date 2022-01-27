<?php

    declare(strict_types=1);

    require_once ROOT_PATH . 'application/factories/PDOFactory.php';
    require_once ROOT_PATH . 'application/database/CryptoDatabase.php';

    
    class CryptoDatabaseFactory
    {
        public static $cryptoDatabase = null;

        public static function create() : \CryptoDatabase
        {
            if(is_null(CryptoDatabaseFactory::$cryptoDatabase))
            {
                CryptoDatabaseFactory::$cryptoDatabase = new \CryptoDatabase(\PDOFactory::getConnection());
            }
            return CryptoDatabaseFactory::$cryptoDatabase;
        }
    }
