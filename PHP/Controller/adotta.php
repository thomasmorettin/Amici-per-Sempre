<?php
require_once dirname(__DIR__) . "/PHP/utils.php";
require_once dirname(__DIR__) . "/PHP/breadcrumb.php";
require_once dirname(__DIR__) . "/Model/Animale.php";

use function Model\getAllAnimali;

ensure_session();

// === GESTIONE FILTRI ===
$filtri = [
    'tipo' => isset($_GET['tipo']) ? $_GET['tipo'] : [],
    'nome' => isset($_GET['nome']) ? $_GET['nome'] : '',
    'peso' => isset($_GET['peso']) ? (int)$_GET['peso'] : 0,
    'eta'  => isset($_GET['eta'])  ? (int)$_GET['eta']  : 0
];

// === RECUPERO ANIMALI TRAMITE MODEL ===
$animali = getAllAnimali($filtri);

// === GENERAZIONE HTML LISTA ANIMALI ===
$lista_animali_html = '';

if (!empty($animali)) {
    foreach ($animali as $animale) {
        $lista_animali_html .= sprintf('
        <li class="animal-card">
            <a href="%s/scheda_animale?id=%d">
                <figure>
                    <img src="%s" alt="Foto di %s">
                    <figcaption>
                        <h3>%s, %s</h3>
                        <p class="tipo">%s</p>
                    </figcaption>
                </figure>
            </a>
        </li>',
            PROJECT_ROOT,
            (int)$animale['ID'],
            htmlspecialchars($animale['PthImg'], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($animale['Nome'], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($animale['Nome'], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($animale['NomeRazza'], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($animale['Tipo'], ENT_QUOTES, 'UTF-8')
        );
    }
} else {
    $lista_animali_html = '
    <li class="no-results">
        <p>Nessun animale trovato con i filtri selezionati.</p>
        <p><a href="' . PROJECT_ROOT . '/adotta">Rimuovi i filtri</a></p>
    </li>';
}

// === GESTIONE CHECKBOX E VALORI FILTRI ===
$checked_cane  = in_array('Cane', $filtri['tipo'])  ? 'checked' : '';
$checked_gatto = in_array('Gatto', $filtri['tipo']) ? 'checked' : '';

// === ARRAY DATI PER buildPage() ===
$dati = [
    '{{current-page}}' => 'Adotta',
    
    '{{page-keywords}}' => 'Amici per Sempre,
                            cani in adozione Padova, 
                            gatti in adozione Padova, 
                            adozione cane, adozione gatto, 
                            cani taglia piccola, 
                            animali in adozione, 
                            rifugio animali Padova',

    '{{current-js}}' => '../JavaScript/filtri-adotta.js',
    '[project_root]' => PROJECT_ROOT,
    '[lista_animali]' => $lista_animali_html,
    '[checked_cane]' => $checked_cane,
    '[checked_gatto]' => $checked_gatto,
    '[filter_nome]' => htmlspecialchars($filtri['nome'], ENT_QUOTES, 'UTF-8'),
    '[filter_peso]' => $filtri['peso'] > 0 ? $filtri['peso'] : 1,
    '[filter_eta]' => $filtri['eta'] > 0 ? $filtri['eta'] : 1
];

// === OUTPUT FINALE ===
echo buildPage("adotta.html", $dati);
?>