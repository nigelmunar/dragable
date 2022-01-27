<?php

    declare(strict_types=1);

    $breadcrumb         = [];
    $pageTitle          = '';
    $subNavName         = '';
    $pageTitle          = 'Dragable';
    $metaDescription    = 'Dragable.';

    switch($scriptName)
    {
        case 'index.php';
            $navName    = 'HOME';

            break;
        case 'edit-layout.php';
            $breadcrumb = [['LAYOUT', 'edit-layout.html']];

            $navName    = 'LAYOUTS';

            break;
        
    }