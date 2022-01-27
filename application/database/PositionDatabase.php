<?php

    declare(strict_types=1);

    require_once ROOT_PATH . 'application/entities/Position.php';
    require_once ROOT_PATH . 'application/factories/LayoutDatabaseFactory.php';
    require_once ROOT_PATH . 'application/factories/CryptoDatabaseFactory.php'; 

    class PositionDatabase
    {
        private $pdo;
        
        public function __construct(\PDO $pdo)
        {
            $this->pdo = $pdo;
        }

        public function addPosition(int $widgetID, int $xAxis, int $yAxis, int $width, int $height, int $layoutID) : void
        {
  
            $stmt = $this->pdo->prepare(
                'INSERT INTO `positions` (`xpos`, `ypos`, `widget_id` ,`widget_w`, `widget_h`, `layout_id`)
                VALUES (:xpos, :ypos, :widget_id, :widget_w, :widget_h, :layout_id)');


            $stmt->bindValue(':xpos', $xAxis, PDO::PARAM_INT);

            $stmt->bindValue(':ypos', $yAxis, PDO::PARAM_INT);

            $stmt->bindValue(':widget_w', $width, PDO::PARAM_INT);

            $stmt->bindValue(':widget_h', $height, PDO::PARAM_INT);

            $stmt->bindValue(':widget_id', $widgetID, PDO::PARAM_INT);

            $stmt->bindValue(':layout_id', $layoutID, PDO::PARAM_INT);


            $stmt->execute();


        }

        public function updatePosition(int $id, int $xAxis, int $yAxis) : void
        {
            $stmt = $this->pdo->prepare(
                'UPDATE `positions` SET `xpos` = :xAxis, `ypos` = :yAxis
                WHERE `widget_id` = :widget_id');

            $stmt->bindValue(':widget_id', $id, PDO::PARAM_INT);

            $stmt->bindValue(':xAxis', $xAxis, PDO::PARAM_INT);

            $stmt->bindValue(':yAxis', $yAxis, PDO::PARAM_INT);

            $stmt->execute();

    
        }


        public function updateSize (int $width, int $height, int $divID) : void
        {
            $stmt = $this->pdo->prepare(
                'UPDATE `positions`
                SET `widget_w` = :width, `widget_h` = :height 
                WHERE `widget_id` = :divID 
                ');
            
            $stmt->bindValue(':divID', $divID, PDO::PARAM_INT);

            $stmt->bindValue(':width', $width, PDO::PARAM_INT);

            $stmt->bindValue(':height', $height, PDO::PARAM_INT);

            $stmt->execute();
        }


        public function getSize(int $divID) : array
        {
            $stmt = $this->pdo->prepare(
                'SELECT `widget_w`, `widget_h`
                FROM `positions`
                WHERE `widget_id` = :divID'
            );

            $stmt->bindValue('divID', $divID, PDO::PARAM_INT);

            $stmt->execute();

            $widget = array();

            while($row = $stmt->fetch())
            {
                $widget = array('w' => $row['widget_w'], 'h' => $row['widget_h']);
            }

            return $widget;
        }


        public function deleteWidget(string $code) : void
        {
            $stmt = $this->pdo->prepare(
                'DELETE 
                FROM `widgets` 
                WHERE `widget_code` = :code'
            );

            $stmt->bindValue(':code', $code, PDO::PARAM_STR);

            $stmt->execute();

        }

        public function getWidgetID(string $code) : ? int
        {
            $stmt = $this->pdo->prepare(
                'SELECT `widget_id`
                FROM `widgets`
                WHERE `widget_code` = :widget_code'
            );

            $stmt->bindValue(':widget_code', $code, PDO::PARAM_STR);

            $stmt->execute();

            if ($row = $stmt->fetch())
            {
                return (int)$row['widget_id'];
            }

            return null;
        }
    }