<?php
require_once dirname(__DIR__) . "/../PHP/utils.php";
require_once dirname(__DIR__) . "/Model/appuntamenti-calendario.php";
use function Model\getAppTickets;

$mese = isset($_GET["mese"]) ? (int)$_GET["mese"] : date("n");
$anno = isset($_GET["anno"]) ? (int)$_GET["anno"] : date("Y");

// Normalizzazione dei parametri di anno e mese
$ts = mktime(0, 0, 0, $mese, 1, $anno);
$mese = date("n", $ts);
$anno = date("Y", $ts);
$giorniMese = date("t", $ts);

$risDB = getAppTickets($mese, $anno);

$nomiMesi = [
    1 => "Gennaio",
    2 => "Febbraio",
    3 => "Marzo",
    4 => "Aprile",
    5 => "Maggio",
    6 => "Giugno",
    7 => "Luglio",
    8 => "Agosto",
    9 => "Settembre",
    10 => "Ottobre",
    11 => "Novembre",
    12 => "Dicembre"
];

$nomiSett = [
    1 => "Lunedì",
    2 => "Martedì",
    3 => "Mercoledì",
    4 => "Giovedì",
    5 => "Venerdì",
    6 => "Sabato",
    7 => "Domenica"
];

// Navigazione per ciascun mese
$prevMese = date("n", mktime(0, 0, 0, $mese - 1, 1, $anno));
$prevAnno = date("Y", mktime(0, 0, 0, $mese - 1, 1, $anno));
$nextMese = date("n", mktime(0, 0, 0, $mese + 1, 1, $anno));
$nextAnno = date("Y", mktime(0, 0, 0, $mese + 1, 1, $anno));

// Link per la navigazione del calendario
$linkPrev = PROJECT_ROOT . "/PHP/Controller/calendario.php?mese=$prevMese&anno=$prevAnno";
$linkNext = PROJECT_ROOT . "/PHP/Controller/calendario.php?mese=$nextMese&anno=$nextAnno";

$settimane = [];
$numSett = 1;

for ($giorno = 1; $giorno <= $giorniMese; $giorno++) {
    $tsGiorno = mktime(0, 0, 0, $mese, $giorno, $anno);
    $nomeGiorno = $nomiSett[(date("N", $tsGiorno))];

    $htmlEv = "";

    if (date("N", $tsGiorno) != 7) {
        if (isset($risDB[$giorno])) {
            foreach ($risDB[$giorno] as $evento) {
                $htmlEv .=
                "<div class='cnt-adozione'>
                    <div class='linea'></div>
                    <p class='orario'>13:30</p>
                    <div class='info'>
                        <p>Appuntamento per adottare \"Willy\"</p>
                        <p>Sig./ra Mario Rossi</p>
                    </div>

                    <div class='btn-gruppo'>
                        <button class='btn-popup-app' title='Modifica appuntamento' data-nome='Mario Rossi' data-ora='13:30' data-data='2025-12-01'></button>
                        <button class='btn-elimina-app' title='Elimina appuntamento' data-nome='Mario Rossi' data-ora='13:30' data-data='01/12/2025'></button>
                    </div>
                </div>";
            }
        }

        else {
            $htmlEv = "<p>Nessun appuntamento per questa giornata.</p>";
        }

        $giornoPad = str_pad($giorno, 2, "0", STR_PAD_LEFT);

        $htmlGiorno = "<p class='giornata'>$nomeGiorno - $giornoPad</p> $htmlEv";

        if (!isset($settimane[$numSett])) { $settimane[$numSett] = ""; }
        $settimane[$numSett] .= $htmlGiorno;
    }

    if (date("N", $tsGiorno) == 7 && $giorno < $giorniMese) { $numSett++; }
}

$htmlBtns = "";
$htmlContent = "";
$count = 1;

foreach ($settimane as $num => $contenuto) {
    $activeBtn = ($count === 1) ? "active" : "";
    $activeCont = ($count === 1) ? "" : "hidden";

    $htmlBtns .= "<button class='btn-toggle $activeBtn' data-target='sett-$num' title='Settimana $count'>Settimana $count</button>";
    $htmlContent .= "<section id='sett-$num' class='$activeCont'>$contenuto</section>";

    $count++;
}

$dati = [
    "{{current-page}}" => "Calendario",
    "{{page-keywords}}" => "",
    "{{current-js}}" => "calendario.js",
    "{{mese-anno}}" => "$nomiMesi[$mese] $anno",
    "{{link-prev}}" => $linkPrev,
    "{{link-next}}" => $linkNext,
    "{{btns-sett}}" => $htmlBtns,
    "{{calendario-appuntamenti}}" => $htmlContent
];

echo buildPage("calendario.html", $dati);
?>