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
$eta = htmlspecialchars($animale['Eta'], ENT_QUOTES, 'UTF-8');
$colore = htmlspecialchars($animale['Colore'], ENT_QUOTES, 'UTF-8');
$sesso_completo = $animale['Sesso'] === 'M' ? 'Maschio' : 'Femmina';
$peso = htmlspecialchars($animale['Peso'], ENT_QUOTES, 'UTF-8');
$storia = htmlspecialchars($animale['Storia'], ENT_QUOTES, 'UTF-8');
$pthImg = htmlspecialchars($animale['PthImg'], ENT_QUOTES, 'UTF-8');

// Gestione caratteristiche
$caratteristiche = json_decode($animale['Caratteristiche'], true);
$caratteristiche_html = '';

if (is_array($caratteristiche) && !empty($caratteristiche)) {
    foreach ($caratteristiche as $caratteristica) {
        $caratteristiche_html .= '<li>' . htmlspecialchars($caratteristica, ENT_QUOTES, 'UTF-8') . '</li>';
    }
} else {
    $caratteristiche_html = '<li>Nessuna caratteristica specificata</li>';
}

// === GESTIONE MESSAGGI PER TOAST ===
// Il messaggio di successo viene gestito da getMsgSession() in utils.php
// che popola automaticamente {{data-page}}

// Recupera dati del formi di richiesta visita per ripopolamento
$form_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];
unset($_SESSION['form_data']);

// === ARRAY DATI PER buildPage() ===
$dati = [
    '{{current-page}}'   => $nome,
    
    '{{page-keywords}}'  => "Amici per Sempre, 
                            $razza in adozione Padova, 
                            $tipo in adozione, 
                            adottare $razza Padova,  
                            $razza $colore adozione, 
                            $tipo in adozione Padova",

    '{{current-js}}'     => 'richiesta-visita.js',
    '{{header}}'         => file_get_contents(dirname(__DIR__) . "/../HTML/header.html"),
    '{{breadcrumb}}'     => populatedBread(),
    '{{footer}}'         => file_get_contents(dirname(__DIR__) . "/../HTML/footer.html"),
    '[immagine]'         => $pthImg,
    '[alt_immagine]'     => "Foto di $nome",
    '[nome_animale]'     => $nome,
    '[specie]'           => $tipo,
    '[razza]'            => $razza,
    '[eta]'              => $eta,
    '[sesso]'            => $sesso_completo,
    '[peso]'             => $peso,
    '[colore]'           => $colore,
    '[storia]'           => nl2br($storia),
    '[caratteristiche_list]' => $caratteristiche_html,
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