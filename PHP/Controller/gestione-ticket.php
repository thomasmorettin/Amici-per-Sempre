<?php
require_once dirname(__DIR__) . "/../PHP/utils.php";
require_once dirname(__DIR__) . "/Model/tickets.php";
require_once dirname(__DIR__) . "/Controller/pannello-filtri.php";
use function Model\getAnimaliTck;
use function Controller\renderPannelloFiltri;
use function Controller\renderPannelloControlloFiltri;

if (is_logged_in()) {
    $risDB = getAnimaliTck();
    $html = "";
    $oggi = date("Y-m-d");

    if (!empty($risDB)) {
        usort($risDB, function($a, $b) {
            $countA = count($a["daGestire"]);
            $countB = count($b["daGestire"]);

            if ($countA != $countB) { return $countB <=> $countA; }

            return strcasecmp($a["info"]["nome"], $b["info"]["nome"]);
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

                        <menu class='btn-gruppo'>
                            <button class='btn-info' title='Note aggiuntive' data-info='{$ticket["info"]}' data-nome='{$ticket["richiedente"]}'>
                                <svg>
                                    <use href='{{root}}/Resources/icons.svg#info'></use>
                                </svg>
                            </button>
                            <button class='btn-popup-app' title='Prenota appuntamento' data-id='{$ticket["id"]}' data-nome='{$ticket["richiedente"]}'>
                                <svg>
                                    <use href='{{root}}/Resources/icons.svg#calendario'></use>
                                </svg>
                            </button>
                            <button class='btn-elimina-app' title='Elimina richiesta' data-id='{$ticket["id"]}' data-nome='{$ticket["richiedente"]}'>
                                <svg>
                                    <use href='{{root}}/Resources/icons.svg#delete'></use>
                                </svg>
                            </button>
                        </menu>
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

                        <menu class='btn-gruppo'>
                            <button class='btn-info' title='Note aggiuntive' data-info='{$ticket["info"]}' data-nome='{$ticket["richiedente"]}'>
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
                        </menu>
                    </li>";
                }
            }

            else { $richGestite = "<p>Non ci sono richieste già gestite.</p>"; }

            $html .=
            "<li>
                <details class='dtl-animale'>
                <summary>
                    <div class='info-animale'>
                        <img src='{{root}}/Resources/Animali/{$animale["infoAnimale"]["foto"]}' class='img-animale'>

                        <div>
                            <p class='nome-animale'>{$animale["infoAnimale"]["nome"]}</p>
                            <p class='dettagli-animale'>{$animale["infoAnimale"]["tipo"]} - {$animale["infoAnimale"]["razza"]}</p>
                            <p class='status-richieste'><span class='num-rich'>{$numRich}</span>&nbsp<span class='richieste'>nuove richieste</span></p>
                        </div>
                    </div>

                    <svg class='exp-freccia'>
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

    else { $html = "<p class='center bold'>Nessun animale presente nel Rifugio.</p>"; }

    $pannelloControllo = renderPannelloControlloFiltri(true); // Il pulsante di ordina non viene mostrato
    $pannelloFiltri = renderPannelloFiltri(PROJECT_ROOT . "/amministrazione/gestione-ticket.php", ["Tipo", "Dati persona"]);

    $dati = [
        "{{current-page}}" => "Tickets",
        "{{page-keywords}}" => "",
        "{{current-js}}" => "gestione-ticket.js",
        "{{extra-js}}" => "pannello-filtri.js",
        "{{lista-animali}}" => $html,
        "{{pannello-controllo-filtri}}" => $pannelloControllo,
        "{{pannello-filtri}}" => $pannelloFiltri,
    ];

    echo buildPage("gestione-ticket.html", $dati);
} else { header("Location: " . PROJECT_ROOT . "/401.php"); }
?>