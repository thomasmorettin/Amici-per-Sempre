<?php
require_once dirname(__DIR__) . "/../PHP/utils.php";
require_once dirname(__DIR__) . "/Model/appuntamenti-calendario.php";
use function Model\getAppuntamenti;

$mese = isset($_GET["mese"]) ? (int)$_GET["mese"] : date("n");
$anno = isset($_GET["anno"]) ? (int)$_GET["anno"] : date("Y");

// Normalizzazione dei parametri di anno e mese
$ts = mktime(0, 0, 0, $mese, 1, $anno);
$currentSett = 1;
$today = date("j");
$mese = date("n", $ts);
$anno = date("Y", $ts);
$giorniMese = date("t", $ts);

$risDB = getAppuntamenti($mese, $anno);

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

$currentData = date("Y-m-d");
$settimane = [];
$numSett = 1;

for ($giorno = 1; $giorno <= $giorniMese; $giorno++) {
    $tsGiorno = mktime(0, 0, 0, $mese, $giorno, $anno);
    $nomeGiorno = $nomiSett[(date("N", $tsGiorno))];

    $htmlEv = "";
    $htmlBtns = "";
    $htmlLinea = "<div class='linea'></div>";

    if (date("N", $tsGiorno) != 7) {
        if (isset($risDB[$giorno])) {
            foreach ($risDB[$giorno] as $evento) {
                $dataApp = "$anno-" . str_pad($mese, 2, "0", STR_PAD_LEFT) . "-" . str_pad($giorno, 2, "0", STR_PAD_LEFT);
                $dataDisplay = str_pad($giorno, 2, "0", STR_PAD_LEFT) . "/" . str_pad($mese, 2, "0", STR_PAD_LEFT) . "/$anno";

                if ($dataApp >= $currentData) {
                    $htmlBtns =
                    "<div class='btn-gruppo'>
                        <button class='btn-info'
                            title='Note aggiuntive'
                            data-info='{$evento['Info']}'
                            data-nome='{$evento['NomeProprietario']} {$evento['CognomeProprietario']}'>
                        </button>
                        <button class='btn-popup-app'
                            title='Modifica appuntamento'
                            data-id='{$evento['ID']}'
                            data-nome='{$evento['NomeProprietario']} {$evento['CognomeProprietario']}'
                            data-ora='{$evento['Ora']}'
                            data-data='{$dataApp}'>
                        </button>
                        <button class='btn-elimina-app'
                            title='Elimina appuntamento'
                            data-id='{$evento['ID']}'
                            data-nome='{$evento['NomeProprietario']} {$evento['CognomeProprietario']}'
                            data-ora='{$evento['Ora']}'
                            data-data='{$dataApp}'
                            data-data-display='{$dataDisplay}'>
                        </button>
                    </div>";
                } else { $htmlLinea = ""; }

                if ($evento["Tipo"] === "Ticket") {
                    $htmlEv .=
                    "<li class='cnt-adozione'>
                        {$htmlLinea}
                        <p class='orario'>{$evento['Ora']}</p>
                        <div class='info'>
                            <p>Appuntamento per adottare \"{$evento['NomeAnimale']}\"</p>
                            <p>Sig./ra {$evento['NomeProprietario']} {$evento['CognomeProprietario']}</p>
                        </div>

                        {$htmlBtns}
                    </li>";
                }

                elseif ($evento["Tipo"] === "Request") {
                    $htmlEv .=
                    "<li class='cnt-presa-adozione' id='g{$giorno}'>
                        {$htmlLinea}
                        <p class='orario'>{$evento['Ora']}</p>
                        <div class='info'>
                            <p>Appuntamento per valutare adozione per razza \"{$evento['Razza']}\"</p>
                            <p>Sig./ra {$evento['NomeProprietario']} {$evento['CognomeProprietario']}</p>
                        </div>

                        {$htmlBtns}
                    </li>";
                }
            }
        }

        else {
            $htmlEv = "<p>Nessun appuntamento per questa giornata.</p>";
        }

        if ($giorno == $today && $mese == date("n") && $anno == date("Y")) {
            $currentSett = $numSett;
        }

        $giornoPad = str_pad($giorno, 2, "0", STR_PAD_LEFT);

        $htmlGiorno = "<li id='g{$giorno}'><p class='giornata'>$nomeGiorno $giornoPad</p><ol>$htmlEv</ol></li>";

        if (!isset($settimane[$numSett])) { $settimane[$numSett] = ""; }
        $settimane[$numSett] .= $htmlGiorno;
    }

    if (date("N", $tsGiorno) == 7 && $giorno < $giorniMese && $giorno > 1) { $numSett++; }
}

$htmlBtns = "";
$htmlContent = "";

foreach ($settimane as $num => $contenuto) {
    $isActive = ($num === $currentSett);

    $activeBtn = $isActive ? "active" : "";
    $activeCont = $isActive ? "" : "hidden";

    $htmlBtns .= "<button class='btn-toggle $activeBtn' data-target='sett-$num' title='Settimana $num'>Settimana $num</button>";
    $htmlContent .= "<ol id='sett-$num' class='lista-settimana $activeCont'>$contenuto</ol>";
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