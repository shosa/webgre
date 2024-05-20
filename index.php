<?php
session_start();
require_once './config/config.php';
require_once 'includes/auth_validate.php';
$tipoUtente = $_SESSION['admin_type'];
// Get DB instance. function is defined in config.php
$db = getDbInstance();

// Get Dashboard information
$numRiparazioni = $db->getValue("riparazioni", "Sum(QTA)");
$db->Where("stato", "IN ATTESA");
$numDaCompletare = $db->getValue("lanci", "Sum(paia)");

include_once ('includes/header.php');
?>

<div id="page-wrapper">
    <?php
    $db = getDbInstance();
    $querySettings = "SELECT value FROM settings WHERE item = 'modulo_produzione'";
    $resultSettings = $db->rawQuery($querySettings);
    $moduloProduzioneValue = !empty($resultSettings) ? intval($resultSettings[0]["value"]) : 0;
    ?>
    <div class="row">
        <?php if ($_SESSION['admin_type'] !== 'operatore'): ?>
            <?php if ($_SESSION['admin_type'] !== 'lavorante'): ?>
                <div class="col-lg-12">
                    <h1 class="page-header page-action-links text-left">Dashboard</h1>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-3">
                                    <i class="fad fa-list-ol fa-5x"></i>
                                </div>
                                <div class="col-9 text-right">
                                    <div class="h1">
                                        <b>
                                            <?php echo $numRiparazioni; ?>
                                        </b>
                                    </div>
                                    <div>Riparazioni</div>
                                </div>
                            </div>
                        </div>
                        <a href="../../functions/riparazioni/riparazioni.php"
                            class="card-footer text-white bg-white text-primary">
                            <span class="float-left ">Apri Elenco</span>
                            <span class="float-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </a>
                    </div>
                </div>
                <?php if ($moduloProduzioneValue == 1): ?>
                    <div class="col-lg-3 col-md-6">
                        <div class="card bg-primary text-white bg-warning">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-3">
                                        <i class="fad fa-shipping-timed fa-5x"></i>
                                    </div>
                                    <div class="col-9 text-right">
                                        <div class="h1">
                                            <b>
                                                <?php echo !empty($numDaCompletare) ? $numDaCompletare : 0; ?>
                                            </b>
                                        </div>
                                        <div>Da lanci da completare</div>
                                    </div>
                                </div>
                            </div>
                            <a href="../../functions/lanci/wait_lanci.php" class="card-footer text-white bg-white text-warning">
                                <span class="float-left">Apri Elenco</span>
                                <span class="float-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif ?>
        <?php endif ?>
    </div>

    <!-- TAB RIPARAZIONI solo per LAVORANTE -->
    <?php
    if ($tipoUtente == "lavorante") {
        // Esegui una query SQL per ottenere la somma dei campi richiesti
        $db = getDbInstance();
        $username = $_SESSION['username'];
        $query = "SELECT SUM(QTA) AS sumQTA
              FROM riparazioni
              JOIN lab_user ON riparazioni.LABORATORIO = lab_user.lab
              WHERE lab_user.user = '$username'";
        $result = $db->rawQuery($query);
        $sumQTA = (empty($result) || $result[0]['sumQTA'] === null) ? 0 : $result[0]['sumQTA'];

        echo '<div class="col-lg-12">
            <h1 class="page-header">Dashboard</h1>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <i class="fad fa-copy fa-5x"></i>
                        </div>
                        <div class="col-9 text-right">
                            <div class="h1"><b>' . $sumQTA . '</div>
                            <div>Riparazioni assegnate</div>
                        </div>
                    </div>
                </div>
                <a href="../../functions/lab_riparazioni/lab_riparazioni.php" class="card-footer text-white">
                    <span class="float-left">Apri Elenco</span>
                    <span class="float-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </a>
            </div>
        </div>';
    }
    ?>
    <!-- FINE TAB RIPARAZIONI solo per LAVORANTE -->
    <?php
    if ($tipoUtente == "lavorante"): ?>
        <?php
        $db = getDbInstance();
        $username = $_SESSION['username'];
        $query = "SELECT laboratori.ID
            FROM laboratori
            WHERE laboratori.Nome = (
                SELECT lab_user.lab
                FROM lab_user
                WHERE lab_user.user = '$username'
            )";
        $result = $db->rawQuery($query);
        $idLab = $result[0]['ID'];

        $query = "SELECT SUM(paia) AS sumQTA
              FROM lanci
              WHERE lanci.id_lab = '$idLab' AND stato != 'IN ATTESA'";
        $result = $db->rawQuery($query);
        $sumQTA = (empty($result) || $result[0]['sumQTA'] === null) ? 0 : $result[0]['sumQTA'];
        ?>
        <div class="col-lg-3 col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <i class="fad fa-tasks fa-5x"></i>
                        </div>
                        <div class="col-9 text-right">
                            <b>
                                <div class="h1">
                                    <?php echo $sumQTA; ?>
                                </div>
                                <div>Lanci in lavoro</div>
                        </div>
                    </div>
                </div>
            </div>
            <a href="../../functions/lab_lanci/lab_lanci.php" class="card-footer text-white">
                <span class="float-left">Gestici Avanzamento</span>
                <span class="float-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
            </a>
        </div>
    </div>

    <?php
    $db = getDbInstance();
    $username = $_SESSION['username'];
    $query = "SELECT laboratori.ID
            FROM laboratori
            WHERE laboratori.Nome = (
                SELECT lab_user.lab
                FROM lab_user
                WHERE lab_user.user = '$username'
            )";
    $result = $db->rawQuery($query);
    $idLab = $result[0]['ID'];

    $query = "SELECT SUM(paia) AS sumQTA
              FROM lanci
              WHERE lanci.id_lab = '$idLab' AND stato = 'IN ATTESA'";
    $result = $db->rawQuery($query);
    $sumQTA = (empty($result) || $result[0]['sumQTA'] === null) ? 0 : $result[0]['sumQTA'];
    ?>
    <div class="col-lg-3 col-md-6">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="row">
                    <div class="col-3">
                        <i class="fad fa-clock fa-5x"></i>
                    </div>
                    <div class="col-9 text-right">
                        <b>
                            <div class="h1">
                                <?php echo $sumQTA; ?>
                            </div>
                            <div>Lanci in fase di preparazione</div>
                    </div>
                </div>
            </div>
        </div>
        <a href="../../functions/lab_lanci/lab_wait_lanci.php" class="card-footer text-white">
            <span class="float-left">Controlla</span>
            <span class="float-right"><i class="fa fa-arrow-circle-right"></i></span>
            <div class="clearfix"></div>
        </a>
    </div>
    </div>
<?php endif ?>

</div>

</div>
<!-- /#page-wrapper -->

<?php include_once ('includes/footer.php'); ?>