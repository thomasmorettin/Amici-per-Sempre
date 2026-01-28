<?php
require_once dirname(__DIR__) . "/../PHP/utils.php";
require_once dirname(__DIR__) . "/Model/tickets.php";
require_once dirname(__DIR__) . "/Controller/pannello-filtri.php";
use function Model\getAnimaliEsterniTck;
use function Controller\renderPannelloFiltri;
use function Controller\renderPannelloControlloFiltri;

$risDB = getAnimaliEsterniTck();
$html = "";
$oggi = date("Y-m-d");

// Creazione del pannello dei filtri e quello di controllo
$pannello_filtri_html = renderPannelloFiltri(PROJECT_ROOT . '/PHP/Controller/richieste-inserimento-rifugio.php', ['Tipo', 'Dati animale', 'Dati persona', 'Razze']);
$pannello_controllo_filtri_html = renderPannelloControlloFiltri(true);

if (!empty($risDB)) {
    /*
    usort($risDB, function($a, $b) {
        $countA = count($a["daGestire"]);
        $countB = count($b["daGestire"]);

        if ($countA != $countB) { return $countB <=> $countA; }

        return strcasecmp($a["info"]["nome"], $b["info"]["nome"]);
    });
    */

    foreach ($risDB as $id => $animale) {
        /*

        if (!empty($animale["daGestire"])) {
            // Ordinamento delle richieste da gestire (crescente)
            usort($animale["daGestire"], function($a, $b) {
                return $a["id"] <=> $b["id"];       // L'ID più vecchio corrisponde alla richiesta meno recente
            });

            // Ordinamento delle richieste gestite (decrescente, se di data passata spostata in fondo)
            usort($animale["gestite"], function($a, $b) use ($oggi) {
                $isOldA = ($a["data"] < $oggi);
                $isOldB = ($b["data"] < $oggi);

                if ($isOldA && !$isOldB) { return 1; }
                elseif (!$isOldA && isOldB) { return -1; }

                $dataOraA = $a["data"] . $a["ora"];
                $dataOraB = $b["data"] . $b["ora"];

                return strcmp($dataOraB, $dataOraA);
            });

            foreach ($animale["daGestire"] as $ticket) {
                $realFormat = (new DateTime($ticket["dataRich"]))->format("d/m/Y");

                $richDaGestire .=
                "<li>
                    <dl>
                        <dt id='nome-rich'>{$ticket["richiedente"]}</dt>
                        <dd>
                            <dl class='cliente-info'>
                                <dt>Data richiesta:</dt>
                                <dd>{$realFormat}</dd>
                                <dt>E-mail:</dt>
                                <dd><a href='mailto:{$ticket["emailRich"]}'>{$ticket["emailRich"]}</a></dd>
                                <dt>Tel:</dt>
                                <dd><a href='tel:{$ticket["telRich"]}'>{$ticket["telRich"]}</a></dd>
                            </dl>
                        </dd>
                    </dl>

                    <div class='btn-gruppo'>
                        <button class='btn-info' title='Note aggiuntive' data-info='{$ticket["info"]}' data-nome='{$ticket["richiedente"]}'></button>
                        <button class='btn-popup-app' title='Prenota appuntamento' data-id='{$ticket["id"]}' data-nome='{$ticket["richiedente"]}'></button>
                        <button class='btn-elimina-app' title='Elimina richiesta' data-id='{$ticket["id"]}' data-nome='{$ticket["richiedente"]}'></button>
                    </div>
                </li>";
            }
        }

        else { $richDaGestire = "<p>Non ci sono richieste da gestire.</p>"; }

        if (!empty($animale["gestite"])) {
            foreach ($animale["gestite"] as $ticket) {
                $realFormat = (new DateTime($ticket["data"]))->format("d/m/Y");
                $giorno = explode("-", $ticket["data"])[2];
                $mese = explode("-", $ticket["data"])[1];
                $anno = explode("-", $ticket["data"])[0];

                $richGestite .=
                "<li>
                    <dl>
                        <dt>{$ticket["richiedente"]}</dt>
                        <dd>
                            <dl class='cliente-info'>
                                <dt>Data appuntamento:</dt>
                                <dd>{$realFormat} - {$ticket["ora"]}</dd>
                                <dt>E-mail:</dt>
                                <dd><a href='mailto:{$ticket["emailRich"]}'>{$ticket["emailRich"]}</a></dd>
                                <dt>Tel:</dt>
                                <dd><a href='tel:{$ticket["telRich"]}'>{$ticket["telRich"]}</a></dd>
                            </dl>
                        </dd>
                    </dl>

                    <div class='btn-gruppo'>
                        <button class='btn-info' title='Note aggiuntive' data-info='{$ticket["info"]}' data-nome='{$ticket["richiedente"]}'></button>
                        <a class='go-calendario' href='{{root}}/PHP/Controller/calendario.php?mese={$mese}&anno={$anno}#g{$giorno}'>Calendario</a>
                    </div>
                </li>";
            }
        }

        else { $richGestite = "<p>Non ci sono richieste già gestite.</p>"; }

        */

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
                    <p>{$animale["padrone"]["nome"]} {$animale["padrone"]["cognome"]}</p>
                    $msgGestito
                </div>

                <div class='exp-freccia'></div>
            </summary>

            <div class='contenuto-nascosto'>
                <div>       <!-- Con il solo scopo di rendere più fluida la dissolvenza della scheda -->
                    <section class='sezione-padrone'>
                        <p>Info padrone</p>
                        <dl class='richiesta-inserimento-info'>
                            <dt>Nome completo:</dt>
                            <dd>{$animale["padrone"]["nome"]} {$animale["padrone"]["cognome"]}</dd>
                            <dt>Data richiesta:</dt>
                            <dd>{$animale["dataRichiesta"]}</dd>
                            <dt>E-mail:</dt>
                            <dd><a href='mailto:{$animale["padrone"]["email"]}'>{$animale["padrone"]["email"]}</a></dd>
                            <dt>Telefono:</dt>
                            <dd><a href='tel:{$animale["padrone"]["telefono"]}'>+39 {$animale["padrone"]["telefono"]}</a></dd>
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
                            <dt>Note:</dt>
                            <dd>{$animale["infoAnimale"]["info"]}</dd>
                        </dl>
                    </section>";

        if(!$animale["gestito"]) {
            $html .= 
            "   
                <menu class='btn-gruppo-v'>
                    <button class='btn-popup-app' title='Prenota appuntamento' data-id='{$animale["infoAnimale"]["id"]}' data-nome='{$animale["padrone"]["nome"]} {$animale["padrone"]["cognome"]}'></button>
                    <button class='btn-elimina-app' title='Elimina richiesta' data-id='{$animale["infoAnimale"]["id"]}' data-nome='{$animale["padrone"]["nome"]} {$animale["padrone"]["cognome"]}'></button>
                </menu>";
        } else {
            $realFormat = (new DateTime($animale["data"]))->format("d/m/Y");
            $giorno = explode("-", $animale["data"])[2];
            $mese = explode("-", $animale["data"])[1];
            $anno = explode("-", $animale["data"])[0];
            $html .=
            "   
                <menu class='btn-gruppo-v'>
                    <a class='go-calendario' href='{{root}}/amministrazione/calendario?mese={$mese}&anno={$anno}#g{$giorno}'>Calendario</a>
                </menu>";
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