<?php

    declare(strict_types=1);

    require_once __DIR__ . '/../../application/config/config.php';
    require_once ROOT_PATH . 'application/includes/init.php';
    require_once ROOT_PATH . 'application/factories/PositionDatabaseFactory.php';

    $positionDB = \PositionDatabaseFactory::create();

    if (isset($_POST['drag']))
    {
        $xAxis  = (int)$_POST['x'];
        $yAxis  = (int)$_POST['y'];
        $id  = (int)$_POST['id'];


        $positionDB->updatePosition($id, $xAxis, $yAxis);

    }

    if (isset($_POST['resize']))
    {
        $width  = (int)$_POST['w'];
        $height = (int)$_POST['h'];
        $divID  = (int)$_POST['id'];


        $positionDB->updateSize($width, $height, $divID);
    }

    exit();