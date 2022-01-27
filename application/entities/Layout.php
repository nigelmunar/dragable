<?php 

    declare(strict_types=1);

    namespace Entities;

    class Layout
    {
        private $_layoutID; //int
        private $_pageID; //int
        private $_layoutName; //string
        private $_layoutCode; //string
        private $_active; //int
        private $_live; //int

        public function getLayoutID() : int
        {
            return $this->_layoutID;
        }

        public function setLayoutID(int $value) : void
        {
            $this->_layoutID = $value;
        }


        public function getPageID() : int
        {
            return $this->_pageID;
        }

        public function setPageID(int $value) : void
        {
            $this->_pageID = $value;
        }


        public function getLayoutName() : string
        {
            return $this->_layoutName;
        }

        public function setLayoutName(string $value) : void
        {
            $this->_layoutName = $value;
        }


        public function getLayoutCode() : string
        {
            return $this->_layoutCode;
        }

        public function setLayoutCode(string $value) : void
        {
            $this->_layoutCode = $value;
        }


        public function getActive() : int
        {
            return $this->_active;
        }

        public function setActive(int $value) : void
        {
            $this->_active = $value;
        }

        public function getLive() : int
        {
            return $this->_live;
        }

        public function setLive(int $value) : void
        {
            $this->_live = $value;
        }




    }