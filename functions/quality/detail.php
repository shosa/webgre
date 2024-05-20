<?php
session_start();
require_once '../../config/config.php';
require_once BASE_PATH . '/includes/auth_validate.php';
require_once BASE_PATH . '/includes/header.php';

// Recupera la data selezionata, se presente
$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

// Recupera i dati relativi alla data selezionata dal database
$db = getDbInstance();
$db->where('data', $date);
$data = $db->get('cq_records');

?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-6">
            <h2 class="page-header page-action-links text-left">Test effettuati in data <span
                    class="badge bg-primary text-white" style="margin-left: 10px;"><?php echo $date; ?> </h2>
            </span>
            </h2>
        </div>
        <div class="col-lg-6">
            <div class="page-action-links text-right">
                <a href="generate_pdf.php?date=<?php echo $date; ?>" class="btn btn-info" style="font-size:18pt;"><i
                        class="fas fa-print"></i> STAMPA REPORT</a>

            </div>
        </div>
    </div>
    <hr>

    <!-- Mostra i dati relativi alla data selezionata -->
    <div class="row">
        <div class="col-lg-12">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Cartellino</th>
                        <th>Commessa</th>
                        <th>Reparto</th>
                        <th>Articolo</th>
                        <th>Modello</th>
                        <th>Calzata</th>
                        <th>Esito</th>
                        <!-- Aggiungi altre colonne se necessario -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $record): ?>
                        <tr>
                            <td><?php echo $record['testid']; ?></td>
                            <td><?php echo $record['cartellino']; ?></td>
                            <td><?php echo $record['commessa']; ?></td>
                            <td><?php echo $record['reparto']; ?></td>
                            <td><?php echo $record['cod_articolo']; ?></td>
                            <td><?php echo $record['articolo']; ?></td>
                            <td><?php echo $record['calzata']; ?></td>
                            <td <?php echo ($record['esito'] == 'V') ? 'style=" text-align:center; background-color: #b8ffba; color: green;"' : 'style="text-align:center;background-color: #ffb8c1; color: red; "'; ?>>
                                <?php echo $record['esito']; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include_once BASE_PATH . '/includes/footer.php'; ?>