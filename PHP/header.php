<?php
require_once dirname(__DIR__) . "/PHP/utils.php";

function getNavbarLinks() {
    $paginaCorr = basename($_SERVER["PHP_SELF"]);
    $menu = [];

    $links = [
        [
            "file" => "index.php",
            "url" => PROJECT_ROOT . "/",
            "testo" => "Home",
            "placeholder" => "{{link-home}}"
        ],

        [
            "file" => "adotta.php",
            "url" => PROJECT_ROOT . "/adotta",
            "testo" => "Adotta",
            "placeholder" => "{{link-adotta}}"
        ],

        [
            "file" => "porta-in-adozione.php",
            "url" => PROJECT_ROOT . "/porta-in-adozione",
            "testo" => "Porta in adozione",
            "placeholder" => "{{link-porta-adozione}}"
        ]
    ];

    foreach ($links as $voce) {
        if ($voce["file"] === $paginaCorr) { $html = "<span class='bold' aria-current='page'>{$voce['testo']}</span>"; }
        else { $html = "<a href='{$voce['url']}'>{$voce['testo']}</a>"; }

        $menu[$voce["placeholder"]] = $html;
    }

    return $menu;
}

function replaceLogo() {
    $paginaCorr = basename($_SERVER["PHP_SELF"]);
    $html = "<h1>Rifugio Amici per Sempre</h1>";

    if ($paginaCorr !== "index.php") { $html = "<a id='link-logo' href='{{root}}/index'><h1>Rifugio Amici per Sempre</h1></a>"; }

    return $html;
}

function populatedNavbar() {
    $header = file_get_contents(__DIR__ . "/../HTML/header.html");

    $header = str_replace("{{logo}}", replaceLogo(), $header);

    $menu = getNavbarLinks();
    foreach ($menu as $placeholder => $valore) {
        $header = str_replace($placeholder, $valore, $header);
    }

    return $header;
}

function getAccountButton() {
    if(is_logged_in()) {
        return '    <a class="btn-link" href="{{root}}/amministrazione" aria-label="area amministrativa" title="Area amministrativa">
                        <svg aria-hidden="true">
                            <use href="{{root}}/Resources/icons.svg#wrench"></use>
                        </svg>
                    </a>
                    <a class="btn-link" href="{{root}}/accesso" lang="en" aria-label="logout da area riservata" title="Logout">
                        <svg aria-hidden="true">
                            <use href="{{root}}/Resources/icons.svg#logout"></use>
                        </svg>
                    </a>';
    } else {
        return '    <a class="btn-link" href="{{root}}/accesso" lang="en" aria-label="login ad area riservata" title="Login">
                        <svg aria-hidden="true">
                            <use href="{{root}}/Resources/icons.svg#login"></use>
                        </svg>
                    </a>';
    }
}
?>