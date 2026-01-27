<?php
require_once dirname(__DIR__) . "/../PHP/utils.php";
require_once dirname(__DIR__) . "/Model/numeri-amministrazione.php";
use function Model\getNumApp;
use function Model\getNumAll;

if (is_logged_in()) {
    $risAppDB = getNumApp();
    $risAllDB = getNumAll();

    $dati = [
        "{{current-page}}" => "Amministrazione",
        "{{page-keywords}}" => "",
        "{{current-js}}" => "amministrazione.js",
        "{{num-app}}" => isset($risAppDB["NumApp"]) ? $risAppDB["NumApp"] : 0,
        "{{num-tck}}" => isset($risAllDB["NumTck"]) ? $risAllDB["NumTck"] : 0,
        "{{num-req}}" => isset($risAllDB["NumReq"]) ? $risAllDB["NumReq"] : 0
    ];

    echo buildPage("amministrazione.html", $dati);
} else { header("Location: " . PROJECT_ROOT . "/401.php"); }
?>