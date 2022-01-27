<?php

    declare(strict_types=1);

    require_once ROOT_PATH . 'application/factories/PDOFactory.php';
    require_once ROOT_PATH . 'application/database/PositionDatabase.php';
    

    class PositionDatabaseFactory
    {
        public static $positionDatabase = null;

        public static function create() : \PositionDatabase
        {
            if(is_null(PositionDatabaseFactory::$positionDatabase))
            {
                PositionDatabaseFactory::$positionDatabase = new \PositionDatabase(\PDOFactory::getConnection());
            }

            return PositionDatabaseFactory::$positionDatabase;
        }

    }
