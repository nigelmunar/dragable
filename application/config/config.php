<?php

    declare(strict_types=1);

    require_once __DIR__ . '/../vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();

    $dotenv->required('DB_HOST')->notEmpty();
    $dotenv->required('DB_SCHEMA')->notEmpty();
    $dotenv->required('DB_USER')->notEmpty();
    $dotenv->required('DB_PASSWORD')->notEmpty();
    $dotenv->required('DB_CHARSET')->notEmpty();
    $dotenv->required('ROOT_PATH')->notEmpty();
    $dotenv->required('MEMCACHED_HOST')->notEmpty();
    $dotenv->required('MEMCACHED_PORT')->notEmpty()->isInteger();
    $dotenv->required('MEMCACHED_ENABLED')->notEmpty()->isBoolean();

    define('DB_HOST', $_ENV['DB_HOST']);
    define('DB_SCHEMA', $_ENV['DB_SCHEMA']);
    define('DB_USER', $_ENV['DB_USER']);
    define('DB_PASSWORD', $_ENV['DB_PASSWORD']);
    define('DB_CHARSET', $_ENV['DB_CHARSET']);
    define('ROOT_PATH', $_ENV['ROOT_PATH']);

    define('MEMCACHED_HOST', $_ENV['MEMCACHED_HOST']);
    define('MEMCACHED_PORT', (int)$_ENV['MEMCACHED_PORT']);
    define('MEMCACHED_ENABLED', (strtolower($_ENV['MEMCACHED_ENABLED']) === 'true' ? true : false));

    require_once ROOT_PATH . 'application/logging/ErrorReporting.php';


    session_start();

    require_once ROOT_PATH . 'application/logging/Logger.php';
    require_once ROOT_PATH . 'application/tools/generalFunctions.php';

    $dbTimezone         = new \DateTimeZone('Europe/London');
    $displayTimezone    = new \DateTimeZone('Europe/London');

    $page               = 1;
    $maxPages           = 1;

    $siteURL            = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . (isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : '') . ((isset($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : '80') !== '443' && (isset($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : '80') !== '80' ? ':' . (isset($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : '80') : '') . '/';
    $scriptName         = strtolower(str_replace(ROOT_PATH . 'public_html/', '', (isset($_SERVER["SCRIPT_FILENAME"]) ? $_SERVER["SCRIPT_FILENAME"] : '')));

    $isMe           = (getUserIP() === '' || ((getUserIP() === '::1' || getUserIP() === '127.0.0.1' || getUserIP() === '') && strpos($siteURL, '.local') !== false));
    
    $styles             = [];
    $scripts            = [];
    
    $pageURL            = '';
    $nonPagedURL        = '';
    $canonicalURL       = '';

    $scriptKeys         = [];
    $styleKeys          = [];
    $mapsToLoad         = [];