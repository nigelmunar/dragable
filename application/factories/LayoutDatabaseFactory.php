<?php
    declare(strict_types = 1);

    require_once ROOT_PATH . 'application/factories/PDOFactory.php';
    require_once ROOT_PATH . 'application/database/LayoutDatabase.php';

    class LayoutDatabaseFactory
    {
        public static $layoutDatabase = null;

        public static function create() : \LayoutDatabase
        {
            if(is_null(LayoutDatabaseFactory::$layoutDatabase))
            {
                LayoutDatabaseFactory::$layoutDatabase = new \LayoutDatabase(\PDOFactory::getConnection());
            }

            return LayoutDatabaseFactory::$layoutDatabase;
        }

    }
