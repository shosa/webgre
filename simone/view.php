<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $_GET['service'] ?></title>
    <?php require ("content/header.php"); ?>
    <link rel="stylesheet" href="content/css/view.css">

</head>

<body>

    <!-- Navbar -->
    <?php include ("content/components/navbar.php") ?>

    <!-- Corpo della pagina -->
    <div class="container mt-5">
        <h1 class="text-center"><?php echo $_GET['service'] ?></h1>
        <!-- Bottone "BOOK NOW" -->
        <div class="container mt-5 text-center">
            <a href="booking?service=<?php echo urlencode($_GET['service']); ?>"
                class="bookingBtn btn btn-primary btn-lg rounded-pill shadow">BOOK NOW</a>
        </div>
        <div class="row">


            <?php
            // Controlla se il parametro service è impostato nella query string
            if (isset($_GET['service'])) {
                // Recupera il nome della categoria dalla query string
                $service = $_GET['service'];

                // Path alla cartella delle immagini relative alla categoria
                $directory = "src/img/" . $service . "/";

                // Recupera tutte le immagini dalla cartella
                $immagini = glob($directory . "*.{jpg,jpeg,png,gif,webp}", GLOB_BRACE);

                // Mostra le immagini
                foreach ($immagini as $immagine) {
                    echo '<div class="col-md-3 mb-4">';
                    echo '<img src="' . $immagine . '" alt="' . basename($immagine) . '" class="img-fluid">';
                    echo '</div>';
                }
            } else {
                // Se il parametro service non è impostato, mostra un messaggio di errore
                echo '<div class="alert alert-danger" role="alert">Errore: Categoria non specificata.</div>';
            }
            ?>
        </div>
    </div>
    <div class="container mt-5 text-center">
        <a href="booking?service=<?php echo urlencode($_GET['service']); ?>"
            class="bookingBtn btn btn-primary btn-lg rounded-pill shadow">BOOK NOW</a>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>

    <!-- Aggiungi il JavaScript per l'effetto fade-in -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.body.classList.add('fade-in');
        });
    </script>
</body>

</html>