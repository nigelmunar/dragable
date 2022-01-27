<?php

    declare(strict_types=1);

    class PDOFactory 
    {
        private static $pdo = null;

        public static function getConnection() : PDO
        {
            if(is_null(self::$pdo))
            {
                $options = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ];

                try
                {
                    self::$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_SCHEMA . ';charset=' . DB_CHARSET, DB_USER, DB_PASSWORD, $options);
                }
                catch (PDOException $e)
                {
                    throw new PDOException($e->getMessage(), (int)$e->getCode());
                }
            }

            return self::$pdo;
        }
    }