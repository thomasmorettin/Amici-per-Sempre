<?php
namespace Model;

require_once dirname(__DIR__) . "/db-access.php";
require_once dirname(__DIR__) . "/Model/razze.php";
use DB\DBAccess;
use Model\getRazzaByTipo;

header('Content-Type: application/json');

$tipo = $_GET['tipo'] ?? null;

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(404);
    exit;
}

if ($tipo === null) {
    echo json_encode([
        'successo' => false,
        'messaggio' => 'Parametro tipo mancante'
    ]);
    exit;
}

$razze = getRazzeByTipo($tipo);
    
if($razze === null) {
    echo json_encode([
        'successo' => false,
        'messaggio' => 'Errore nella richiesta delle razze'
    ]);
} else {
    echo json_encode([
        'successo' => true,
        'razze' => $razze
    ]);
}

?>