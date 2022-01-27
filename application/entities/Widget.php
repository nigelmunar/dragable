<?php

    declare(strict_types=1);

    namespace Entities;

    class Widget
    {
        private $_widgetID; //int
        private $_widgetContent; // string
        private $_widgetType; // int
        private $_widgetCode; //string
        private $_crypto; //?string
        private $_xPos; //int
        private $_yPos; //int
        private $_width; //int
        private $_height; //int



        public function getWidgetID() : int
        {
            return $this->_widgetID;
        }

        public function setWidgetID(int $value) : void
        {
            $this->_widgetID = $value;
        }


        public function getWidgetContent() : string
        {
            return $this->_widgetContent;
        }

        public function setWidgetContent(string $value) : void
        {
            $this->_widgetContent = $value;
        }


        public function getWidgetType() : int
        {
            return $this->_widgetType;
        }

        public function setWidgetType(int $value) : void
        {
            $this->_widgetType = $value;
        }


        public function getWidgetCode() : string
        {
            return $this->_widgetCode;
        }

        public function setWidgetCode(string $value) : void
        {
            $this->_widgetCode = $value;
        }

        public function getCrypto() : ?string
        {
            return $this->_crypto;
        }

        public function setCrypto(string $value) : void
        {
            $this->_crypto = $value;
        }


        public function getXPos() : int
        {
            return $this->_xPos;
        }

        public function setXPos(int $value) : void
        {
            $this->_xPos = $value;
        }


        public function getYPos() : int
        {
            return $this->_yPos;
        }

        public function setYPos(int $value) : void
        {
            $this->_yPos = $value;
        }


        public function getWidth() : int
        {
            return $this->_width;
        }

        public function setWidth(int $value) : void
        {
            $this->_width = $value;
        }


        public function getHeight() : int
        {
            return $this->_height;
        }

        public function setHeight(int $value) : void
        {
            $this->_height = $value;
        }





    }