<?php

session_start();
require_once '../../config/config.php';
require_once BASE_PATH . '/includes/auth_validate.php';
$db = getDbInstance();
$cartellino = filter_input(INPUT_GET, 'cartellino');
$db->where('Cartel', $cartellino);
$query = $db->getLastQuery();
echo $query;
$informazione = $db->getOne("dati");
$db->where('sigla', $informazione["Ln"]);
$descrizioneLinea = $db->getOne("linee");

require_once BASE_PATH . '/includes/header.php';
$db->orderBy("ID", "DESC");
$max_testid = $db->getValue("cq_testid", "MAX(ID)");

// Calcola il nuovo valore per id
$new_testid = $max_testid + 1;
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h2 class="page-header page-action-links text-center"
                style="padding:5px; background-color:orange;border-radius:10px;color:White;">
                <?php echo $descrizioneLinea["descrizione"]; ?>
            </h2>
            <h2 class="page-header page-action-links text-left">Nuovo Test #<?php echo $new_testid; ?></h2>
        </div>
    </div>
    <hr>
    <h4 class="page-header page-action-links text-left" style="color:red;">
        ** Controllare il riepilogo prima di procedere, andando avanti l'operazione di controllo sarà registrata.
    </h4>
    <form class="form" action="" method="post" id="customer_form" enctype="multipart/form-data">
        <?php include ("forms/add_form.php"); ?>
    </form>
</div>

<script>
    document.getElementById('start_test_button').addEventListener('click', function () {
        var cartellino = document.getElementById('cartellino').value;
        window.location.href = 'cqtest?cartellino=' + encodeURIComponent(cartellino);
    });
</script>

<?php include_once BASE_PATH . '/includes/footer.php'; ?>