<?php 

declare(strict_types=1);

namespace Entities;

class Page
{
    private $_pageID; //int
    private $_pageCode; //string
    private $_pageName; //string
    private $_pageURL; //string
    private $_slug; //?string
    private $_dateTimeCreated; //string

    public function getPageID() : int
    {
        return $this->_pageID;
    }

    public function setPageID(int $value) : void
    {
        $this->_pageID = $value;
    }


    public function getPageCode() : string
    {
        return $this->_pageCode;
    }

    public function setPageCode(string $value) : void
    {
        $this->_pageCode = $value;
    }


    public function getPageName() : string
    {
        return $this->_pageName;
    }

    public function setPageName(string $value) : void
    {
        $this->_pageName = $value;
    }


    public function getPageURL() : string
    {
        return $this->_pageURL;
    }

    public function setPageURL(string $value) : void
    {
        $this->_pageURL = $value;
    }


    public function getSlug() : ?string
    {
        return $this->_slug;
    }

    public function setSlug(?string $value) : void
    {
        $this->_slug = $value;
    }


    public function getDateTimeCreated() : string
    {
        return $this->_dateTimeCreated;
    }

    public function setDateTimeCreated(string $value) : void
    {
        $this->_dateTimeCreated = $value;
    }


}