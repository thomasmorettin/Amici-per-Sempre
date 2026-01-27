<?php
namespace Model;

require_once dirname(__DIR__) . "/db-access.php";
use DB\DBAccess;

header('Content-Type: application/json');

$query = "SELECT * FROM `Razza`";

$db = new DBAccess();
$connOk = $db->openConn();
    
if ($connOk) {
    $result = $db->exeQuery($query);
    $db->closeConn();

    $razze_cane = [];
    $razze_gatto = [];        
    
    foreach ($result as $row) {
        if($row["Tipo"] === "Cane") {
            array_push($razze_cane, $row["Nome"]);
        } else {
            array_push($razze_gatto, $row["Nome"]);
        }
    }

    $razze = [
        "Cane" => $razze_cane, 
        "Gatto"=> $razze_gatto
    ];

    echo json_encode($razze);
} else {
    echo null;
}

?>