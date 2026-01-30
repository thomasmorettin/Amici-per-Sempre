<?php
require_once dirname(__DIR__) . "/../PHP/utils.php";
require_once dirname(__DIR__) . "/Model/tickets.php";
require_once dirname(__DIR__) . "/Controller/pannello-filtri.php";
use function Model\getAnimaliEsterniTck;
use function Controller\getFiltriFromRequest;
use function Controller\renderPannelloFiltri;
use function Controller\renderPannelloControlloFiltri;

if (!is_logged_in()) {
    header("Location: " . PROJECT_ROOT . "/401.php"); 
}

$risDB = getAnimaliEsterniTck(getFiltriFromRequest());
$html = "";
$oggi = date("Y-m-d");

// Creazione del pannello dei filtri e quello di controllo
$pannello_filtri_html = renderPannelloFiltri(PROJECT_ROOT . '/PHP/Controller/richieste-inserimento-rifugio.php', ['Tipo', 'Dati persona', 'Razze', 'Sesso']);
$pannello_controllo_filtri_html = renderPannelloControlloFiltri(true);

if (!empty($risDB)) {

    foreach ($risDB as $id => $animale) {

        $richiestaRealFormat = (new DateTime($animale["dataRichiesta"]))->format("d/m/Y");

        if(!$animale["gestito"]) {
            $msgGestito = "<p class='status-da-gestire'>Da gestire</p>";
        } else {
            $msgGestito = "<p class='status-gestito'>Gestito</p>";
        }

        $html .=
        "
            <details class='dtl-animale'>
            <summary>
                <div>
                    <p class='info-richiesta-animale'>{$animale["infoAnimale"]["tipo"]} - {$animale["infoAnimale"]["razza"]}</p>
                    <p class='dettagli-animale'>{$animale["padrone"]["nome"]} {$animale["padrone"]["cognome"]}</p>
                    $msgGestito
                </div>

                <svg class='exp-freccia'>
                    <use href='{{root}}/Resources/icons.svg#arrow'></use>
                </svg>
            </summary>

            <div class='contenuto-nascosto'>
                <div>       <!-- Con il solo scopo di rendere più fluida la dissolvenza della scheda -->
                    <section class='sezione-padrone'>
                        <p>Info padrone</p>
                        <dl class='richiesta-inserimento-info'>
                            <dt>Nome completo:</dt>
                            <dd>{$animale["padrone"]["nome"]} {$animale["padrone"]["cognome"]}</dd>
                            <dt>E-mail:</dt>
                            <dd><a href='mailto:{$animale["padrone"]["email"]}'>{$animale["padrone"]["email"]}</a></dd>
                            <dt>Telefono:</dt>
                            <dd><a href='tel:{$animale["padrone"]["telefono"]}'>+39 {$animale["padrone"]["telefono"]}</a></dd>
                            <dt>Data richiesta:</dt>
                            <dd>{$richiestaRealFormat}</dd>";
                            
        
        if ($animale["gestito"]) {

            $gestitaRealFormat = (new DateTime($animale["data"]))->format("d/m/Y"); 

            $html .=
            "               <dt>Data appuntamento:</dt>
                            <dd>{$gestitaRealFormat}</dt>
                            <dt>Ora appuntamento:</dt>
                            <dd>{$animale["ora"]}</dt>";
        }

        $html .= 
        "
                        </dl>
                        <div class='spaziatore'></div>
                    </section>

                    <section>
                        <p>Info animale</p>
                        <dl class='richiesta-inserimento-info'>
                            <dt>Tipo:</dt>
                            <dd>{$animale["infoAnimale"]["tipo"]}</dd>
                            <dt>Razza:</dt>
                            <dd>{$animale["infoAnimale"]["razza"]}</dd>
                            <dt>Sesso:</dt>
                            <dd>{$animale["infoAnimale"]["sesso"]}</dd>
                            <dt>Età:</dt>
                            <dd>{$animale["infoAnimale"]["eta"]}</dd>
                            <dt>Peso</dt>
                            <dd>{$animale["infoAnimale"]["peso"]}</dd>
                        </dl>
                    </section>";

        if(!$animale["gestito"]) {
            $html .= 
            "   
                <div class='btn-gruppo-v'>
                    <button class='btn-info' title='Note aggiuntive' data-info='{$animale["infoAnimale"]["info"]}' data-nome='{$animale["padrone"]["nome"]} {$animale["padrone"]["cognome"]}'>
                        <svg>
                            <use href='{{root}}/Resources/icons.svg#info'></use>
                        </svg>
                    </button>
                    <button class='btn-popup-app' title='Prenota appuntamento' data-id='{$animale["infoAnimale"]["id"]}' data-nome='{$animale["padrone"]["nome"]} {$animale["padrone"]["cognome"]}'>
                        <svg>
                            <use href='{{root}}/Resources/icons.svg#calendario'></use>
                        </svg>
                    </button>
                    <button class='btn-elimina-app' title='Elimina richiesta' data-id='{$animale["infoAnimale"]["id"]}' data-nome='{$animale["padrone"]["nome"]} {$animale["padrone"]["cognome"]}'>
                        <svg>
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
                <div class='btn-gruppo-v'>
                    <button class='btn-info' title='Note aggiuntive' data-info='{$animale["infoAnimale"]["info"]}' data-nome='{$animale["padrone"]["nome"]}'>
                        <svg>
                            <use href='{{root}}/Resources/icons.svg#info'></use>
                        </svg>
                    </button>
                    <a class='go-calendario btn-link' href='{{root}}/amministrazione/calendario?mese={$mese}&anno={$anno}#g{$giorno}'>
                        <svg>
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
        </details>";
    }
}

else { $html = "<p class='center bold'>Nessun animale presente nel Rifugio.</p>"; }

$dati = [
    "{{current-page}}" => "Richieste Inserimento Rifugio",
    "{{page-keywords}}" => "",
    "{{current-js}}" => "richieste-inserimento-rifugio.js",
    "{{extra-js}}" => "pannello-filtri.js",
    "{{lista-animali}}" => $html,
    "{{pannello-filtri}}" => $pannello_filtri_html,
    "{{pannello-controllo-filtri}}" => $pannello_controllo_filtri_html
];

echo buildPage("richieste-inserimento-rifugio.html", $dati);
?>