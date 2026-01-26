<?php
require_once __DIR__ . "/PHP/utils.php";

http_response_code(401);

$dati = [
    '{{current-page}}' => 'Errore 401',
    '{{page-description}}' => "",
    '{{page-keywords}}' => 'amici per sempre, errore, 401, autenticazione richiesta, accesso negato, login',
    '{{current-js}}' => '',
];

echo buildPage("401.html", $dati);
?>