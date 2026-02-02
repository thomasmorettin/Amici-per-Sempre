<?php
require_once dirname(__DIR__) . "/utils.php";
require_once dirname(__DIR__) . "/breadcrumb.php";
require_once dirname(__DIR__) . "/Model/animale.php";
require_once dirname(__DIR__) . "/Controller/pannello-filtri.php"; 

use function Model\getAllAnimali;

ensure_session();

// === GESTIONE FILTRI ===
$filtri = [
    'tipo' => isset($_GET['tipo']) ? $_GET['tipo'] : [],
    'nome' => isset($_GET['nome']) ? $_GET['nome'] : '',
    'peso' => isset($_GET['peso']) ? $_GET['peso'] : '',  
    'eta'  => isset($_GET['eta'])  ? $_GET['eta']  : '',
    'razza_cane' => isset($_GET['razza_cane']) ? $_GET['razza_cane'] : [],   
    'razza_gatto' => isset($_GET['razza_gatto']) ? $_GET['razza_gatto'] : [] 
];

// === RECUPERO ANIMALI TRAMITE MODEL ===
$animali = getAllAnimali($filtri);
$count = count($animali);

// === GENERAZIONE HTML LISTA ANIMALI ===
$lista_animali_html = '';
$contatore_html = '';

if (!empty($animali)) {
    // conteggio 
    $contatore_html = sprintf(
        '<p class="animali-trovati" role="status">%d %s %s</p>',
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
        
        // Usa il campo Lingua dal database 
        $lang_razza = ($animale['LinguaRazza'] !== 'it') 
            ? ' lang="' . htmlspecialchars($animale['LinguaRazza'], ENT_QUOTES, 'UTF-8') . '"' 
            : '';

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
            <a href="%s/adotta/scheda-animale?id=%d" aria-label="%s">
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
            htmlspecialchars("{{root}}/Resources/Animali/" . $animale['PthImg'], ENT_QUOTES, 'UTF-8'),
            $nome,
            $lang_razza,
            $razza,
            $tipo
        );
    }
} else {
    $contatore_html = '<p class="animali-nontrovati" role="status">Nessun animale trovato con i filtri selezionati.</p>';
}

// === GENERAZIONE PANNELLO FILTRI 
$pannello_controllo_html = Controller\renderPannelloControlloFiltri(false);
$pannello_filtri_html = Controller\renderPannelloFiltri(
    PROJECT_ROOT . '/adotta',
    ['Tipo', 'Razze', 'Dati animale']  
);

// === ARRAY DATI PER buildPage() ===
$dati = [
    '{{current-page}}' => 'Adotta',
    '{{page-description}}' => "Cani e gatti in adozione al rifugio Amici per Sempre di Padova. Filtra per razza, peso ed età per trovare l'animale perfetto per te.",
    '{{page-keywords}}' => 'Amici per Sempre,
                            cani in adozione Padova, 
                            gatti in adozione Padova, 
                            adozione cane, adozione gatto, 
                            cani taglia piccola, 
                            animali in adozione, 
                            rifugio animali Padova',

    '{{current-js}}' => 'pannello-filtri.js',
    '{{extra-js}}' => 'org-schema.js',
    '[project_root]' => PROJECT_ROOT,
    '[contatore_animali]' => $contatore_html,
    '[lista_animali]' => $lista_animali_html,
    '[pannello_controllo_filtri]' => $pannello_controllo_html, 
    '[pannello_filtri]' => $pannello_filtri_html,              
    
];

// === OUTPUT FINALE ===
echo buildPage("adotta.html", $dati);
?>