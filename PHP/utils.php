<?php
require_once dirname(__DIR__) . "/PHP/template.php";
require_once dirname(__DIR__) . "/PHP/vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Pelago\Emogrifier\CssInliner;

const PROJECT_ROOT = "/tec-web";

// Funzione per l'avvio della sessione se non già avviata
function ensure_session() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function is_logged_in() {
    ensure_session();
    return isset($_SESSION['loggato']);
}

function getMsgSession() {
    ensure_session();
    $dataMsg = "";

    if (isset($_SESSION["success"])) {
        $msg = htmlspecialchars($_SESSION["success"], ENT_QUOTES, 'UTF-8');
        $dataMsg = "data-toast-msg='$msg' data-toast-type='success'";
        unset($_SESSION["success"]);
    }

    elseif (isset($_SESSION["error"])) {
        $msg = htmlspecialchars($_SESSION["error"], ENT_QUOTES, 'UTF-8');
        $dataMsg = "data-toast-msg='$msg' data-toast-type='error'";
        unset($_SESSION["error"]);
    }

    return $dataMsg;
}

// Funzione per assemblaggio della pagina (header + main + footer)
function buildPage($file, $dati) {
    $main = file_get_contents(__DIR__ . "/../HTML/" . $file);

    // Creazione della pagina finale con unione degli elementi
    $template = buildTemplate();

    $template = str_replace("{{data-page}}", getMsgSession(), $template);
    $template = str_replace("{{main}}", $main, $template);
    // Rimozione di attributo defer nel caso in cui il tipo di script è module
    $template = str_replace('{{defer}}', isset($dati["{{type-script}}"]) ? "" : "defer", $template);

    // Se è presente {{extra-js}} e contiene solo nomi di file, trasformali in tag <script>
    if (isset($dati['{{extra-js}}'])) {
        $extra = $dati['{{extra-js}}'];
        if (!is_string($extra) && is_array($extra)) {
            $items = $extra;
        } else {
            $items = preg_split('/[|,;\s]+/', trim((string)$extra));
        }

        // se la stringa non contiene già un tag <script>, genera i tag
        $containsScriptTag = is_string($extra) && (stripos($extra, '<script') !== false);
        if (!$containsScriptTag) {
            $scripts = '';
            foreach ($items as $it) {
                $it = trim($it);
                if ($it === '') continue;
                // se è un URL assoluto
                if (preg_match('#^https?://#i', $it)) {
                    $scripts .= '<script src="' . $it . '" defer></script>' . "\n";
                } else {
                    // assicurati estensione .js
                    if (!preg_match('/\.js$/i', $it)) $it .= '.js';
                    $scripts .= '<script src="{{root}}/JavaScript/' . $it . '" defer></script>' . "\n";
                }
            }
            $dati['{{extra-js}}'] = $scripts;
        }
    }

    // Popolamento dinamico dei placeholder
    foreach ($dati as $placeholder => $valore) {
        $template = str_replace($placeholder, $valore, $template);
    }

    $template = str_replace("{{root}}", PROJECT_ROOT, $template);

    return preg_replace("/\{\{.*?\}\}/", "", $template);        // Rimuove eventuali placeholder non sostituiti
}

// Funzione per il parsing del CSS all'interno dei tag inline de HTML
function parserEmailCSS($html) {
    $css = file_get_contents(__DIR__ . "/../CSS/style-email.css");

    try {
        return CssInliner::fromHtml($html)->inlineCss($css)->render();
    }
    
    catch (Exception $e) {
        return $html;
    }
}

// Funzione per l'invio della mail automatica all'utente
function sendEmail($destinatario, $nomeUser) {
    $mail = new PHPMailer(true);

    $html = file_get_contents(__DIR__ . "/../HTML/Email/request-mail.html");
    $body = parserEmailCSS($html);

    $body = str_replace("{{root}}", PROJECT_ROOT, $body);
    $body = str_replace("{{nome-utente}}", $nomeUser, $body);

    try {
        $mail->isSMTP();
        $mail->Host       = gethostbyname("smtp.gmail.com");
        $mail->SMTPAuth   = true;
        $mail->Username   = "rifugio.amicipersempre@gmail.com";     // Indirizzo email del rifugio
        $mail->Password   = "ghwn vgqt wqxz apzn";      // Password apposita per l'applicazione -> connessione all'indirizzo di posta
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 465;	// Il server dell'Università blocca il traffico uscente (anche da 587)

	    // Timeout: Se non si connette in 10 secondi, da errore (invece di caricare all'infinito)
        $mail->Timeout    = 5;
        $mail->Timelimit  = 5;

        $mail->setFrom("rifugio.amicipersempre@gmail.com", "Rifugio Amici per Sempre");
        $mail->addAddress($destinatario);
        $mail->isHTML(true);

        $mail->Subject = "Richiesta al Rifugio Amici per Sempre";
        $mail->Body = $body;

        $mail->send();

        return true;
    }

    catch (Exception $e) { return false; }
}
?>