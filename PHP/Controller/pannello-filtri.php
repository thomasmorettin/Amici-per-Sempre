<?php
namespace Controller;
// Helper per il pannello filtri — restituisce HTML pronto da inserire

// Recupera i filtri dalla request GET
function getFiltriFromRequest(): array
{
	return [
		'tipo' => isset($_GET['tipo']) ? (array)$_GET['tipo'] : [],
		'nome' => isset($_GET['nome']) ? (string)$_GET['nome'] : '',
		'peso' => isset($_GET['peso']) ? (string)$_GET['peso'] : 0,
		'eta'  => isset($_GET['eta'])  ? (string)$_GET['eta']  : 0,
        'nome_persona' => isset($_GET['nome_persona']) ? (string)$_GET['nome_persona'] : '',
        'cognome_persona' => isset($_GET['cognome_persona']) ? (string)$_GET['cognome_persona'] : '',
        'email' => isset($_GET['email']) ? (string)$_GET['email'] : '',
        'telefono' => isset($_GET['telefono']) ? (string)$_GET['telefono'] : ''
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

    $nome = htmlspecialchars($values['nome'] ?? '', ENT_QUOTES, 'UTF-8');
    $peso = htmlspecialchars($values['peso'] ?? '', ENT_QUOTES, 'UTF-8');
    $eta  = htmlspecialchars($values['eta'] ?? '', ENT_QUOTES, 'UTF-8');
    $nome_persona = htmlspecialchars($values['nome_persona'] ?? '', ENT_QUOTES, 'UTF-8');
    $cognome_persona = htmlspecialchars($values['cognome_persona'] ?? '', ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($values['email'] ?? '', ENT_QUOTES, 'UTF-8');
    $telefono = htmlspecialchars($values['telefono'] ?? '', ENT_QUOTES, 'UTF-8');

    $project_root = defined('PROJECT_ROOT') ? PROJECT_ROOT : ''; 


    // Controlla se ci sono filtri che sono stati cambiati da quelli default
    $filtri_cambiati = count($selectedTipo);
    if ($nome !== '') $filtri_cambiati++;
    if ($peso !== '') $filtri_cambiati++;
    if ($eta !== '') $filtri_cambiati++;
    if ($nome_persona !== '') $filtri_cambiati++;
    if ($cognome_persona !== '') $filtri_cambiati++;
    if ($email !== '') $filtri_cambiati++;
    if ($telefono !== '') $filtri_cambiati++;

    $html = '<div class="filter-panel">'
        . '    <div class="filter-content">'
        . '        <form method="GET" action="' . htmlspecialchars($action, ENT_QUOTES) . '">'
        . '            <div class="azioni-filtro">'
        . '                <button type="submit" id="applica">Applica ' . $filtri_cambiati . ' filtri</button>'
        . '                <button type="reset" id="reset"></button>'
        . '            </div>';

    if (in_array('Tipo', $sections, true)) {
        $html .= '            <div class="accordion">'
            . '                <div class="accordion-header">'
            . '                    <div class="legend-left"></div>'
            . '                    <div class="header">'
            . '                        <p>Tipo animale</p>'
            . '                        <span class="header-arrow"></span>'
            . '                    </div>'
            . '                    <div class="legend-right"></div>'
            . '                </div>'
            . '                <div class="content">'
            . '                    <div class="inner-content form-field">'
            . '                        <div class="check-group">'
            . '                            <label for="cane">'
            . '                                <input type="checkbox" id="cane" name="tipo[]" value="Cane" ' . $checked_cane . '>Cane'
            . '                            </label>'
            . '                            <label for="gatto">'
            . '                                <input type="checkbox" id="gatto" name="tipo[]" value="Gatto" ' . $checked_gatto . '>Gatto'
            . '                            </label>'
            . '                        </div>'
            . '                    </div>'
            . '                </div>'
            . '            </div>';
    }

    if (in_array('Dati animale', $sections, true)) {
        $html .= '            <div class="accordion">'
            . '                <div class="accordion-header">'
            . '                    <div class="legend-left"></div>'
            . '                    <div class="header">'
            . '                        <p>Dati animale</p>'
            . '                        <span class="header-arrow"></span>'
            . '                    </div>'
            . '                    <div class="legend-right"></div>'
            . '                </div>'
            . '                <div class="content">'
            . '                    <div class="inner-content">'
            . '                        <div class="form-field">'
            . '                            <label>Nome</label>'
            . '                            <input type="text" name="nome" value="' . $nome . '" placeholder="Es: Fido">'
            . '                        </div>'
            . '                        <div class="form-field">'
            . '                            <label>Peso</label>'
            . '                             <select name="peso">'
            . '                                <option value="" ' . ($peso == "" ? 'selected' : '') . '>Qualsiasi</option>'
            . '                                <option value="-5" ' . ($peso == "-5" ? 'selected' : '') . '>Molto piccolo (Meno di 5 kg)</option>'
            . '                                <option value="5-10" ' . ($peso == "5-10" ? 'selected' : '') . '>Piccolo (Da 5 a 10 kg)</option>'
            . '                                <option value="11-25" ' . ($peso == "11-25" ? 'selected' : '') . '>Medio (Da 11 a 25 kg)</option>'
            . '                                <option value="26-50" ' . ($peso == "26-50" ? 'selected' : '') . '>Grande (Da 26 a 50 kg)</option>'
            . '                                <option value="51+" ' . ($peso == "51+" ? 'selected' : '') . '>Molto grande (51 kg o più)</option>'
            . '                             </select>'
            . '                        </div>'
            . '                        <div class="form-field">'
            . '                            <label>Età</label>'
            . '                             <select name="eta">'
            . '                                <option value="" ' . ($eta == "" ? 'selected' : '') . '>Qualsiasi</option>'
            . '                                <option value="-4" ' . ($eta == "-4" ? 'selected' : '') . '>Cucciolo (Meno di 4 mesi)</option>'
            . '                                <option value="4-1" ' . ($eta == "4-1" ? 'selected' : '') . '>Piccolo (Da 5 mesi ad 1 anno)</option>'
            . '                                <option value="1-4" ' . ($eta == "1-4" ? 'selected' : '') . '>Giovane (Da 1 a 4 anni)</option>'
            . '                                <option value="4-10" ' . ($eta == "4-10" ? 'selected' : '') . '>Adulto (Da 4 a 10 anni)</option>'
            . '                                <option value="10+" ' . ($eta == "10+" ? 'selected' : '') . '>Anziano (10 anni o più)</option>'
            . '                             </select>'
            . '                        </div>'
            . '                    </div>'
            . '                </div>'
            . '            </div>';
    }

    if (in_array('Dati persona', $sections, true)) {
        $html .= '             <div class="accordion">'
            . '                 <div class="accordion-header">'
            . '                    <div class="legend-left"></div>'
            . '                     <div class="header">'
            . '                        <p> Dati persona </p>'
            . '                        <span class="header-arrow"></span>'
            . '                    </div>'
            . '                    <div class="legend-right"></div>'
            . '                </div>'
            . '                <div class="content">'
            . '                    <div class="inner-content">'
            . '                         <div class="form-field">'
            . '                            <label>Nome</label>'
            . '                            <input type="text" name="nome_persona" value="' . $nome_persona . '" placeholder="Es: Mario">'
            . '                        </div>'
            . '                        <div class="form-field">'
            . '                            <label>Cognome</label>'
            . '                            <input type="text" name="cognome_persona" value="' . $cognome_persona . '" placeholder="Es: Rossi">'
            . '                        </div>'
            . '                        <div class="form-field">'
            . '                            <label>Email</label>'
            . '                            <input type="email" name="email" value="' . $email . '" placeholder="Es: mariorossi@example.com">'
            . '                        </div>'
            . '                        <div class="form-field">'
            . '                            <label>Telefono</label>'
            . '                            <input type="tel" name="telefono" value="' . $telefono . '" placeholder="Es: 1234567890">'
            . '                        </div>'
            . '                    </div>'
            . '                </div>'
            . '            </div>';
    }

    $html .= '        </form>'
        . '    </div>'
        . '    <span class="divider"></span>'
        . '</div>';

    return $html;
}


// Crea il pannello di controllo filtri (pulsanti Filtra e Ordina)
// Di default il bottone "Ordina" non viene mostrato
function renderPannelloControlloFiltri($ordina = false): string
{

    $html = '            <div class="list-topbar">'
        . '                   <button id="filtra-btn">'
        . '                       Filtra'
        . '                   </button>';    

    if ($ordina) {
        $html .= '            <div class="select-wrapper" id="ordina-wrapper">'
            . '                    <select class="custom-select" name="sort">'
            . '                         <option value="razza">Ordina per: Nome</option>'
            . '                         <option value="nome">Ordina per: Data richiesta</option>'
            . '                         <option value="data">Ordina per: Data appuntamento</option>'
            . '                    </select>'
            . '               </div>';
    }

    $html .= '            </div>';

    return $html;
}

?>