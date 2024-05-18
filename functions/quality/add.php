<?php

session_start();
require_once '../../config/config.php';
require_once BASE_PATH . '/includes/auth_validate.php';

// Funzione per ottenere l'istanza del database
$db = getDbInstance();

// Validazione del parametro GET 'cartellino'
$cartellino = filter_input(INPUT_GET, 'cartellino', FILTER_UNSAFE_RAW);
if (!$cartellino) {
    die('Cartellino non valido');
}

// Ottenimento delle informazioni dal database
$db->where('Cartel', $cartellino);
$informazione = $db->getOne("dati");
if (!$informazione) {
    die('Informazioni non trovate');
}

$db->where('sigla', $informazione["Ln"]);
$descrizioneLinea = $db->getOne("linee");

// Calcolo del nuovo valore per id
$db->orderBy("ID", "DESC");
$max_testid = $db->getValue("cq_testid", "MAX(ID)");
$new_testid = $max_testid + 1;

// Inclusione dell'header
require_once BASE_PATH . '/includes/header.php';
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h2 class="page-header page-action-links text-center"
                style="padding:5px; background-color:orange;border-radius:10px;color:White;">
                <?php echo htmlspecialchars($descrizioneLinea["descrizione"], ENT_QUOTES, 'UTF-8'); ?>
            </h2>
            <h2 class="page-header page-action-links text-left">Nuovo Test #<?php echo $new_testid; ?></h2>
        </div>
    </div>
    <hr>
    <h4 class="page-header page-action-links text-left" style="color:red;">
        ** Controllare il riepilogo prima di procedere, andando avanti l'operazione di controllo sarà registrata.
    </h4>
    <form class="form" action="cqtest.php" method="post" id="customer_form" enctype="multipart/form-data">
        <fieldset class="p-3">
            <div name="intestazione" class="p-3 mb-3 bg-light rounded">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="Codice" class="form-label">Codice Articolo</label>
                            <input type="text" name="Codice"
                                value="<?php echo htmlspecialchars($informazione['Articolo'], ENT_QUOTES, 'UTF-8'); ?>"
                                placeholder="Codice" class="form-control" required id="Codice" readonly>
                            <input type="text" name="Codice" value="<?php echo $descrizioneLinea["descrizione"]; ?>"
                                placeholder="" class="form-control" required id="descrizioneLinea" readonly hidden>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="Articolo" class="form-label">Articolo</label>
                            <input type="text" name="Articolo"
                                value="<?php echo htmlspecialchars($informazione['Descrizione Articolo'], ENT_QUOTES, 'UTF-8'); ?>"
                                placeholder="Descrizione Articolo" class="form-control" required id="Articolo" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="cliente" class="form-label">Cliente</label>
                            <input name="cliente"
                                value="<?php echo htmlspecialchars($informazione['Ragione Sociale'], ENT_QUOTES, 'UTF-8'); ?>"
                                placeholder="Cliente" class="form-control" type="text" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="cartellino" class="form-label"><b>Cartellino</b></label>
                            <input type="text" name="cartellino"
                                value="<?php echo htmlspecialchars($informazione['Cartel'], ENT_QUOTES, 'UTF-8'); ?>"
                                placeholder="Cartellino" class="form-control" required id="cartellino" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="commessa" class="form-label"><b>Commessa</b></label>
                            <input name="commessa"
                                value="<?php echo htmlspecialchars($informazione['Commessa Cli'], ENT_QUOTES, 'UTF-8'); ?>"
                                placeholder="Commessa non presente" class="form-control" type="text" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="Idrip" class="form-label">Operazione N#</label>
                            <input type="text" name="Idrip" value="<?php echo $new_testid; ?>"
                                placeholder="ID Riparazione" class="form-control" required id="Idrip" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="utente" class="form-label">Operatore</label>
                            <input name="utente"
                                value="<?php echo htmlspecialchars(strtoupper($_SESSION['username']), ENT_QUOTES, 'UTF-8'); ?>"
                                class="form-control" type="text" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="data" class="form-label">Data</label>
                            <input type="text" name="data" value="<?php echo date('d/m/Y'); ?>" placeholder="DD/MM/YYYY"
                                class="form-control" id="data" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="orario" class="form-label">Orario</label>
                            <input type="text" name="orario" value="<?php echo date('H:i'); ?>" class="form-control"
                                id="orario" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group floating-button">
                <button type="submit" class="btn btn-primary btn-lg">INIZIA TEST <i class="fas fa-play"></i></button>
            </div>
        </fieldset>
    </form>
</div>

<script>
    document.getElementById('customer_form').addEventListener('submit', function (event) {
        event.preventDefault();
        var cartellino = document.getElementById('cartellino').value;

        window.location.href = 'cqtest?cartellino=' + encodeURIComponent(cartellino);
    });
</script>

<?php include_once BASE_PATH . '/includes/footer.php'; ?>