<?php

    declare(strict_types=1);

    require_once ROOT_PATH . 'application/factories/PDOFactory.php';
    require_once ROOT_PATH . 'application/database/WidgetDatabase.php';

    class WidgetDatabaseFactory
    {
        public static $widgetDatabase = null;

        public static function create() : \WidgetDatabase
        {
            if(is_null(WidgetDatabaseFactory::$widgetDatabase))
            {
                WidgetDatabaseFactory::$widgetDatabase = new \WidgetDatabase(PDOFactory::getConnection());
            }

            return WidgetDatabaseFactory::$widgetDatabase;
        }
    }