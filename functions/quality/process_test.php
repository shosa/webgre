<?php
// process_test.php

// Includi il file di configurazione del database
require_once '../../config/config.php';

// Recupera i dati inviati dal modulo
$cartellino = filter_input(INPUT_POST, 'cartellino', FILTER_UNSAFE_RAW);
$calzate = $_POST['calzata'];
$test = $_POST['test'];
$note = $_POST['note'];
$esiti = $_POST['esito'];

$db = getDbInstance();
// Prepara e inserisci i dati nel database per ogni riga
for ($i = 0; $i < count($calzate); $i++) {
    $data = [
        'cartellino' => $cartellino,
        'commessa' => $_SESSION['commessa'],
        'calzata' => $calzate[$i],
        'test' => $test[$i],
        'note' => $note[$i],
        'esito' => $esiti[$i]
    ];

    // Inserisci i dati nella tabella cq_records
    $insert = $db->insert('cq_records', $data);

    // Verifica se l'inserimento è avvenuto con successo
    if (!$insert) {
        // Se l'inserimento fallisce, invia una risposta JSON al client con indicazione dell'errore
        echo json_encode(['success' => false, 'message' => 'Errore durante l\'inserimento dei dati.']);
        exit; // Esci dallo script
    }
}

// Se l'inserimento è avvenuto con successo, invia una risposta JSON al client
echo json_encode(['success' => true]);
