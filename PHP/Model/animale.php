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
    
    // Se ci sono razze gatto selezionate e "Gatto" è nei tipi selezionati
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
    
    // Filtro per peso 
    if (!empty($filtri['peso'])) {
        $peso = $filtri['peso'];
    
        if ($peso === '-5') {
        // Molto piccolo (Meno di 5 kg)
            $condizioni[] = "ar.Peso < ?";
            $params[] = 5;
        } elseif ($peso === '51+') {
        // Molto grande (51 kg o più)
            $condizioni[] = "ar.Peso >= ?";
            $params[] = 51;
        } elseif (strpos($peso, '-') !== false) {
        // Range normale (es: "5-10", "11-25", "26-50")
            list($min, $max) = explode('-', $peso);
            $condizioni[] = "ar.Peso >= ? AND ar.Peso <= ?";
            $params[] = (float)$min;
            $params[] = (float)$max + 0.9;  // Include fino a 25.9
        }
    }
    
    // Filtro per età
    if (!empty($filtri['eta'])) {
        $eta = $filtri['eta'];

        if ($eta === '-4') {
        // Cucciolo (Meno di 4 mesi)
            $condizioni[] = "ar.Eta < ?";
            $params[] = 0.33;
        } elseif ($eta === '4-1') {
        // Piccolo (Da 4 mesi a meno di 1 anno)
            $condizioni[] = "ar.Eta >= ? AND ar.Eta < ?";  
            $params[] = 0.33;
            $params[] = 1.0;
        } elseif ($eta === '10+') {
        // Anziano (10 anni o più)
            $condizioni[] = "ar.Eta >= ?";
            $params[] = 10;
        } elseif (strpos($eta, '-') !== false) {
        // Range normale (es: "1-4", "4-10")
            list($min, $max) = explode('-', $eta);
            $condizioni[] = "ar.Eta >= ? AND ar.Eta < ?";  
            $params[] = (float)$min;
            $params[] = (float)$max;
        }
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