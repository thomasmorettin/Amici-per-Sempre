<?php
namespace Model;

require_once dirname(__DIR__) . "/db-access.php";
use DB\DBAccess;

function accedi($user, $password) {
    $query = "SELECT Nome, PasswordHash FROM Utente WHERE Nome = ?";
    
    $db = new DBAccess();
    $connOk = $db->openConn();
    
    if ($connOk) {
        $result = $db->exeQuery($query, [$user]);
        $db->closeConn();
        
        if (!empty($result) && password_verify($password, $result[0]['PasswordHash'])) {
            return $result[0];
        }
    }
    
    return null;
}

?>