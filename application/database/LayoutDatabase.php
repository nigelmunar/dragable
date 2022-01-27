<?php

    declare(strict_types = 1);

    require_once ROOT_PATH . 'application/entities/Layout.php';

    class LayoutDatabase
    {
        private $pdo;

        public function __construct(\PDO $pdo)
        {
            $this->pdo = $pdo;

        }

        public function layoutExist(string $name) : bool
        {
            $stmt = $this->pdo->prepare(
                'SELECT `layout_name`
                FROM `layouts`
                WHERE `layout_name` = :layout_name
                ');
            
            $stmt->bindValue(':layout_name', $name, PDO::PARAM_STR);

            $stmt->execute();

            if($stmt->rowCount() > 0)
            {
                return true;
            }

            return false;
        }

        public function getLayouts(int $pageID) : array
        {
            $stmt = $this->pdo->prepare(
                'SELECT `layout_id`, `layout_name`, `page_id`, `active`, `layout_code` 
                FROM `layouts` 
                WHERE `page_id` = :pageID and `live` = 1'
                );
            
            $stmt->bindValue(':pageID', $pageID, PDO::PARAM_INT);

            $stmt->execute();

            $layouts = array();


            while($row = $stmt->fetch())
            {
                $layout = new Entities\Layout;

                $layout->setLayoutID((int)$row['layout_id']);
                $layout->setLayoutName($row['layout_name']);
                $layout->setPageID((int)$row['page_id']);
                $layout->setActive((int)$row['active']);
                $layout->setLayoutCode($row['layout_code']);

                $layouts[] = $layout;
            }   

            return $layouts;
        }

        public function addLayout (string $name, int $pageID, string $code) : bool
        {
            $stmt = $this->pdo->prepare(
                'INSERT INTO `layouts` (`layout_name`, `page_id`, `layout_code`, `active`, `live`)
                VALUES (:layout_name, :page_id, :layout_code, :active, :live)'
            );

            $stmt->bindValue(':layout_name',$name, PDO::PARAM_STR);
            $stmt->bindValue(':page_id',$pageID, PDO::PARAM_STR);
            $stmt->bindValue(':layout_code',$code, PDO::PARAM_STR);
            $stmt->bindValue(':active', 0, PDO::PARAM_INT);
            $stmt->bindValue(':live', 1, PDO::PARAM_INT);

            $stmt->execute();

            $layoutID = (int)$this->pdo->lastInsertId();

            return $this->activeLayout($code, $pageID);

        }

        public function activeLayout(string $code, int $page) : bool
        {
            $stmt = $this->pdo->prepare(
                'UPDATE `layouts`
                SET `active` = CASE
                    WHEN layout_code = :layout_code THEN 1
                    ELSE 0
                END
                WHERE page_id = :page_id'
            );

            $stmt->bindValue(':layout_code', $code, PDO::PARAM_STR);
            
            $stmt->bindValue(':page_id', $page, PDO::PARAM_INT);

            return $stmt->execute();

        }

        public function getLayoutID(string $code) : ? int
        {
            $stmt = $this->pdo->prepare(
                'SELECT `layout_id`
                FROM `layouts`
                WHERE `layout_code` = :layout_code'
            );

            $stmt->bindValue(':layout_code', $code, PDO::PARAM_STR);

            $stmt->execute();

            if($row = $stmt->fetch())
            {
                return (int)$row['layout_id'];
            }

            return null;
        }

        public function deleteLayout(int $layoutID) : bool
        {
            $stmt = $this->pdo->prepare(
                'UPDATE layouts 
                SET live = 0
                WHERE layout_id = :layoutID'
            );

            $stmt->bindValue(':layoutID', $layoutID, PDO::PARAM_INT);

            return $stmt->execute();


        }

        public function getLayoutByCode(string $code) : ?Entities\Layout
        {
            $stmt = $this->pdo->prepare(
                'SELECT `layout_id`, `layout_name`, `layout_code`, `page_id`, `active`, `live`
                FROM `layouts`
                WHERE `layout_code` = :layout_code'
            );

            $stmt->bindValue(':layout_code', $code, PDO::PARAM_STR);

            $stmt->execute();

            while($row = $stmt->fetch())
            {
                $layout = new Entities\Layout;

                $layout->setLayoutID((int)$row['layout_id']);
                $layout->setLayoutName($row['layout_name']);
                $layout->setLayoutCode($row['layout_code']);
                $layout->setPageID((int)$row['page_id']);
                $layout->setActive((int)$row['active']);
                $layout->setLive((int)$row['active']);

                return $layout;
            }

            return null;
        }
 
    }