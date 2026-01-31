<?php
namespace Controller;

require_once dirname(__DIR__) . '\Model\razze.php';
use function Model\getRazze;

// Helper per il pannello filtri — restituisce HTML pronto da inserire

// Recupera i filtri dalla request GET
function getFiltriFromRequest(): array
{
	return [
		'tipo' => isset($_GET['tipo']) ? (array)$_GET['tipo'] : [],
        'sesso' => isset($_GET['sesso']) ? (array)$_GET['sesso'] : [],
        'razza_cane' => isset($_GET['razza_cane']) ? (array)$_GET['razza_cane'] : [],
        'razza_gatto' => isset($_GET['razza_gatto']) ? (array)$_GET['razza_gatto'] : [],
		'nome' => isset($_GET['nome']) ? (string)$_GET['nome'] : '',
		'peso' => isset($_GET['peso']) ? (string)$_GET['peso'] : '',
		'eta'  => isset($_GET['eta'])  ? (string)$_GET['eta']  : '',
        'nome_persona' => isset($_GET['nome_persona']) ? (string)$_GET['nome_persona'] : '',
        'cognome_persona' => isset($_GET['cognome_persona']) ? (string)$_GET['cognome_persona'] : '',
        'email' => isset($_GET['email']) ? (string)$_GET['email'] : '',
        'telefono' => isset($_GET['telefono']) ? (string)$_GET['telefono'] : '',
        'ricerca' => isset($_GET['ricerca']) ? (string)$_GET['ricerca'] : ''
	];
}

// Crea il pannello filtri in base a i tipi di filtri listati in $filtri
// Inserire su $action l'URL di destinazione del form

