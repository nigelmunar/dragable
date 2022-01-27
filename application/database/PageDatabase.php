<?php

    declare(strict_types = 1);

    require_once ROOT_PATH . 'application/entities/Page.php';

    class PageDatabase
    {
        private $pdo;

        public function __construct(\PDO $pdo)
        {
            $this->pdo = $pdo;

        }

        public function getPage(string $url) : ?\Entities\Page
        {
            $stmt = $this->pdo->prepare(
                'SELECT page_id, page_name, page_code, date_time_created
                FROM pages 
                WHERE url_page_name = :pageURL');

            $stmt->bindValue(':pageURL', $url, PDO::PARAM_STR);

            $stmt->execute();

            while($row = $stmt->fetch())
            {
                $page  = new \Entities\Page;

                $page->setPageID((int)$row['page_id']);
                $page->setPageName($row['page_name']);
                $page->setPageCode($row['date_time_created']);
                $page->setPageURL($url);

                return $page;
            }

            return null;
        }
    }