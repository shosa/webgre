<?php
session_start();
include 'config/config.php';
$db = getDbInstance();

// Controlla se l'utente è loggato, altrimenti reindirizzalo alla pagina di accesso
if (!isset($_SESSION['logged_in'])) {
    header('Location: login');
    exit();
}

// Gestione delle azioni
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Aggiungi una nuova sezione
if ($action == 'add_section') {
    $nome_sezione = $_POST['nome_sezione'];

    // Esegui l'inserimento nel database
    $data = array(
        'nome_sezione' => $nome_sezione,
        'abilitato' => 1 // Assumo che una nuova sezione sia abilitata di default
    );
    if ($db->insert('sezioni', $data)) {
        // Creazione della cartella per le immagini
        mkdir("src/img/$nome_sezione");

        // Messaggio di successo
        $alert_message = "Sezione '$nome_sezione' aggiunta con successo!";
    } else {
        // Messaggio di errore
        $alert_message = "Si è verificato un errore durante l'aggiunta della sezione.";
    }
}

// Elimina una sezione esistente
if ($action == 'delete_section') {
    $id = $_GET['id'];

    // Ottieni il nome della sezione prima di eliminarla
    $section = $db->where('id', $id)->getOne('sezioni');
    $nome_sezione = $section['nome_sezione'];

    // Esegui l'eliminazione nel database
    if ($db->where('id', $id)->delete('sezioni')) {
        // Elimina la cartella relativa alle immagini
        $cartella_immagini = "src/img/$nome_sezione";
        if (file_exists($cartella_immagini) && is_dir($cartella_immagini)) {
            // Elimina la cartella e tutti i file al suo interno
            array_map('unlink', glob("$cartella_immagini/*.*"));
            rmdir($cartella_immagini);
        }

        // Messaggio di successo
        $alert_message = "Sezione '$nome_sezione' eliminata con successo!";
    } else {
        // Messaggio di errore
        $alert_message = "Si è verificato un errore durante l'eliminazione della sezione.";
    }
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PixelBook - Admin</title>
    <?php require ("content/header.php"); ?>
    <link rel="stylesheet" href="content/css/admin_service.css">
</head>
<style>

</style>

<body>

    <!-- Navbar -->
    <?php include ("content/components/admin_navbar.php") ?>

    <!-- Aggiungi Sezione Form -->
    <div class="container mt-5">
        <div class="row justify-content-center"> <!-- Allinea il contenuto al centro -->
            <div class="col-md-6"> <!-- Utilizza una colonna di larghezza media -->
                <h2 class="text-center">Aggiungi una nuova sezione</h2> <!-- Allinea il titolo al centro -->
                <form action="?action=add_section" method="post">
                    <div class="form-group">
                        <label for="nome_sezione">Nome Sezione</label>
                        <input type="text" class="form-control" id="nome_sezione" name="nome_sezione" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block"
                        style="margin-top:2%;width:100%">Aggiungi</button>
                    <!-- Utilizza il pulsante a larghezza intera -->
                </form>
            </div>
        </div>
    </div>

    <!-- Elenco delle Sezioni -->
    <div class="container mt-5">
        <h2>Sezioni presenti:</h2>
        <div class="row">
            <?php
            $sections = $db->get('sezioni');
            if ($sections) {
                foreach ($sections as $section) {
                    ?>
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $section['nome_sezione']; ?></h5>
                                <a href="manage_images.php?section=<?php echo urlencode($section['nome_sezione']); ?>"
                                    class="btn btn-primary card-btn"><i class="fa-thin fa-images"></i> Immagini</a>
                                <a href="?action=delete_section&id=<?php echo $section['id']; ?>"
                                    class="btn btn-danger card-btn"><i class="fa-light fa-trash-can"></i> Rimuovi</a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>

</body>
<?php require ("content/footer.php"); ?>
<script>
    // Verifica se esiste un messaggio di alert da mostrare
    <?php if (!empty($alert_message)): ?>
        // Mostra l'alert Bootstrap
        $(document).ready(function () {
            var alertMessage = '<?php echo addslashes($alert_message); ?>'; // Aggiungiamo addslashes per evitare problemi con caratteri speciali
            $('#alert_placeholder').append('<div class="alert alert-success alert-dismissible fade show" role="alert">' + alertMessage + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
        });
    <?php endif; ?>
</script>

</html>