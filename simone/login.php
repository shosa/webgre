<?php
session_start();
include 'config/config.php';
$db = getDbInstance();

// Controlla se l'utente è già loggato, in tal caso reindirizzalo alla pagina di amministrazione
if (isset($_SESSION['logged_in'])) {
    header('Location: admin.php');
    exit();
}

// Gestione del login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Effettua la query per trovare l'utente nel database
    $db->where('username', $username);
    $user = $db->getOne('users');

    // Verifica se l'utente esiste e la password è corretta
    if ($user && $password === $user['password']) {
        // Autenticazione riuscita, imposta la sessione e reindirizza alla pagina di amministrazione
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $user['username']; // Salva il nome utente nella sessione se necessario
        header('Location: admin');
        exit();
    } else {
        // Credenziali non valide, mostra un messaggio di errore
        $error = "Credenziali non valide. Riprova.";
    }
}

?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PixelBook - Login</title>
    <?php require ("content/header.php"); ?>
    <link rel="stylesheet" href="content/css/login.css">
</head>

<body>
    <!-- Navbar -->
    <?php include ("content/components/navbar.php") ?>
    <!-- Form di Login -->
    <div class="container mt-5">
        <h2>Accesso</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <form action="" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Accedi</button>
        </form>
    </div>

</body>
<?php require ("content/footer.php"); ?>

</html>