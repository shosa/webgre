<?php
session_start();
include 'config/config.php';
$db = getDbInstance();

// Controlla se l'utente è loggato, altrimenti reindirizzalo alla pagina di accesso
if (!isset($_SESSION['logged_in'])) {
    header('Location: login');
    exit();
}

?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PixelBook - Admin</title>
    <?php require ("content/header.php"); ?>
    <link rel="stylesheet" href="content/css/admin.css">
    <style>

    </style>
</head>

<body>

    <!-- Navbar -->
    <?php include ("content/components/admin_navbar.php") ?>
    <div class="logo">
        <i class="fa-duotone fa-aperture" style="--fa-primary-color: #6610f2; --fa-secondary-color: #6610f2;"></i><br>
        <b>Pixel<span style="color:#6610f2">Book</b></span>
        <br><span class="labelAdmin">ADMIN PANEL</span>
    </div>
    <div class="sottologo">
        <small class="text-muted" style="font-size: 1em;">Usa il menu in alto per navigare nelle sezioni di
            amministrazione.</small>
    </div>

</body>
<?php require ("content/footer.php"); ?>

</html>