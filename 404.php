<?php
require_once __DIR__ . "/PHP/utils.php";

http_response_code(404);

$dati = [
    '{{current-page}}' => 'Errore 404',
    '{{page-description}}' => 'Errore 404. La pagina richiesta non è disponibile. Visita Amici per Sempre Rifugio Padova per conoscere cani e gatti in adozione.',
    '{{page-keywords}}' => 'amici per sempre, errore, pagina non trovata, 404',
    '{{current-js}}' => '',
];

echo buildPage("404.html", $dati);
?>