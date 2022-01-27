<?php

    declare(strict_types=1);


    require_once ROOT_PATH . 'application/entities/Crypto.php';

    class CryptoDatabase
    {
        private $pdo;
        
        public function __construct(\PDO $pdo)
        {
            $this->pdo = $pdo;
        }

        function insertData(object $data) : void
        {
            foreach($data->data as $coin)
            {
                $stmt = $this->pdo->prepare(
                    'INSERT INTO `cryptocurrency`(`name`, `price`, `cryptocurrency_code`, `symbol`, `slug`)
                    VALUES (:name, :price, :cryptoCode, :symbol, :slug);
                    )');

                $stmt->bindValue(':name', $coin->name);

                $stmt->bindValue(':price', $coin->quote->USD->price);

                $stmt->bindValue(':cryptoCode', \Ramsey\Uuid\Uuid::uuid4());

                $stmt->bindValue(':symbol', $coin->symbol);

                $stmt->bindValue(':slug', $coin->slug);

                $stmt->execute();

            }
        }

        public function getCryptocurrencies() : array
        {
            $stmt = $this->pdo->prepare(
                'SELECT `cryptocurrency_id`, `name`, `symbol`, `slug`, `cryptocurrency_code`, `price`
                FROM `cryptocurrency`');

            $stmt->execute();

            $crypto = array();

            while($row = $stmt->fetch())
            {
                $cryptocurrency = new \Entities\Crypto;
                $cryptocurrency->setID((int)$row['cryptocurrency_id']);
                $cryptocurrency->setName($row['name']);
                $cryptocurrency->setSymbol($row['slug']);
                $cryptocurrency->setSlug($row['slug']);
                $cryptocurrency->setCryptoCode($row['cryptocurrency_code']);
                $cryptocurrency->setPrice($row['price']);

                array_push($crypto, $cryptocurrency);
            }

            return $crypto;
        }


        public function getCryptoByCode(string $code) : Entities\Crypto
        {
            $stmt = $this->pdo->prepare(
                'SELECT `cryptocurrency_id`, `name`, `symbol`, `slug`, `price`
                FROM `cryptocurrency`
                WHERE `cryptocurrency_code` = :crypto_code');

            $stmt->bindValue(':crypto_code', $code, PDO::PARAM_STR);

            $stmt->execute();

            while($row = $stmt->fetch())
            {
                $cryptocurrency = new \Entities\Crypto;
                $cryptocurrency->setID((int)$row['cryptocurrency_id']);
                $cryptocurrency->setName($row['name']);
                $cryptocurrency->setSymbol($row['symbol']);
                $cryptocurrency->setSlug($row['slug']);
                $cryptocurrency->setPrice($row['price']);

                return $cryptocurrency;

            }

            return null;
        }

        public function addCryptoWidget(int $cryptoID, int $widgetID) : bool
        {
            $stmt = $this->pdo->prepare(
                'INSERT INTO `crypto_widgets` (`cryptocurrency_id`, `widget_id`)
                VALUES (:cryptoID, :widgetID)
                ');
            
            $stmt->bindValue(':cryptoID', $cryptoID, PDO::PARAM_INT);
            
            $stmt->bindValue(':widgetID', $widgetID, PDO::PARAM_INT);

            return $stmt->execute();


        }
        


    }