<?php 

    declare(strict_types=1);

    require_once ROOT_PATH . 'application/entities/Widget.php';

    class WidgetDatabase
    {
        private $pdo;

        public function __construct(\PDO $pdo) 
        {
            $this->pdo = $pdo;
        }

        public function addWidget(string $content, string $code, int $type, int $pageID) : int
        {
            $stmt = $this->pdo->prepare(
                'INSERT INTO `widgets` (`widget_content`, `widget_code`, `widget_type`, `page_id`)
                VALUES (:widget_content, :widget_code, :widget_type, :pageID)');


            $stmt->bindValue(':widget_content', $content, PDO::PARAM_STR);

            $stmt->bindValue(':widget_code', $code, PDO::PARAM_STR);

            $stmt->bindValue(':widget_type', $type, PDO::PARAM_INT);

            $stmt->bindValue(':pageID', $pageID, PDO::PARAM_INT);

            $stmt->execute();

            return (int)$this->pdo->lastInsertId();


        }


        public function updateWidget(string $code, string $content, int $type) : void
        {
            $stmt = $this->pdo->prepare(
                'UPDATE `widgets` 
                SET `widget_content` = :content, `widget_type` = :widget_type
                WHERE `widget_code` = :code');

            $stmt->bindValue(':code', $code, PDO::PARAM_STR);

            $stmt->bindValue(':content', $content, PDO::PARAM_STR);

            $stmt->bindValue(':widget_type', $type, PDO::PARAM_INT);

            $stmt->execute();

    
        }


        public function getWidgets(int $pageID) : array
        {
            $stmt = $this->pdo->prepare(
                'SELECT `w`.`widget_id`, `w`.`widget_code`, `p`.`xpos`, `p`.`ypos`, `p`.`widget_w`, `p`.`widget_h`, `w`.`widget_content`, `wt`.`widget_type_id`  
                FROM `layouts` AS `l` 
                JOIN `positions` AS `p` ON `p`.`layout_id` = `l`.`layout_id` 
                JOIN `widgets` AS `w` ON `p`.`widget_id` = w.`widget_id` 
                JOIN `widget_types` AS `wt` ON `wt`.`widget_type_id` = `w`.`widget_type`
                WHERE `w`.`page_id` = :pageID AND `l`.`active` = 1 AND `l`.`live` = 1');


            $stmt->bindValue(':pageID', $pageID,PDO::PARAM_INT);
            
            $stmt->execute();

            $widgets = array();

            while($row = $stmt->fetch())
            {
                $widget = new Entities\Widget;

                $widget->setWidgetID((int)$row['widget_id']);
                $widget->setWidgetCode($row['widget_code']);
                $widget->setWidgetType((int)$row['widget_type_id']);
                $widget->setXPos((int)$row['xpos']);
                $widget->setYPos((int)$row['ypos']);
                $widget->setWidth((int)$row['widget_w']);
                $widget->setHeight((int)$row['widget_h']);
                $widget->setWidgetContent($row['widget_content']);

                if($widget->getWidgetType() === 3)
                {
                    $sql = $this->pdo->prepare(
                        'SELECT `cryptocurrency`.`cryptocurrency_code` 
                        FROM `cryptocurrency`
                        JOIN `crypto_widgets` ON `crypto_widgets`.`cryptocurrency_id` = `cryptocurrency`.`cryptocurrency_id`
                        WHERE `crypto_widgets`.`widget_id` = :widgetID'
                    );

                    $sql->bindValue(':widgetID', $widget->getWidgetID(), PDO::PARAM_INT);

                    $sql->execute();

                    $widget->setCrypto($sql->fetch()['cryptocurrency_code']);
                }

                $widgets [] = $widget;
            }

            // while($row = $stmt->fetch())
            // {   
            //     $widgets [] = array('id' => $row['widget_id'], 'type' => $row['widget_type_id'], 'code' => $row['widget_code'], 'content' => $row['widget_content'], 'x' => $row['xpos'], 'y' => $row['ypos'], 'h' => $row['widget_h'], 'w' => $row['widget_w']);
            // }

            return $widgets;
        }

        public function getWidgetTypes() : array
        {
            $stmt = $this->pdo->prepare(
                'SELECT `widget_type_id`, `widget_type_name` 
                FROM `widget_types`'
            );

            $stmt->execute();

            $widgetTypes = array ();

            while($row = $stmt->fetch())
            {
                $widgetTypes [] = array('id' => $row['widget_type_id'], 'type' => $row['widget_type_name']);
            }

            return $widgetTypes;
        }
    }