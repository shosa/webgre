<?php
// Avvia la sessione
session_start();

// Elimina tutte le variabili di sessione
$_SESSION = array();

// Distrugge la sessione
session_destroy();

// Reindirizza alla home
header('Location: home');
exit();
?>