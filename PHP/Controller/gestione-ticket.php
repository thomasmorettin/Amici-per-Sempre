<?php
require_once dirname(__DIR__) . "/../PHP/utils.php";
require_once dirname(__DIR__) . "/Model/tickets.php";
require_once dirname(__DIR__) . "/Controller/pannello-filtri.php";
require_once dirname(__DIR__) . "/../PHP/genera-dialogs.php";
use function Model\getAnimaliTck;
use function Controller\renderPannelloFiltri;
use function Controller\renderPannelloControlloFiltri;
use function Controller\getFiltriFromRequest;
use function Controller\{getDialogInfo, getDialogAppuntamento, getDialogCanRichiesta};

if (is_logged_in()) {
    $risDB = getAnimaliTck(getFiltriFromRequest());
    $html = "";
    $oggi = date("Y-m-d");

    if (!empty($risDB)) {
        usort($risDB, function($a, $b) {
            $countA = count($a["daGestire"]);
            $countB = count($b["daGestire"]);

            if ($countA != $countB) { return $countB <=> $countA; }

            return strcasecmp($a["infoAnimale"]["nome"], $b["infoAnimale"]["nome"]);
        });

        foreach ($risDB as $id => $animale) {
            $richDaGestire = "";
            $richGestite = "";
            $numRich = (string)count($animale["daGestire"]);

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
                    elseif (!$isOldA && $isOldB) { return -1; }

                    $dataOraA = $a["data"] . $a["ora"];
                    $dataOraB = $b["data"] . $b["ora"];

                    return strcmp($dataOraB, $dataOraA);
                });

                foreach ($animale["daGestire"] as $ticket) {
                    $realFormat = (new DateTime($ticket["dataRich"]))->format("d/m/Y");

                    $richDaGestire .=
                    "<li>
                        <dl>
                            <dt>{$ticket["richiedente"]}</dt>
                            <dd>
                                <dl class='cliente-info'>
                                    <dt>Data <abbr title='Richiesta'>ric</abbr>:</dt>
                                    <dd>{$realFormat}</dd>
                                    <dt lang='en'>E-mail:</dt>
                                    <dd><a href='mailto:{$ticket["emailRich"]}'>{$ticket["emailRich"]}</a></dd>
                                    <dt><abbr title='Telefono'>Tel</abbr>:</dt>
                                    <dd><a href='tel:{$ticket["telRich"]}'>+39 {$ticket["telRich"]}</a></dd>
                                    <dt class='note-hidden'>Note:</dt>
                                    <dd class='note-hidden'>{$ticket["info"]}</dd>
                                </dl>
                            </dd>
                        </dl>

                        <div class='btn-gruppo hidden'>
                            <button class='btn-info' title='Note aggiuntive' data-info='{$ticket["info"]}' data-nome='{$ticket["richiedente"]}' aria-label='note aggiuntive per {$ticket["richiedente"]}'>
                                <svg aria-hidden='true'>
                                    <use href='{{root}}/Resources/icons.svg#info'></use>
                                </svg>
                            </button>
                            <button class='btn-popup-app' title='Prenota appuntamento' data-id='{$ticket["id"]}' data-nome='{$ticket["richiedente"]}' aria-label='prenota appuntamento con {$ticket["richiedente"]}'>
                                <svg aria-hidden='true'>
                                    <use href='{{root}}/Resources/icons.svg#calendario'></use>
                                </svg>
                            </button>
                            <button class='btn-elimina-app' title='Elimina richiesta' data-id='{$ticket["id"]}' data-nome='{$ticket["richiedente"]}' aria-label='elimina richiesta di {$ticket["richiedente"]}'>
                                <svg aria-hidden='true'>
                                    <use href='{{root}}/Resources/icons.svg#delete'></use>
                                </svg>
                            </button>
                        </div>
                    </li>";
                }
            }

            else { $richDaGestire = "<li><p>Non ci sono richieste da gestire.</p></li>"; }

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
                                    <dt>Data <abbr title='Appuntamento'>app</abbr>:</dt>
                                    <dd>{$realFormat} - {$ticket["ora"]}</dd>
                                    <dt lang='en'>E-mail:</dt>
                                    <dd><a href='mailto:{$ticket["emailRich"]}'>{$ticket["emailRich"]}</a></dd>
                                    <dt><abbr title='Telefono'>Tel</abbr>:</dt>
                                    <dd><a href='tel:{$ticket["telRich"]}'>+39 {$ticket["telRich"]}</a></dd>
                                    <dt class='note-hidden'>Note:</dt>
                                    <dd class='note-hidden'>{$ticket["info"]}</dd>
                                </dl>
                            </dd>
                        </dl>

                        <div class='btn-gruppo hidden'>
                            <button class='btn-info' title='Note aggiuntive' data-info='{$ticket["info"]}' data-nome='{$ticket["richiedente"]}' aria-label='note aggiuntive per {$ticket["richiedente"]}'>
                                <svg aria-hidden='true'>
                                    <use href='{{root}}/Resources/icons.svg#info'></use>
                                </svg>
                            </button>
                            <a class='go-calendario btn-link' href='{{root}}/amministrazione/calendario?mese={$mese}&anno={$anno}#g{$giorno}' aria-label='vai ad appuntamento in calendario con {$ticket["richiedente"]}'>
                                <svg aria-hidden='true'>
                                    <use href='{{root}}/Resources/icons.svg#forward'></use>
                                </svg>
                                <span>Calendario</span>
                            </a>
                        </div>
                    </li>";
                }
            }

            else { $richGestite = "<li><p>Non ci sono richieste già gestite.</p></li>"; }

            $html .=
            "<li>
                <details class='dtl-animale'>
                <summary>
                    <span class='info-animale'>
                        <img src='{{root}}/Resources/Animali/{$animale["infoAnimale"]["foto"]}' class='img-animale' alt='' aria-hidden='true'>

                        <span>
                            <span class='nome-animale'>{$animale["infoAnimale"]["nome"]}</span>
                            <span class='dettagli-animale'>{$animale["infoAnimale"]["tipo"]} - <span lang='{$animale["infoAnimale"]["linguaRazza"]}'>{$animale["infoAnimale"]["razza"]}</span></span>
                            <span class='status-richieste'><span class='num-rich'>{$numRich}</span>&nbsp;<span class='richieste'>nuove richieste</span></span>
                        </span>
                    </span>

                    <svg class='exp-freccia' aria-hidden='true'>
                        <use href='{{root}}/Resources/icons.svg#arrow'></use>
                    </svg>
                </summary>

                <div class='contenuto-nascosto'>
                    <div>       <!-- Con il solo scopo di rendere più fluida la dissolvenza della scheda -->
                        <section>
                            <p class='titolo-richieste'>Richieste da gestire:</p>

                            <ul>
                                {$richDaGestire}
                            </ul>
                        </section>

                        <section>
                            <p>Richieste gestite:</p>

                            <ul>
                                {$richGestite}
                            </ul>
                        </section>
                    </div>
                </div>
            </details>
        </li>";
        }
    }

    else { $html = "<li><p class='no-richieste'>Nessun animale corrisponde alla ricerca.</p></li>"; }

    $pannelloControllo = renderPannelloControlloFiltri(true); // Il pulsante di ordina non viene mostrato
    $pannelloFiltri = renderPannelloFiltri(PROJECT_ROOT . "/amministrazione/gestione-ticket.php", ["Tipo", "Dati persona"]);

    $dati = [
        "{{current-page}}" => "Tickets",
        "{{page-keywords}}" => "",
        "{{type-script}}" => "module",
        "{{current-js}}" => "gestione-ticket.js",
        "{{extra-js}}" => "pannello-filtri.js",
        "{{lista-animali}}" => $html,
        "{{pannello-controllo-filtri}}" => $pannelloControllo,
        "{{pannello-filtri}}" => $pannelloFiltri,
        "{{dialogs}}" => (getDialogInfo()) . (getDialogAppuntamento("aggiungi-appuntamento", $_SERVER["REQUEST_URI"])) . (getDialogCanRichiesta("elimina-richiesta", $_SERVER["REQUEST_URI"]))
    ];

    echo buildPage("gestione-ticket.html", $dati);
} else { header("Location: " . PROJECT_ROOT . "/401.php"); }
?>