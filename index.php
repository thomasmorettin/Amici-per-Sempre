<?php
require_once __DIR__ . "/PHP/utils.php";

$dati = [
    "{{current-page}}" => "Home",
    "{{page-description}}" => "Rifugio Amici per Sempre a Padova: adozioni di cani e gatti. Scopri i nostri animali, o porta il tuo, in cerca di una nuova casa.",
    "{{page-keywords}}" => "rifugio amici per sempre,
                            rifugio di volontariato animali,
                            adozione cani padova,
                            adozione gatti padova,
                            portare cane in adozione,
                            portare gatto in adozione,
                            cessione animali domestici,
                            addestratori enci padova,
                            volontariato animali padova,
                            veterinaria padova,
                            via trieste, 63, padova,
                            91022233344",
    "{{extra-js}}" => "faq-schema.js, org-schema.js",
    "{{current-js}}" => "index.js"
];

echo buildPage("index.html", $dati);
?>