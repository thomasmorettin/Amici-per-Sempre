<?php
namespace Model;
require_once dirname(__DIR__) . "/../PHP/db-access.php";
use DB\DBAccess;

function getRazze() {
    $db = new DBAccess();

    $query = "SELECT * FROM Razza ORDER BY Tipo";

    $connOk = $db->openConn();
    if ($connOk) {
        $result = $db->exeQuery($query);
        $db->closeConn();
        
        $razze_cane = [];
        $razze_gatto = [];

        if ($result) {
            foreach ($result as $row) {
                $razza_data = [
                    'Nome' => htmlspecialchars($row["Nome"], ENT_QUOTES, 'UTF-8'),
                    'Lingua' => htmlspecialchars($row["Lingua"], ENT_QUOTES, 'UTF-8')
                ];
                
                if ($row["Tipo"] === "Cane") {
                    array_push($razze_cane, $razza_data);
                } else if ($row["Tipo"] === "Gatto") {
                    array_push($razze_gatto, $razza_data);
                }
            }
        }

        $razze = ["Cane" => $razze_cane, "Gatto" => $razze_gatto];

        return $razze;
    }

    return null;
}

function getRazzeByTipo($tipo) {
    if ($tipo === null) {
        return null;
    }

    $db = new DBAccess();

    $query = "SELECT * FROM Razza WHERE Tipo = ?";

    $connOk = $db->openConn();
    if ($connOk) {
        $result = $db->exeQuery($query, [$tipo]);
        $db->closeConn();

        $razze = [];

        if ($result) {
            foreach ($result as $row) {
                $razza_data = [
                    'Nome' => htmlspecialchars($row["Nome"], ENT_QUOTES, 'UTF-8'),
                    'Lingua' => htmlspecialchars($row["Lingua"], ENT_QUOTES, 'UTF-8')
                ];
                array_push($razze, $razza_data);
            }
        }

        return $razze;
    }

    return null;
}
?>