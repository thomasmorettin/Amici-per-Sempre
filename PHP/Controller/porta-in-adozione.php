<?php
require_once dirname(__DIR__) . "/../PHP/utils.php";
require_once dirname(__DIR__) . "/Model/razze.php";

use function Model\getRazze;

ensure_session();

$form_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];
unset($_SESSION['form_data']);

$radio_buttons_sesso = '<label for="sesso-maschio">
                            <input type="radio" name="sesso" value="m" id="sesso-maschio" aria-describedby="error-sesso" required ' . ((isset($form_data['sesso']) && $form_data['sesso'] == "m") ? 'checked' : '') . '>
                            Maschio
                            <svg aria-hidden="true">
                                <use href="{{root}}/Resources/icons.svg#check"></use>
                            </svg>
                        </label>
                        <label for="sesso-femmina">
                            <input type="radio" name="sesso" value="f" id="sesso-femmina" aria-describedby="error-sesso" required ' . ((isset($form_data['sesso']) && $form_data['sesso'] == "f") ? 'checked' : '') . '>
                            Femmina
                            <svg aria-hidden="true">
                                <use href="{{root}}/Resources/icons.svg#check"></use>
                            </svg>
                        </label>';

$radio_buttons_specie = '<label for="specie-cane">
                            <input type="radio" name="specie" id="specie-cane" aria-describedby="error-specie" value="cane" required ' . ((isset($form_data['specie']) && $form_data['specie'] == "cane") ? 'checked' : '') . '>
                            Cane
                            <svg aria-hidden="true">
                                <use href="{{root}}/Resources/icons.svg#check"></use>
                            </svg>
                        </label>
                        <label for="specie-gatto"><input type="radio" name="specie" id="specie-gatto" aria-describedby="error-specie" value="gatto" required ' . ((isset($form_data['specie']) && $form_data['specie'] == "gatto") ? 'checked' : '') . '>
                            Gatto
                            <svg aria-hidden="true">
                                <use href="{{root}}/Resources/icons.svg#check"></use>
                            </svg>
                        </label>';

$razze = getRazze();

$razze_cani_html = '';
$razze_gatti_html = '';

foreach ($razze["Cane"] as $razza) {
    $razze_cani_html .= '<li><span lang="' . $razza["Lingua"] . '">'. $razza["Nome"] . '</span></li>';
}

foreach ($razze["Gatto"] as $razza) {
    $razze_gatti_html .= '<li><span lang="' . $razza["Lingua"] . '">' . $razza["Nome"] . '</span></li>';
}

$dati = [
    "{{current-page}}" => "Porta in Adozione",
    '{{page-description}}' => "Contatta il centro di adozione per prenotare una visita e portare il tuo cane o gatto in adozione. Invia una richiesta compilando il form.",
    "{{page-keywords}}" => "Amici per Sempre, 
                            Porta in Adozione, 
                            porta adozione cane,
                            porta adozione gatto, 
                            porta adozione animale",
    "{{current-js}}" => "porta-in-adozione.js",
    '{{extra-js}}' => 'org-schema.js',
    "[val_nome]"         => isset($form_data['nome']) ? htmlspecialchars($form_data['nome'], ENT_QUOTES, 'UTF-8') : '',
    "[val_cognome]"      => isset($form_data['cognome']) ? htmlspecialchars($form_data['cognome'], ENT_QUOTES, 'UTF-8') : '',
    "[val_email]"        => isset($form_data['email']) ? htmlspecialchars($form_data['email'], ENT_QUOTES, 'UTF-8') : '',
    "[val_telefono]"     => isset($form_data['telefono']) ? htmlspecialchars($form_data['telefono'], ENT_QUOTES, 'UTF-8') : '',
    "[val_razza]"        => isset($form_data['razza-input']) ? htmlspecialchars($form_data['razza-input'], ENT_QUOTES, 'UTF-8') : '',
    "[val_eta]"          => isset($form_data['eta']) ? htmlspecialchars($form_data['eta'], ENT_QUOTES, 'UTF-8') : '',
    "[val_peso]"         => isset($form_data['peso']) ? htmlspecialchars($form_data['peso'], ENT_QUOTES, 'UTF-8') : '',
    "[val_note]"         => isset($form_data['note']) ? htmlspecialchars($form_data['note'], ENT_QUOTES, 'UTF-8') : '',
    "[razze-cani]"       => $razze_cani_html,
    "[razze-gatti]"      => $razze_gatti_html,
    "[radio-buttons-sesso]" => $radio_buttons_sesso,
    "[radio-buttons-specie]" => $radio_buttons_specie
];

echo buildPage("porta_in_adozione.html", $dati);
?>