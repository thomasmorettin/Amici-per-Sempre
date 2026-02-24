<?php
require_once dirname(__DIR__) . "/../PHP/utils.php";
require_once dirname(__DIR__) . "/Model/tickets.php";
require_once dirname(__DIR__) . "/Controller/pannello-filtri.php";
require_once dirname(__DIR__) . "/../PHP/genera-dialogs.php";
use function Model\getAnimaliEsterniTck;
use function Controller\getFiltriFromRequest;
use function Controller\renderPannelloFiltri;
use function Controller\renderPannelloControlloFiltri;
use function Controller\{getDialogInfo, getDialogAppuntamento, getDialogCanRichiesta};

if (!is_logged_in()) {
    header("Location: " . PROJECT_ROOT . "/401.php"); 
}

$risDB = getAnimaliEsterniTck(getFiltriFromRequest());
$html = "";
$oggi = date("Y-m-d");

// Creazione del pannello dei filtri e quello di controllo
$pannello_filtri_html = renderPannelloFiltri(PROJECT_ROOT . '/richieste-inserimento-rifugio.php', ['Tipo', 'Dati persona', 'Razze', 'Sesso']);
$pannello_controllo_filtri_html = renderPannelloControlloFiltri(true);

if (!empty($risDB)) {

    foreach ($risDB as $id => $animale) {

        $richiestaRealFormat = (new DateTime($animale["dataRichiesta"]))->format("d/m/Y");

        if(!$animale["gestito"]) {
            $msgGestito = "<span class='status-da-gestire'>Da gestire</span>";
        } else {
            $msgGestito = "<span class='status-gestito'>Gestito</span>";
        }

        $html .=
        "   <li>
                <details class='dtl-animale'>
                    <summary>
                        <span class='info-animale'>
                            <span>
                                <span class='info-richiesta-animale'>{$animale["infoAnimale"]["tipo"]} - <span lang='{$animale["infoAnimale"]["linguaRazza"]}'>{$animale["infoAnimale"]["razza"]}</span></span>
                                <span class='dettagli-padrone'>{$animale["padrone"]["nome"]} {$animale["padrone"]["cognome"]}</span>
                                $msgGestito
                            </span>
                        </span>

                        <svg class='exp-freccia'>
                            <use href='{{root}}/Resources/icons.svg#arrow'></use>
                        </svg>
                    </summary>

            <div class='contenuto-nascosto'>
                <div>       <!-- Con il solo scopo di rendere più fluida la dissolvenza della scheda -->
                    <section>
                        <p>Info padrone:</p>
                        <dl class='richiesta-inserimento-info'>
                            <dt>Nome:</dt>
                            <dd>{$animale["padrone"]["nome"]} {$animale["padrone"]["cognome"]}</dd>
                            <dt lang='en'>E-mail:</dt>
                            <dd><a href='mailto:{$animale["padrone"]["email"]}'>{$animale["padrone"]["email"]}</a></dd>
                            <dt><abbr title='Telefono'>Tel</abbr>:</dt>
                            <dd><a href='tel:{$animale["padrone"]["telefono"]}'>+39 {$animale["padrone"]["telefono"]}</a></dd>
                            <dt>Data <abbr title='Richiesta'>ric</abbr>:</dt>
                            <dd>{$richiestaRealFormat}</dd>";
                            
        
        if ($animale["gestito"]) {

            $gestitaRealFormat = (new DateTime($animale["data"]))->format("d/m/Y"); 

            $html .=
            "               <dt>Data <abbr title='Appuntamento'>app</abbr>:</dt>
                            <dd>{$gestitaRealFormat}</dd>
                            <dt>Ora <abbr title='Appuntamento'>app</abbr>:</dt>
                            <dd>{$animale["ora"]}</dd>";
        }

        $html .= 
        "
                        </dl>
                    </section>

                    <section>
                        <p>Info animale:</p>
                        <dl class='richiesta-inserimento-info'>
                            <dt>Tipo:</dt>
                            <dd>{$animale["infoAnimale"]["tipo"]}</dd>
                            <dt>Razza:</dt>
                            <dd><span lang='{$animale["infoAnimale"]["linguaRazza"]}'>{$animale["infoAnimale"]["razza"]}</span></dd>
                            <dt>Sesso:</dt>
                            <dd>{$animale["infoAnimale"]["sesso"]}</dd>
                            <dt>Età:</dt>
                            <dd>{$animale["infoAnimale"]["eta"]}</dd>
                            <dt>Peso:</dt>
                            <dd>{$animale["infoAnimale"]["peso"]}</dd>
                            <dt class='note-hidden'>Note:</dt>
                            <dd class='note-hidden'>{$animale["infoAnimale"]["info"]}</dd>
                        </dl>
                    </section>";

        if(!$animale["gestito"]) {
            $html .= 
            "   
                    <div class='btn-gruppo-v hidden'>
                        <button class='btn-info' title='Note aggiuntive' data-info='{$animale["infoAnimale"]["info"]}' data-nome='{$animale["padrone"]["nome"]} {$animale["padrone"]["cognome"]}'>
                            <svg aria-hidden='true'>
                                <use href='{{root}}/Resources/icons.svg#info'></use>
                            </svg>
                        </button>
                        <button class='btn-popup-app' title='Prenota appuntamento' data-id='{$animale["infoAnimale"]["id"]}' data-nome='{$animale["padrone"]["nome"]} {$animale["padrone"]["cognome"]}'>
                            <svg aria-hidden='true'>
                                <use href='{{root}}/Resources/icons.svg#calendario'></use>
                            </svg>
                        </button>
                        <button class='btn-elimina-app' title='Elimina richiesta' data-id='{$animale["infoAnimale"]["id"]}' data-nome='{$animale["padrone"]["nome"]} {$animale["padrone"]["cognome"]}'>
                            <svg aria-hidden='true'>
                                <use href='{{root}}/Resources/icons.svg#delete'></use>
                            </svg>
                        </button>
                    </div>";
        } else {
            $realFormat = (new DateTime($animale["data"]))->format("d/m/Y");
            $giorno = explode("-", $animale["data"])[2];
            $mese = explode("-", $animale["data"])[1];
            $anno = explode("-", $animale["data"])[0];
            $html .=
            "   
                    <div class='btn-gruppo-v hidden'>
                        <button class='btn-info' title='Note aggiuntive' data-info='{$animale["infoAnimale"]["info"]}' data-nome='{$animale["padrone"]["nome"]}'>
                            <svg aria-hidden='true'>
                                <use href='{{root}}/Resources/icons.svg#info'></use>
                            </svg>
                        </button>
                        <a class='go-calendario btn-link' href='{{root}}/amministrazione/calendario?mese={$mese}&anno={$anno}#g{$giorno}'>
                            <svg aria-hidden='true'>
                                <use href='{{root}}/Resources/icons.svg#forward'></use>
                            </svg>
                            <span>Calendario</span>
                        </a>
                    </div>";
        }

        $html .=
        "
                    </div>
                </div>
            </details>
        </li>";
    }
}

else { $html = "<p class='no-richieste'>Nessun animale presente nel Rifugio.</p>"; }

$dati = [
    "{{current-page}}" => "Richieste Inserimento Rifugio",
    "{{page-description}}" => "Gestione delle richieste di portare in adozione al Rifugio Amici per Sempre Padova.",
    "{{page-keywords}}" => "Amici per Sempre,
                            amministrazione,
                            richieste di inserimento,
                            gestione richieste di inserimento al rifugio,
                            filtri ricerca richieste di inserimento al rifugio",
    "{{type-script}}" => "module",
    "{{current-js}}" => "richieste-inserimento-rifugio.js",
    "{{extra-js}}" => "pannello-filtri.js",
    "{{lista-animali}}" => $html,
    "{{pannello-filtri}}" => $pannello_filtri_html,
    "{{pannello-controllo-filtri}}" => $pannello_controllo_filtri_html,
    "{{dialogs}}" => (getDialogInfo()) . (getDialogAppuntamento("aggiungi-appuntamento", $_SERVER["REQUEST_URI"])) . (getDialogCanRichiesta("elimina-richiesta", $_SERVER["REQUEST_URI"]))
];

echo buildPage("richieste-inserimento-rifugio.html", $dati);
?>