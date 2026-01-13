<?php
require_once dirname(__DIR__) . "/PHP/template.php";

const PROJECT_ROOT = "/tec-web";

// Funzione per l'avvio della sessione se non già avviata
function ensure_session() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
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

    // -- PARTE MODIFICATA DA NICCOLO' --

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

    // -- FINE PARTE MODIFICATA DA NICCOLO' --

    // Popolamento dinamico dei placeholder
    foreach ($dati as $placeholder => $valore) {
        $template = str_replace($placeholder, $valore, $template);
    }

    $template = str_replace("{{root}}", PROJECT_ROOT, $template);

    return preg_replace("/\{\{.*?\}\}/", "", $template);        // Rimuove eventuali placeholder non sostituiti
}
?>