// Sezioni da mettere sull'array $filtri
// SEZIONE "Tipo": Checkbox con "Cane" e "Gatto"
// SEZIONE "Dati animale": Input per "Nome", Select per "Peso" e "Età"
// SEZIONE "Dati persona": Input per "Nome", "Cognome", "Email" e "Telefono"
function renderPannelloFiltri(?string $action, array $filtri = []): string
{
    $sections = $filtri ?: ['Tipo', 'Dati animale', 'Dati persona'];

    // Valori correnti per popolare i campi (presa dalla request)
    $values = getFiltriFromRequest();
    $selectedTipo = isset($values['tipo']) && is_array($values['tipo']) ? $values['tipo'] : [];

    $checked_cane  = in_array('Cane', $selectedTipo) ? 'checked' : '';
    $checked_gatto = in_array('Gatto', $selectedTipo) ? 'checked' : '';

    $selectedSesso = isset($values['sesso']) && is_array($values['sesso']) ? $values['sesso'] : [];

    $checked_maschio = in_array('Maschio', $selectedSesso) ? 'checked' : '';
    $checked_femmina = in_array('Femmina', $selectedSesso) ? 'checked' : '';

    $nome = htmlspecialchars($values['nome'] ?? '', ENT_QUOTES, 'UTF-8');
    $peso = htmlspecialchars($values['peso'] ?? '', ENT_QUOTES, 'UTF-8');
    $eta  = htmlspecialchars($values['eta'] ?? '', ENT_QUOTES, 'UTF-8');
    $nome_persona = htmlspecialchars($values['nome_persona'] ?? '', ENT_QUOTES, 'UTF-8');
    $cognome_persona = htmlspecialchars($values['cognome_persona'] ?? '', ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($values['email'] ?? '', ENT_QUOTES, 'UTF-8');
    $telefono = htmlspecialchars($values['telefono'] ?? '', ENT_QUOTES, 'UTF-8');

    $project_root = defined('PROJECT_ROOT') ? PROJECT_ROOT : ''; 

    $razze_cane = isset($values['razza_cane']) && is_array($values['razza_cane']) ? $values['razza_cane'] : [];
    $razze_gatto = isset($values['razza_gatto']) && is_array($values['razza_gatto']) ? $values['razza_gatto'] : [];

    // Controlla se ci sono filtri che sono stati cambiati da quelli default
    $filtri_cambiati = count($selectedTipo);
    $filtri_cambiati += count($selectedSesso);
    if ($nome !== '') $filtri_cambiati++;
    if ($peso !== '') $filtri_cambiati++;
    if ($eta !== '') $filtri_cambiati++;
    if ($nome_persona !== '') $filtri_cambiati++;
    if ($cognome_persona !== '') $filtri_cambiati++;
    if ($email !== '') $filtri_cambiati++;
    if ($telefono !== '') $filtri_cambiati++;
    $filtri_cambiati += count($razze_cane) + count($razze_gatto);

    $html = '<div class="filter-panel" id="side-panel">'
        . '    <div class="filter-content">'
        . '        <form method="GET" action="' . htmlspecialchars($action, ENT_QUOTES) . '" id="form-filtri">'
        . '            <div class="azioni-filtro">'
        . '                <button type="submit" id="applica" data-n-filtri="' . $filtri_cambiati .'">Applica ' . $filtri_cambiati . ' filtri</button>'
        . '                <button type="button" class="reset" aria-label="Azzera i filtri">'
        . '                    <svg aria-hidden="true">'
        . '                         <use href="{{root}}/Resources/icons.svg#delete"></use>'
        . '                    </svg>'
        . '                 </button>'
        . '            </div>';

    if (in_array('Tipo', $sections, true)) {
        $html .= '         <div class="accordion">'
            . '                <div class="accordion-header">'
            . '                    <div class="legend-left"></div>'
            . '                    <button type="button" class="header" aria-expanded="false" id="legenda-tipo-animale" aria-describedby="count-tipo-animale">'
            . '                        Tipo animale'
            . '                        <svg aria-hidden="true">'
            . '                            <use href="{{root}}/Resources/icons.svg#arrow"></use>'
            . '                        </svg>'
            . '                    </button>'
            . '                    <span class="flag-filtro" aria-live="polite">'
            . '                        <span class="flag">0</span>'
            . '                        <span class="solo-sr" id="count-tipo-animale"></span>'
            . '                    </span>'
            . '                    <div class="legend-right"></div>'
            . '                </div>'
            . '                <div class="content">'
            . '                    <div class="inner-content">'
            . '                        <div class="form-field">'
            . '                            <fieldset class="check-group" aria-labelledby="legenda-tipo-animale">'
            . '                                <label for="cane">'
            . '                                    <input type="checkbox" id="cane" name="tipo[]" value="Cane" ' . $checked_cane . '>Cane'
            . '                                    <svg aria-hidden="true"><use href="{{root}}/Resources/icons.svg#circle"></use></svg>'
            . '                                </label>'
            . '                                <label for="gatto">'
            . '                                    <input type="checkbox" id="gatto" name="tipo[]" value="Gatto" ' . $checked_gatto . '>Gatto'
            . '                                    <svg aria-hidden="true"><use href="{{root}}/Resources/icons.svg#circle"></use></svg>'
            . '                                </label>'
            . '                            </fieldset>'
            . '                        </div>'
            . '                    </div>'
            . '                </div>'
            . '            </div>';
    }

    if (in_array('Dati animale', $sections, true)) {
        $html .= '            <div class="accordion">'
            . '                <div class="accordion-header">'
            . '                    <div class="legend-left"></div>'
            . '                    <button type="button" class="header" aria-expanded="false" aria-describedby="count-dati-animale">'
            . '                        Dati animale'
            . '                        <svg aria-hidden="true">'
            . '                            <use href="{{root}}/Resources/icons.svg#arrow"></use>'
            . '                        </svg>'
            . '                    </button>'
            . '                    <span class="flag-filtro" aria-live="polite">'
            . '                        <span class="flag">0</span>'
            . '                        <span class="solo-sr" id="count-dati-animale"></span>'
            . '                    </span>'
            . '                    <div class="legend-right"></div>'
            . '                </div>'
            . '                <div class="content">'
            . '                    <div class="inner-content">'
            . '                        <div class="form-field">'
            . '                            <label for="nome">Nome</label>'
            . '                            <input type="text" id="nome" name="nome" value="' . $nome . '" placeholder="Es. Fido" data-changed="' . ($nome == "" ? 'false' : 'true') . '">'
            . '                        </div>'
            . '                        <div class="form-field">'
            . '                            <label for="peso">Peso</label>'
            . '                            <div class="select-custom">'
            . '                                <select name="peso" id="peso" data-changed="' . ($peso == "" ? 'false' : 'true') . '">'
            . '                                    <option value="" ' . ($peso == "" ? 'selected' : '') . '>Qualsiasi</option>'
            . '                                    <option value="-5" ' . ($peso == "-5" ? 'selected' : '') . '>Molto piccolo (Meno di 5 kg)</option>'
            . '                                    <option value="5-10" ' . ($peso == "5-10" ? 'selected' : '') . '>Piccolo (Da 5 a 10 kg)</option>'
            . '                                    <option value="11-25" ' . ($peso == "11-25" ? 'selected' : '') . '>Medio (Da 11 a 25 kg)</option>'
            . '                                    <option value="26-50" ' . ($peso == "26-50" ? 'selected' : '') . '>Grande (Da 26 a 50 kg)</option>'
            . '                                    <option value="51+" ' . ($peso == "51+" ? 'selected' : '') . '>Molto grande (51 kg o più)</option>'
            . '                                </select>'
            . '                                <svg aria-hidden="true">'
            . '                                    <use href="{{root}}/Resources/icons.svg#arrow"></use>'
            . '                                </svg>'
            . '                            </div>'
            . '                        </div>'
            . '                        <div class="form-field">'
            . '                            <label for="eta">Età</label>'
            . '                            <div class="select-custom">'
            . '                                    <select name="eta" id="eta" data-changed="' . ($eta == "" ? 'false' : 'true') . '">'
            . '                                    <option value="" ' . ($eta == "" ? 'selected' : '') . '>Qualsiasi</option>'
            . '                                    <option value="-4" ' . ($eta == "-4" ? 'selected' : '') . '>Cucciolo (Meno di 4 mesi)</option>'
            . '                                    <option value="4-1" ' . ($eta == "4-1" ? 'selected' : '') . '>Piccolo (Da 5 mesi ad 1 anno)</option>'
            . '                                    <option value="1-4" ' . ($eta == "1-4" ? 'selected' : '') . '>Giovane (Da 1 a 4 anni)</option>'
            . '                                    <option value="4-10" ' . ($eta == "4-10" ? 'selected' : '') . '>Adulto (Da 4 a 10 anni)</option>'
            . '                                    <option value="10+" ' . ($eta == "10+" ? 'selected' : '') . '>Anziano (10 anni o più)</option>'
            . '                                </select>'
            . '                                <svg aria-hidden="true">'
            . '                                    <use href="{{root}}/Resources/icons.svg#arrow"></use>'
            . '                                </svg>'
            . '                            </div>'
            . '                        </div>'
            . '                    </div>'
            . '                </div>'
            . '            </div>';
    }

    if (in_array('Dati persona', $sections, true)) {
        $html .= '             <div class="accordion">'
            . '                 <div class="accordion-header">'
            . '                    <div class="legend-left"></div>'
            . '                     <button type="button" class="header" aria-expanded="false" aria-describedby="count-dati-persona">'
            . '                        Dati persona'
            . '                        <svg aria-hidden="true">'
            . '                            <use href="{{root}}/Resources/icons.svg#arrow"></use>'
            . '                        </svg>'
            . '                    </button>'
            . '                    <span class="flag-filtro" aria-live="polite">'
            . '                        <span class="flag">0</span>'
            . '                        <span class="solo-sr" id="count-dati-persona"></span>'
            . '                    </span>'
            . '                    <div class="legend-right"></div>'
            . '                </div>'
            . '                <div class="content">'
            . '                    <div class="inner-content">'
            . '                        <div class="form-field">'
            . '                            <label for="nome_persona">Nome</label>'
            . '                            <input type="text" id="nome_persona" name="nome_persona" value="' . $nome_persona . '" placeholder="Es. Mario" data-changed="' . ($nome_persona == "" ? 'false' : 'true') . '">'
            . '                        </div>'
            . '                        <div class="form-field">'
            . '                            <label for="cognome_persona">Cognome</label>'
            . '                            <input type="text" id="cognome_persona" name="cognome_persona" value="' . $cognome_persona . '" placeholder="Es. Rossi" data-changed="' . ($cognome_persona == "" ? 'false' : 'true') . '">'
            . '                        </div>'
            . '                        <div class="form-field">'
            . '                            <label for="email">Email</label>'
            . '                            <input type="email" id="email" name="email" value="' . $email . '" placeholder="Es. mariorossi@esempio.com" data-changed="' . ($email == "" ? 'false' : 'true') . '">'
            . '                        </div>'
            . '                        <div class="form-field">'
            . '                            <label for="telefono">Telefono</label>'
            . '                            <input type="tel" id="telefono" name="telefono" value="' . $telefono . '" placeholder="Es. 1234567890" data-changed="' . ($telefono == "" ? 'false' : 'true') . '">'
            . '                        </div>'
            . '                    </div>'
            . '                </div>'
            . '            </div>';
    }

    if (in_array('Razze', $sections, true)) {
        $razze = getRazze();

        $razze_cane_html = "";
        $razze_gatto_html = "";

        foreach ($razze["Cane"] as $razza_cane) {
            $razze_cane_html .= '          <label for="' . $razza_cane . '">'
            . '                                <input type="checkbox" id="' . $razza_cane .'" name="razza_cane[]" value="' . $razza_cane .'" ' . (in_array($razza_cane, $razze_cane) ? "checked" : "") . '>' . $razza_cane
            . '                                <svg aria-hidden="true"><use href="{{root}}/Resources/icons.svg#circle"></use></svg>'
            . '                            </label>';
        }

        foreach ($razze["Gatto"] as $razza_gatto) {
            $razze_gatto_html .= '         <label for="' . $razza_gatto . '">'
            . '                                <input type="checkbox" id="' . $razza_gatto .'" name="razza_gatto[]" value="' . $razza_gatto .'" ' . (in_array($razza_gatto, $razze_gatto) ? "checked" : "") . '>' . $razza_gatto
            . '                                <svg aria-hidden="true"><use href="{{root}}/Resources/icons.svg#circle"></use></svg>'
            . '                            </label>';
        }

        $html .= '             <div class="accordion">'
            . '                 <div class="accordion-header">'
            . '                    <div class="legend-left"></div>'
            . '                     <button type="button" class="header" aria-expanded="false" id="legenda-razze-cane" aria-describedby="count-razze-cane">'
            . '                        Razze cane'
            . '                        <svg aria-hidden="true">'
            . '                            <use href="{{root}}/Resources/icons.svg#arrow"></use>'
            . '                        </svg>'
            . '                    </button>'
            . '                    <span class="flag-filtro" aria-live="polite">'
            . '                        <span class="flag">0</span>'
            . '                        <span class="solo-sr" id="count-razze-cane"></span>'
            . '                    </span>'
            . '                    <div class="legend-right"></div>'
            . '                </div>'
            . '                <div class="content">'
            . '                    <div class="inner-content form-field">'
            . '                        <fieldset class="check-group" aria-labelledby="legenda-razze-cane">'
            .                              $razze_cane_html
            . '                        </fieldset>'
            . '                    </div>'
            . '                </div>'
            . '             </div>'
            . '             <div class="accordion">'
            . '                 <div class="accordion-header">'
            . '                    <div class="legend-left"></div>'
            . '                     <button type="button" class="header" aria-expanded="false" id="legenda-razze-gatto" aria-describedby="count-razze-gatto">'
            . '                        Razze gatto'
            . '                        <svg aria-hidden="true">'
            . '                            <use href="{{root}}/Resources/icons.svg#arrow"></use>'
            . '                        </svg>'
            . '                    </button>'
            . '                    <span class="flag-filtro" aria-live="polite">'
            . '                        <span class="flag">0</span>'
            . '                        <span class="solo-sr" id="count-razze-gatto"></span>'
            . '                    </span>'
            . '                    <div class="legend-right"></div>'
            . '                </div>'
            . '                <div class="content">'
            . '                    <div class="inner-content form-field">'
            . '                        <fieldset class="check-group" aria-labelledby="legenda-razze-gatto">'
            .                              $razze_gatto_html
            . '                        </fieldset>'
            . '                    </div>'
            . '                </div>'
            . '            </div>';
    }

    $html .= '        </form>'
        . '    </div>'
        . '    <span class="divider"></span>'
        . '</div>'
        . '<dialog class="filter-panel" id="popup-panel">'
        . '    <button class="btn-close" aria-label="chiudi pannello filtri">
                    <svg aria-hidden="true">
                        <use href="{{root}}/Resources/icons.svg#cancel"></use>
                    </svg>
                </button>'
        . '   <div class="filter-content">'
        . '   </div>'
        . '</dialog>';

    return $html;
}


// Crea il pannello di controllo filtri (pulsanti Filtra e Ordina)
// Di default il bottone "Ordina" non viene mostrato
function renderPannelloControlloFiltri($ricerca_html = false): string
{

    $values = getFiltriFromRequest();
    $ricerca = htmlspecialchars($values['ricerca'] ?? '', ENT_QUOTES, 'UTF-8');

    $html = '            <div id="list-topbar">'
        . '                   <button id="filtra-btn" ' . (!$ricerca_html ? "class=alone" : "") . '>'
        . '                       <svg aria-hidden="true">'
        . '                           <use href="{{root}}/Resources/icons.svg#filter"></use>'
        . '                       </svg>'
        . '                   <span class="abbr">Filtra</span>'
        . '                   </button>';    

    if ($ricerca_html) {
        $html .= '          <form id="form-ricerca" method="GET">'
        . '                    <input type="text" id="ricerca" name="ricerca" placeholder="Ricerca" value="' . $ricerca . '">'
        . '                    <button id="avvia-ricerca" title="Avvia ricerca" type="submit">'
        . '                       <svg aria-hidden="true">'
        . '                         <use href="{{root}}/Resources/icons.svg#search"></use>'
        . '                       </svg>'
        . '                    <span class="abbr">Cerca</span>'
        . '                    </button>'
        . '                    <button id="clear-ricerca" title="Azzerra ricerca" type="reset">'
        . '                       <svg aria-hidden="true">'
        . '                         <use href="{{root}}/Resources/icons.svg#delete"></use>'
        . '                       </svg>'
        . '                    <span class="abbr">Azzera</span>'
        . '                    </button>'
        . '                 </form>';
    }

    $html .= '            </div>';

    return $html;
}

?>