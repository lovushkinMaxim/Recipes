<?php

if (!isset($_SERVER) || empty($_SERVER["DOCUMENT_ROOT"])) {
    $_SERVER["DOCUMENT_ROOT"] = __DIR__ . '/../..';
}

require_once('autoloader.php');
$loader = new Psr4AutoloaderClass();
$loader->register();
$loader->addNamespace('App', $_SERVER["DOCUMENT_ROOT"] . "/local/classes/App/");
$loader->addNamespace('BD', $_SERVER["DOCUMENT_ROOT"] . "/local/classes/BD/");
