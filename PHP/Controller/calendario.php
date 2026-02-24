<?php
require_once dirname(__DIR__) . "/../PHP/utils.php";
require_once dirname(__DIR__) . "/Model/appuntamenti-calendario.php";
require_once dirname(__DIR__) . "/../PHP/genera-dialogs.php";
use function Model\getAppuntamenti;
use function Controller\{getDialogInfo, getDialogAppuntamento, getDialogCanAppuntamento};

if (is_logged_in()) {
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
    $linkPrev = PROJECT_ROOT . "/amministrazione/calendario.php?mese=$prevMese&anno=$prevAnno";
    $linkNext = PROJECT_ROOT . "/amministrazione/calendario.php?mese=$nextMese&anno=$nextAnno";

    $currentData = date("Y-m-d");
    $settimane = [];
    $numSett = 1;

    for ($giorno = 1; $giorno <= $giorniMese; $giorno++) {
        $tsGiorno = mktime(0, 0, 0, $mese, $giorno, $anno);
        $nomeGiorno = $nomiSett[(date("N", $tsGiorno))];

        $htmlEv = "";
        $htmlBtns = "";
        $noApp = false;

        if (date("N", $tsGiorno) != 7) {
            $giornoPad = str_pad($giorno, 2, "0", STR_PAD_LEFT);

            if (isset($risDB[$giorno])) {
                foreach ($risDB[$giorno] as $evento) {
                    $dataApp = "$anno-" . str_pad($mese, 2, "0", STR_PAD_LEFT) . "-" . str_pad($giorno, 2, "0", STR_PAD_LEFT);
                    $dataDisplay = str_pad($giorno, 2, "0", STR_PAD_LEFT) . "/" . str_pad($mese, 2, "0", STR_PAD_LEFT) . "/$anno";

                    if ($dataApp >= $currentData) {
                        $htmlBtns =
                        "<div class='btn-gruppo-cal hidden'>
                            <button class='btn-info'
                                title='Note aggiuntive'
                                aria-label='note aggiuntive per {$evento['NomeProprietario']} {$evento['CognomeProprietario']} delle {$evento['Ora']}'
                                data-info='{$evento['Info']}'
                                data-nome='{$evento['NomeProprietario']} {$evento['CognomeProprietario']}'>
                                <svg aria-hidden='true'>
                                    <use href='{{root}}/Resources/icons.svg#info'></use>
                                </svg>
                            </button>
                            <button class='btn-popup-app'
                                title='Modifica appuntamento'
                                aria-label='modifica appuntamento di {$evento['NomeProprietario']} {$evento['CognomeProprietario']} delle {$evento['Ora']}'
                                data-id='{$evento['ID']}'
                                data-nome='{$evento['NomeProprietario']} {$evento['CognomeProprietario']}'
                                data-ora='{$evento['Ora']}'
                                data-data='{$dataApp}'>
                                <svg aria-hidden='true'>
                                    <use href='{{root}}/Resources/icons.svg#calendario'></use>
                                </svg>
                            </button>
                            <button class='btn-elimina-app'
                                title='Elimina appuntamento'
                                aria-label='elimina appuntamento di {$evento['NomeProprietario']} {$evento['CognomeProprietario']} delle {$evento['Ora']}'
                                data-id='{$evento['ID']}'
                                data-nome='{$evento['NomeProprietario']} {$evento['CognomeProprietario']}'
                                data-ora='{$evento['Ora']}'
                                data-data='{$dataApp}'
                                data-data-display='{$dataDisplay}'>
                                <svg aria-hidden='true'>
                                    <use href='{{root}}/Resources/icons.svg#delete'></use>
                                </svg>
                            </button>
                        </div>";
                    } else { $htmlLinea = ""; }

                    if ($evento["Tipo"] === "Ticket") {
                        $htmlEv .=
                        "<li class='cnt-adozione'>
                            <div class='linea'></div>

                            <div class='cnt-info-btns'>
                                <div class='appuntamento'>
                                    <p class='orario'><span class='hidden'>ore </span>{$evento['Ora']}</p>

                                    <div class='info'>
                                        <p>Appuntamento adozione \"{$evento['NomeAnimale']}\"</p>
                                        <p><abbr title='Signore o signora'>Sig./ra</abbr>&nbsp;{$evento['NomeProprietario']} {$evento['CognomeProprietario']}</p>
                                        <p class='note-hidden'><span class='bold'>Note:</span>&nbsp;{$evento['Info']}</p>
                                    </div>
                                </div>

                                {$htmlBtns}
                            </div>
                        </li>";
                    }

                    elseif ($evento["Tipo"] === "Request") {
                        $htmlEv .=
                        "<li class='cnt-presa-adozione'>
                            <div class='linea'></div>

                            <div class='cnt-info-btns'>
                                <div class='appuntamento'>
                                    <p class='orario'><span class='hidden'>ore </span>{$evento['Ora']}</p>

                                    <div class='info'>
                                        <p>Appuntamento per valutare ingresso in rifugio per razza \"<span lang='{$evento['LinguaRazza']}'>{$evento['Razza']}</span>\" ({$evento['TipoRazza']})</p>
                                        <p><abbr title='Signore o signora'>Sig./ra</abbr>&nbsp;{$evento['NomeProprietario']} {$evento['CognomeProprietario']}</p>
                                        <p class='note-hidden'><span class='bold'>Note:</span>&nbsp;{$evento['Info']}</p>
                                    </div>
                                </div>

                                {$htmlBtns}
                            </div>
                        </li>";
                    }
                }
            }

            else {
                $htmlEv = "<p>Nessun appuntamento per questa giornata.</p>";
                $noApp = true;
            }

            if ($giorno == $today && $mese == date("n") && $anno == date("Y")) {
                $currentSett = $numSett;
            }

            if ($noApp) { $htmlGiorno = "<li id='g{$giornoPad}'><p class='giornata'>$nomeGiorno $giornoPad</p>$htmlEv</li>"; }
            else { $htmlGiorno = "<li id='g{$giornoPad}'><p class='giornata'>$nomeGiorno $giornoPad</p><ol>$htmlEv</ol></li>"; }

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
        $ariaCurrent = $isActive ? "true" : "false";
        $activeCont = $isActive ? "" : "hidden";

        $htmlBtns .= "<button id='btn-sett-$num' class='btn-toggle hidden $activeBtn' data-target='sett-$num' title='Settimana $num' role='tab' aria-controls='sett-$num' aria-selected='$ariaCurrent'><span class='abbr'>Settimana</span>$num</button>";
        $htmlContent .= "<div id='sett-$num' aria-label='appuntamenti settimana $num' aria-labelledby='btn-sett-$num' role='tabpanel' class='lista-settimana $activeCont'><ol>$contenuto</ol></div>";
    }

    $dati = [
        "{{current-page}}" => "Calendario",
        "{{type-script}}" => "module",
        "{{page-description}}" => "Calendario appuntamenti per la visualizzazione/modifica di richieste al Rifugio Amici per Sempre Padova.",
        "{{page-keywords}}" => "Amici per Sempre,
                                amministrazione,
                                numero appuntamenti,
                                calendario appuntamenti,
                                appuntamenti adozione,
                                appuntamenti ingresso in rifugio",
        "{{current-js}}" => "calendario.js",
        "{{mese-anno}}" => "$nomiMesi[$mese] $anno",
        "{{link-prev}}" => $linkPrev,
        "{{mese-prec}}" => $nomiMesi[$prevMese] . " " . $prevAnno,
        "{{link-next}}" => $linkNext,
        "{{mese-succ}}" => $nomiMesi[$nextMese] . " " . $nextAnno,
        "{{btns-sett}}" => $htmlBtns,
        "{{calendario-appuntamenti}}" => $htmlContent,
        "{{dialogs}}" => (getDialogInfo()) . (getDialogAppuntamento("modifica-appuntamento", $_SERVER["REQUEST_URI"])) . (getDialogCanAppuntamento("elimina-appuntamento", "gestione-ticket"))
    ];

    echo buildPage("calendario.html", $dati);
} else { header("Location: " . PROJECT_ROOT . "/401.php"); }
?>