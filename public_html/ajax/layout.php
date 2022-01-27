<?php
    declare(strict_types=1);

    require_once __DIR__ . '/../../application/config/config.php';
    require_once ROOT_PATH . 'application/includes/init.php';
    require_once ROOT_PATH . 'application/factories/WidgetDatabaseFactory.php';
    require_once ROOT_PATH . 'application/factories/LayoutDatabaseFactory.php';


    $widgetDB       = \WidgetDatabaseFactory::create();
    $layoutDB       = \LayoutDatabaseFactory::create();


    $code           = $_GET['code'];
    $page           = $_GET['page'];


    $layoutDB->activeLayout($code, (int)$page);
    
    $widgets = $widgetDB->getWidgets((int)$page);

    foreach ($widgets as $widget)
    {
        $data [] = array('id' => $widget->getWidgetID(), 'type' => $widget->getWidgetType(), 'code' => $widget->getWidgetCode(), 'content' => $widget->getWidgetContent(), 'x' => $widget->getXPos(), 'y' => $widget->getYPos(), 'h' => $widget->getHeight(), 'w' => $widget->getWidth());
    }

    echo json_encode($data);
