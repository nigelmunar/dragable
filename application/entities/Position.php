<?php 

    declare(strict_types=1);

    namespace Entities;

    class Position
    {
        private $_xAxis; //int
        private $_yAxis; //int
        private $_widgetID; //int
        private $_widgetCode; //string
        private $_widgetType; //int

        public function getXAxis() : int
        {
            return $this->_xAxis;
        }

        public function setXAxis(int $value) : void
        {
            $this->_xAxis = $value;
        }

        public function getYAxis() : int
        {
            return $this->_yAxis;
        }

        public function setYAxis(int $value) : void
        {
            $this->_yAxis = $value;
        }

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
            return $this->_content;
        }

        public function setName(string $value) : void
        {
            $this->_content = $value;
        }

        public function getWidgetID() : int
        {
            return $this->_widgetID;
        }

        public function setWidgetID(int $value) : void
        {
            $this->_widgetID = $value;
        }

        public function getWidgetCode() : string
        {
            return $this->_widgetCode;
        }

        public function setWidgetCode(string $value) : void
        {
            $this->_widgetCode = $value;
        }

        public function getWidgetType() : int
        {
            return $this->_widgetType;
        }

        public function setWidgetType(int $value) : void
        {
            $this->_widgetType = $value;
        }

    }