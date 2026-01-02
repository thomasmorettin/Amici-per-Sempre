<?php
require_once dirname(__DIR__) . "/../PHP/utils.php";
require_once dirname(__DIR__) . "/Model/numeri-amministrazione.php";
use function Model\getNumApp;
use function Model\getNumAll;

$risAppDB = getNumApp();
$risAllDB = getNumAll();

$dati = [
    "{{current-page}}" => "Amministrazione",
    "{{page-keywords}}" => "",
    "{{current-js}}" => "amministrazione.js",
    "{{num-app}}" => isset($risAppDB["num-app"]) ? $risAppDB["num-app"] : 0,
    "{{num-tck}}" => isset($risAllDB["num-tck"]) ? $risAllDB["num-tck"] : 0,
    "{{num-req}}" => isset($risAllDB["num-req"]) ? $risAllDB["num-req"] : 0
];

echo buildPage("amministrazione.html", $dati);
?>