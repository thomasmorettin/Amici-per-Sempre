<?php
namespace Model;

require_once dirname(__DIR__) . "/db-access.php";
use DB\DBAccess;

// Ottieni un singolo animale per ID
function getAnimaleById($id) {
    $query = "SELECT ar.*, r.Tipo
              FROM AnimaleRifugio ar
              JOIN Razza r ON ar.Razza = r.Nome
              WHERE ar.ID = ?";
    
    $db = new DBAccess();
    $connOk = $db->openConn();
    
    if ($connOk) {
        $result = $db->exeQuery($query, [$id]);
        $db->closeConn();
        
        return !empty($result) ? $result[0] : null;
    }
    
    return null;
}

// Ottieni tutti gli animali con filtri opzionali
function getAllAnimali($filtri = []) {
    
    $query = "SELECT ar.*, r.Tipo, r.Nome AS NomeRazza
              FROM AnimaleRifugio ar
              JOIN Razza r ON ar.Razza = r.Nome";
    
    $params = [];
    $condizioni = [];
    
    // Filtro per tipo (Cane/Gatto)
    if (!empty($filtri['tipo'])) {
        $placeholders = implode(',', array_fill(0, count($filtri['tipo']), '?'));
        $condizioni[] = "r.Tipo IN ($placeholders)";
        foreach ($filtri['tipo'] as $tipo) {
            $params[] = $tipo;
        }
    }
    
    // Filtro per nome
    if (!empty($filtri['nome'])) {
        $condizioni[] = "ar.Nome LIKE ?";
        $params[] = '%' . $filtri['nome'] . '%';
    }
    
    // Filtro per peso
    if (!empty($filtri['peso']) && $filtri['peso'] > 0) {
        $condizioni[] = "CAST(SUBSTRING_INDEX(ar.Peso, ' ', 1) AS DECIMAL) >= ?";
        $params[] = $filtri['peso'];
    }
    
    // Filtro per età
    if (!empty($filtri['eta']) && $filtri['eta'] > 0) {
        $condizioni[] = "CAST(SUBSTRING_INDEX(ar.Eta, ' ', 1) AS DECIMAL) >= ?";
        $params[] = $filtri['eta'];
    }
    
    // Aggiungi condizioni WHERE se presenti
    if (!empty($condizioni)) {
        $query .= " WHERE " . implode(" AND ", $condizioni);
    }
    
    $query .= " ORDER BY ar.Nome ASC";
    
    $db = new DBAccess();
    $connOk = $db->openConn();
    
    if ($connOk) {
        $result = $db->exeQuery($query, $params);
        $db->closeConn();
        
        return $result ?: [];
    }
    
    return [];
}
?>