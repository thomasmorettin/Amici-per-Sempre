<?php
namespace Model;

require_once dirname(__DIR__) . "/db-access.php";
use DB\DBAccess;

// Ottieni un singolo animale per ID
function getAnimaleById($id) {
    $query = "SELECT ar.*, r.Tipo, r.Lingua AS LinguaRazza
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
    
    $query = "SELECT ar.*, r.Tipo, r.Nome AS NomeRazza, r.Lingua AS LinguaRazza
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
    
    // determina quali tipi sono selezionati (se nessuno, considera entrambi)
    $tipi_selezionati = !empty($filtri['tipo']) ? $filtri['tipo'] : ['Cane', 'Gatto'];
    
    // filtro razze intelligente con controllo compatibilità
    $razze_condizioni = [];
    
    // Se ci sono razze cane selezionate E "Cane" è nei tipi selezionati
    if (!empty($filtri['razza_cane']) && in_array('Cane', $tipi_selezionati)) {
        $placeholders = implode(',', array_fill(0, count($filtri['razza_cane']), '?'));
        $razze_condizioni[] = "(r.Tipo = 'Cane' AND r.Nome IN ($placeholders))";
        foreach ($filtri['razza_cane'] as $razza) {
            $params[] = $razza;
        }
    }
    
    // Se ci sono razze gatto selezionate E "Gatto" è nei tipi selezionati
    if (!empty($filtri['razza_gatto']) && in_array('Gatto', $tipi_selezionati)) {
        $placeholders = implode(',', array_fill(0, count($filtri['razza_gatto']), '?'));
        $razze_condizioni[] = "(r.Tipo = 'Gatto' AND r.Nome IN ($placeholders))";
        foreach ($filtri['razza_gatto'] as $razza) {
            $params[] = $razza;
        }
    }
    
    if (!empty($razze_condizioni)) {
        $condizioni[] = '(' . implode(' OR ', $razze_condizioni) . ')';
    }
    
    // filtro per nome
    if (!empty($filtri['nome'])) {
        $condizioni[] = "ar.Nome LIKE ?";
        $params[] = '%' . $filtri['nome'] . '%';
    }
    
    // filtro per peso
    if (!empty($filtri['peso']) && $filtri['peso'] > 0) {
        $condizioni[] = "ar.Peso >= ?";
        $params[] = $filtri['peso'];
    }
    
    // Filtro per età
    if (!empty($filtri['eta']) && $filtri['eta'] > 0) {
        $condizioni[] = "ar.Eta >= ?";
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