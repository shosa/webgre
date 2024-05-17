<?php
session_start();
require_once '../../config/config.php';
require_once BASE_PATH . '/includes/auth_validate.php';

$db = getDbInstance();

// Recupera la variabile cartellino dall'URL
$cartellino = filter_input(INPUT_GET, 'cartellino', FILTER_UNSAFE_RAW);

// Ottiene l'informazione dalla tabella 'dati'
$db->where('Cartel', $cartellino);
$informazione = $db->getOne("dati");

// Ottiene le opzioni per il menu a tendina 'CALZATA' dalla tabella 'id_numerate'
$calzateOptions = [];
if (!empty($informazione["Nu"])) {
    $db->where('id', $informazione["Nu"]);
    $idNumerate = $db->getOne('id_numerate');
    if ($idNumerate) {
        for ($j = 1; $j <= 20; $j++) {
            $field = 'N' . str_pad($j, 2, '0', STR_PAD_LEFT);
            if (!empty($idNumerate[$field])) {
                $calzateOptions[] = htmlspecialchars($idNumerate[$field], ENT_QUOTES, 'UTF-8');
            }
        }
    }
}

// Includi l'header
require_once BASE_PATH . '/includes/header.php';
?>

<div id="page-wrapper">
    <div class="col-lg-12">
        <h2 class="page-header page-action-links text-left">Nuovo Test
            <span style="background-color:#ededed;padding:5px;border-radius:10px;">
                (Cartellino: <?php echo htmlspecialchars($informazione["Cartel"], ENT_QUOTES, 'UTF-8'); ?>
                - Commessa: <?php echo htmlspecialchars($informazione["Commessa Cli"], ENT_QUOTES, 'UTF-8'); ?>)
            </span>
        </h2>
    </div>
    <hr>

    <form action="process_test.php" method="post" id="test_form">
        <input type="hidden" name="cartellino"
            value="<?php echo htmlspecialchars($cartellino, ENT_QUOTES, 'UTF-8'); ?>">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="test_table">
                <thead>
                    <tr>
                        <th width="10%">CALZATA</th>
                        <th width="60%">TEST</th>
                        <th width="30%">ESITO</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($i = 0; $i < 5; $i++): ?>
                        <tr>
                            <td>
                                <select name="calzata[]" class="form-control">
                                    <option value=""></option>
                                    <?php foreach ($calzateOptions as $option): ?>
                                        <option value="<?php echo $option; ?>"><?php echo $option; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <textarea name="test[]" class="form-control" rows="2"></textarea>
                            </td>
                            <td>
                                <select name="esito[]" class="form-control">
                                    <option value="V">V</option>
                                    <option value="X">X</option>
                                </select>
                            </td>
                        </tr>
                    <?php endfor; ?>
                </tbody>
            </table>
        </div>
        <div class="form-group text-center">
            <button type="button" class="btn btn-success" id="add_row">+</button>
            <button type="submit" class="btn btn-primary">Salva</button>
        </div>
    </form>
</div>

<script>
    document.getElementById('add_row').addEventListener('click', function () {
        var table = document.getElementById('test_table').getElementsByTagName('tbody')[0];
        var newRow = table.insertRow();
        var calzataCell = newRow.insertCell(0);
        var testCell = newRow.insertCell(1);
        var esitoCell = newRow.insertCell(2);

        // Crea il menu a tendina calzata con l'opzione vuota
        var calzataSelect = document.createElement('select');
        calzataSelect.name = 'calzata[]';
        calzataSelect.className = 'form-control';
        var emptyOption = document.createElement('option');
        emptyOption.value = '';
        emptyOption.text = '';
        calzataSelect.appendChild(emptyOption);

        <?php foreach ($calzateOptions as $option): ?>
            var option = document.createElement('option');
            option.value = '<?php echo $option; ?>';
            option.text = '<?php echo $option; ?>';
            calzataSelect.appendChild(option);
        <?php endforeach; ?>

        calzataCell.appendChild(calzataSelect);

        // Aggiungi il campo di testo multiriga per il test
        var testTextarea = document.createElement('textarea');
        testTextarea.name = 'test[]';
        testTextarea.className = 'form-control';
        testTextarea.rows = 2;
        testCell.appendChild(testTextarea);

        // Aggiungi il menu a tendina per l'esito
        var esitoSelect = document.createElement('select');
        esitoSelect.name = 'esito[]';
        esitoSelect.className = 'form-control';
        var optionV = document.createElement('option');
        optionV.value = 'V';
        optionV.text = 'V';
        var optionX = document.createElement('option');
        optionX.value = 'X';
        optionX.text = 'X';
        esitoSelect.appendChild(optionV);
        esitoSelect.appendChild(optionX);
        esitoCell.appendChild(esitoSelect);
    });
</script>

<?php include_once BASE_PATH . '/includes/footer.php'; ?>