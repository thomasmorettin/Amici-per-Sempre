<?php

require_once dirname(__DIR__) . "/../PHP/utils.php";

ensure_session();


if( is_logged_in() ) {
    $dati = [
        "{{current-page}}" => "Logout",
        "{{current-js}}" => "login.js",
        '{{page-description}}' => 'Logout da area riservata agli amministratori di Amici per Sempre Rifugio Padova.',
        '{{page-keywords}}' => 'Amici per Sempre,
                        logout, 
                        esci'
    ];

    echo buildPage("logout.html", $dati);

    exit;
}

$dati = [
    "{{current-page}}" => "Login",
    "{{current-js}}" => "login.js",
    '{{page-description}}' => 'Login ad area riservata agli amministratori di Amici per Sempre Rifugio Padova.',
    '{{page-keywords}}' => 'Amici per Sempre,
                        login, 
                        accedi, 
                        area di accesso'
];

echo buildPage("login.html", $dati);

?>