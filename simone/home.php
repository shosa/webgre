<?php
session_start();
include 'config/config.php';
$db = getDbInstance();
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PixelBook</title>
    <?php require ("content/header.php"); ?>
    <link rel="stylesheet" href="content/css/home.css">
</head>


<body>
 
    <!-- Navbar -->
    <?php include ("content/components/navbar.php") ?>
    <!-- Corpo della pagina -->
    <div class="container text-center mt-5">

    
        <h1 style="font-size:30pt;">
            Hire the <br><b style="color:#6610f2;font-size:60pt;">BEST</b><br>
            <small class="text-muted"> photographers in Dubai.</small>
        </h1>
        <p class="additional-text">Book your service in a <span style="color:#6610f2;">few seconds.</span></p>
        <button class="btn btn-primary " onclick="scrollToServices()">SAY CHEESE!</button>
        <div class="row mt-5">
            <div class="col-md-4 col-sm-12"> <!-- Modifica qui -->
                <p class="strength-left"><span class="number">1</span></p>
                <p class="number-text"> Choose your service.</p>
            </div>
            <div class="col-md-4 col-sm-12"> <!-- Modifica qui -->
                <p class="strength-center"><span class="number">2</span></p>
                <p class="number-text"> Decide where and when.</p>
            </div>
            <div class="col-md-4 col-sm-12"> <!-- Modifica qui -->
                <p class="strength-center"><span class="number">3</span></p>
                <p class="number-text"> Get in touch with us.</p>
            </div>
        </div>

    </div>
    <!-- Griglia di immagini -->
    <div class="container mt-5" id="services">
        <div class="row">
            <!-- Ciclo per generare le immagini della griglia -->
            <?php
            $sql = "SELECT nome_sezione, thumbnail FROM sezioni WHERE abilitato = 1";
            $result = $db->rawQuery($sql);

            if ($result) {
                foreach ($result as $row) {
                    $nome_sezione = $row["nome_sezione"];
                    $thumbnail = $row["thumbnail"];
                    ?>
                    <div class="col-md-3 mb-4">
                        <a href="view?service=<?php echo urlencode($nome_sezione); ?>" class="categoria-overlay">
                            <img src="src/img/<?php echo $nome_sezione ?>/<?php echo $thumbnail ?>"
                                alt="<?php echo $nome_sezione ?>" class="img-fluid grid-img">
                            <p class="nomeCategoria text-center"><?php echo $nome_sezione ?></p>
                        </a>
                    </div>
                    <?php
                }
            } else {
                echo "Nessun risultato trovato";
            }
            ?>
        </div>
    </div>
    <!-- Riepilogo qualita -->
    <div class="container mt-5">
        <div class="row quality-summary">
            <div class="col-md-4 mb-4">
                <i class="fad fa-camera-retro fa-3x" style="color:#6610f2;"></i>
                <p>The most convenient way to hire top photographers</p>
            </div>
            <div class="col-md-4 mb-4">
                <i class="fad fa-star fa-3x" style="color:#6610f2;"></i>
                <p>Highly rated photographers & videographers</p>
            </div>
            <div class="col-md-4 mb-4">
                <i class="fad fa-check-circle fa-3x" style="color:#6610f2;"></i>
                <p>All photographers are experts in their categories</p>
            </div>
            <div class="col-md-4 mb-4">
                <i class="fad fa-shield-alt fa-3x" style="color:#6610f2;"></i>
                <p>Satisfaction guaranteed or free replacement</p>
            </div>
            <div class="col-md-4 mb-4">
                <i class="fad fa-lock fa-3x" style="color:#6610f2;"></i>
                <p>Safe online payments. Pay 80% after your shoot.</p>
            </div>
            <div class="col-md-4 mb-4">
                <i class="fad fa-balance-scale fa-3x" style="color:#6610f2;"></i>
                <p>Fair market rate for a superior quality</p>
            </div>
        </div>
    </div>
</body>
<script src="content/js/home.js"></script>
<?php require ("content/footer.php"); ?>

</html>