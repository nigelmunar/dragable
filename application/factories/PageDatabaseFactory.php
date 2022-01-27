<?php
    declare(strict_types = 1);

    require_once ROOT_PATH . 'application/factories/PDOFactory.php';
    require_once ROOT_PATH . 'application/database/PageDatabase.php';

    class PageDatabaseFactory
    {
        public static $pageDatabase = null;

        public static function create() : \PageDatabase
        {
            if(is_null(PageDatabaseFactory::$pageDatabase))
            {
                PageDatabaseFactory::$pageDatabase = new \PageDatabase(\PDOFactory::getConnection());
            }

            return PageDatabaseFactory::$pageDatabase;
        }

    }
