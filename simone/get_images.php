<?php
session_start();
include 'config/config.php';

// Controlla se il parametro categoria è stato passato
if (isset($_GET['categoria'])) {
    $categoria = $_GET['categoria'];

    // Costruisci il percorso della cartella delle immagini per la categoria specificata
    $path = 'content/src/img/' . $categoria;

    // Ottieni un array delle immagini nella cartella
    $images = array_values(array_diff(scandir($path), array('..', '.')));

    // Invia l'array delle immagini come JSON
    header('Content-Type: application/json');
    echo json_encode($images);
} else {
    // Se il parametro categoria non è stato passato, restituisci un messaggio di errore
    http_response_code(400);
    echo json_encode(array('error' => 'Il parametro "categoria" è obbligatorio.'));
}
?>
