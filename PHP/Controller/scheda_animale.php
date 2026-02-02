<?php
require_once dirname(__DIR__) . "/utils.php";
require_once dirname(__DIR__) . "/breadcrumb.php";
require_once dirname(__DIR__) . "/Model/animale.php";

use function Model\getAnimaleById;

ensure_session();

// === RECUPERO ID ANIMALE DALL'URL ===
$animale_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($animale_id <= 0) {
    header("Location: " . PROJECT_ROOT . "/404");
    exit;
}

// === RECUPERO DATI TRAMITE MODEL ===
$animale = getAnimaleById($animale_id);

if (!$animale) {
    header("Location: " . PROJECT_ROOT . "/404");
    exit;
}

// === SANITIZZAZIONE DATI ===
$nome = htmlspecialchars($animale['Nome'], ENT_QUOTES, 'UTF-8');
$razza = htmlspecialchars($animale['Razza'], ENT_QUOTES, 'UTF-8');
$tipo = htmlspecialchars($animale['Tipo'], ENT_QUOTES, 'UTF-8');

// PRIMA sanitizza, POI formatta
$eta_raw = floatval(htmlspecialchars($animale['Eta'], ENT_QUOTES, 'UTF-8'));
$anni = floor($eta_raw);
$mesi = round(($eta_raw - $anni) * 12);

// Gestisci casi particolari
if ($mesi == 12) {
    $anni++;
    $mesi = 0;
}

// Costruisci la stringa età
$eta_formattata = '';
if ($anni > 0) {
    $eta_formattata .= $anni . ($anni == 1 ? ' anno' : ' anni');
}
if ($mesi > 0) {
    if ($anni > 0) $eta_formattata .= ' e ';
    $eta_formattata .= $mesi . ($mesi == 1 ? ' mese' : ' mesi');
}
if (empty($eta_formattata)) {
    $eta_formattata = 'Meno di 1 mese';
}

$eta = $eta_formattata;

$colore = htmlspecialchars($animale['Colore'], ENT_QUOTES, 'UTF-8');
$sesso_completo = $animale['Sesso'] === 'M' ? 'Maschio' : 'Femmina';

$peso_raw = floatval(htmlspecialchars($animale['Peso'], ENT_QUOTES, 'UTF-8'));
$peso = $peso_raw . ' kg';

$storia = htmlspecialchars($animale['Storia'], ENT_QUOTES, 'UTF-8');
$pthImg = htmlspecialchars("{{root}}/Resources/Animali/" . $animale['PthImg'], ENT_QUOTES, 'UTF-8');

// Determina la lingua della razza e assegna lang per accessibilità
$lingua_razza = ($animale['LinguaRazza'] !== 'it') 
    ? ' lang="' . htmlspecialchars($animale['LinguaRazza'], ENT_QUOTES, 'UTF-8') . '"' 
    : '';

// Gestione caratteristiche
$caratteristiche = json_decode($animale['Caratteristiche'], true);
$caratteristiche_html = '';

if (is_array($caratteristiche) && !empty($caratteristiche)) {
    foreach ($caratteristiche as $caratteristica) {
    $caratteristiche_html .= '<li>'
        . '<svg class="checkli" aria-hidden="true">'
        . '    <use href="{{root}}/Resources/icons.svg#check"></use>'
        . '</svg>'
        . htmlspecialchars($caratteristica, ENT_QUOTES, 'UTF-8')
        . '</li>';
}
} else {
    $caratteristiche_html = '<li>Nessuna caratteristica specificata</li>';
}


// Recupera dati del formi di richiesta visita per ripopolamento
$form_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];
unset($_SESSION['form_data']);

// === ARRAY DATI PER buildPage() ===
$dati = [
    '{{current-page}}'   => $nome,
    '{{page-description}}' => "Adotta un $tipo di razza $razza in cerca di famiglia. Prenota una visita gratuita al rifugio Amici per Sempre di Padova.",
    '{{page-keywords}}'  => "Amici per Sempre, 
                            $razza in adozione Padova, 
                            $tipo in adozione,   
                            $razza $colore adozione, 
                            $tipo in adozione Padova",

    '{{current-js}}'     => 'richiesta-visita.js',
    '{{extra-js}}'       => 'org-schema.js',
    '{{header}}'         => file_get_contents(dirname(__DIR__) . "/../HTML/header.html"),
    '{{breadcrumb}}'     => populatedBread(),
    '{{footer}}'         => file_get_contents(dirname(__DIR__) . "/../HTML/footer.html"),
    '[immagine]'         => $pthImg,
    '[nome_animale]'     => $nome,
    '[specie]'           => $tipo,
    '[lang_razza]'       => $lingua_razza, 
    '[razza]'            => $razza,
    '[eta]'              => $eta,
    '[sesso]'            => $sesso_completo,
    '[peso]'             => $peso,
    '[colore]'           => $colore,
    '[storia]'           => nl2br($storia),
    '[caratteristiche_list]' => $caratteristiche_html,
    '[nome_animale]'     => $nome,
    '[animale_id]'       => $animale_id,
    '[val_nome]'         => isset($form_data['nome']) ? htmlspecialchars($form_data['nome'], ENT_QUOTES, 'UTF-8') : '',
    '[val_cognome]'      => isset($form_data['cognome']) ? htmlspecialchars($form_data['cognome'], ENT_QUOTES, 'UTF-8') : '',
    '[val_email]'        => isset($form_data['email']) ? htmlspecialchars($form_data['email'], ENT_QUOTES, 'UTF-8') : '',
    '[val_telefono]'     => isset($form_data['telefono']) ? htmlspecialchars($form_data['telefono'], ENT_QUOTES, 'UTF-8') : '',
    '[val_note]'         => isset($form_data['note']) ? htmlspecialchars($form_data['note'], ENT_QUOTES, 'UTF-8') : ''
];

// === OUTPUT FINALE ===
echo buildPage("scheda_animale.html", $dati);
?>