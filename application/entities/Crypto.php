<?php

    declare(strict_types=1);

    namespace Entities;

    class Crypto
    {
        private $_id; //int
        private $_name; //string
        private $_symbol; //string
        private $_slug; //string
        private $_cryptoCode; //string
        private $_price; //string


        public function getID() : int
        {
            return $this->_id;
        }

        public function setID(int $value) : void
        {
            $this->_id = $value;
        }
        
        public function getName() : string
        {
            return $this->_name;
        }

        public function setName(string $value) : void
        {
            $this->_name = $value;
        }


        public function getSymbol() : string
        {
            return $this->_symbol;
        }

        public function setSymbol(string $value) : void
        {
            $this->_symbol = $value;
        }


        public function getSlug() : string
        {
            return $this->_slug;
        }

        public function setSlug(string $value) : void
        {
            $this->_slug = $value;
        }


        public function getCryptoCode() : string
        {
            return $this->_cryptoCode;
        }

        public function setCryptoCode(string $value) : void
        {
            $this->_cryptoCode = $value;
        }


        public function getPrice() : string
        {
            return $this->_price;
        }

        public function setPrice(string $value) : void
        {
            $this->_price = $value;
        }


    }