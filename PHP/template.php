<?php
require_once dirname(__DIR__) . "/PHP/header.php";
require_once dirname(__DIR__) . "/PHP/breadcrumb.php";

function buildTemplate() {
    $template = file_get_contents(__DIR__ . "/../HTML/template.html");

    $header = populatedNavbar();
    $breadcrumb = populatedBread();
    $footer = file_get_contents(__DIR__ . "/../HTML/footer.html");
    $accountBtn = getAccountButton();

    $layout = [
        "{{header}}" => $header,
        "{{breadcrumb}}" => $breadcrumb,
        "{{footer}}" => $footer,
        "{{btn-account}}" => $accountBtn
    ];

    foreach ($layout as $placeholder => $valore) {
        $template = str_replace($placeholder, $valore, $template);
    }

    return $template;
}
?>