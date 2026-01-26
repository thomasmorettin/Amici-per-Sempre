<?php
require_once dirname(__DIR__) . "/utils.php";
require_once dirname(__DIR__) . "/breadcrumb.php";
require_once dirname(__DIR__) . "/Model/animale.php";
require_once dirname(__DIR__) . "/Controller/pannello-filtri.php"; 

use function Model\getAllAnimali;

ensure_session();

// === LISTA RAZZE INGLESI ===
$razze_inglesi = [
    'Labrador',
    'Golden Retriever',
    'Beagle',
    'Bulldog',
    'Chihuahua',
    'Maine Coon',
    'Ragdoll',
    'Sphynx',
    'British Shorthair',
    'Scottish Fold'
];

// === GESTIONE FILTRI ===
$filtri = [
    'tipo' => isset($_GET['tipo']) ? $_GET['tipo'] : [],
    'nome' => isset($_GET['nome']) ? $_GET['nome'] : '',
    'peso' => isset($_GET['peso']) ? $_GET['peso'] : '',  
    'eta'  => isset($_GET['eta'])  ? $_GET['eta']  : '' 
];

// === RECUPERO ANIMALI TRAMITE MODEL ===
$animali = getAllAnimali($filtri);
$count = count($animali);

// === GENERAZIONE HTML LISTA ANIMALI ===
$lista_animali_html = '';

if (!empty($animali)) {
    // conteggio 
    $lista_animali_html .= sprintf(
        '<p class="animali-trovati">%d %s %s</p>',
        $count,
        $count === 1 ? 'animale' : 'animali',
        $count === 1 ? 'trovato' : 'trovati'
    );
    
    foreach ($animali as $animale) {
        // Sanitizza i dati
        $nome = htmlspecialchars($animale['Nome'], ENT_QUOTES, 'UTF-8');
        $razza = htmlspecialchars($animale['NomeRazza'], ENT_QUOTES, 'UTF-8');
        $tipo = htmlspecialchars($animale['Tipo'], ENT_QUOTES, 'UTF-8');
        $colore = htmlspecialchars($animale['Colore'], ENT_QUOTES, 'UTF-8');
        $tipo_lower = strtolower($tipo);
        
        // Determina se la razza è in inglese
        $lang_razza = in_array($animale['NomeRazza'], $razze_inglesi) ? ' lang="en"' : '';

        // aria-label per il link
        $aria_label = sprintf(
            'Vai alla scheda di %s, %s %s di razza %s',
            $nome,
            $tipo_lower,
            $colore,
            $razza
        );
        
        $lista_animali_html .= sprintf('
        <li class="animal-card">
            <a href="%s/PHP/Controller/scheda_animale?id=%d" aria-label="%s">
                <figure>
                    <img src="%s" alt="">
                    <figcaption>
                        <h3>%s - <span%s>%s</span></h3>
                        <p class="tipo">%s</p>
                    </figcaption>
                </figure>
            </a>
        </li>',
            PROJECT_ROOT,
            (int)$animale['ID'],
            $aria_label,
            htmlspecialchars($animale['PthImg'], ENT_QUOTES, 'UTF-8'),
            $nome,
            $lang_razza,
            $razza,
            $tipo
        );
    }
} else {
    $lista_animali_html = '
    <li class="no-results" role="status">
        <p>Nessun animale trovato con i filtri selezionati.</p>
        <p><a href="' . PROJECT_ROOT . '/adotta">Rimuovi i filtri</a></p>
    </li>';
}

// === GENERAZIONE PANNELLO FILTRI 
$pannello_controllo_html = Controller\renderPannelloControlloFiltri(false); // false = no bottone "Ordina"
$pannello_filtri_html = Controller\renderPannelloFiltri(
    PROJECT_ROOT . '/adotta',  // Action del form
    ['Tipo', 'Dati animale']   // Solo queste 2 sezioni (senza "Dati persona")
);

// === ARRAY DATI PER buildPage() ===
$dati = [
    '{{current-page}}' => 'Adotta',
    '{{page-description}}' => '',
    '{{page-keywords}}' => 'Amici per Sempre,
                            cani in adozione Padova, 
                            gatti in adozione Padova, 
                            adozione cane, adozione gatto, 
                            cani taglia piccola, 
                            animali in adozione, 
                            rifugio animali Padova',

    '{{current-js}}' => 'pannello-filtri.js', 
    '[project_root]' => PROJECT_ROOT,
    '[lista_animali]' => $lista_animali_html,
    '[pannello_controllo_filtri]' => $pannello_controllo_html, 
    '[pannello_filtri]' => $pannello_filtri_html,              
    
];

// === OUTPUT FINALE ===
echo buildPage("adotta.html", $dati);
?>