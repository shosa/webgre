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
$orario = date('H:i');

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
<style>
    #test_table td {
        vertical-align: middle;
    }

    .esito-btn.active {
        border-color: transparent !important;
        box-shadow: 0 0 0 0.2rem rgba(0, 255, 0, 0.5), 0 0 0 0.2rem rgba(0, 0, 0, 0.125) !important;
        /* Verde per V */
    }

    .esito-btn.active[data-value="X"] {
        box-shadow: 0 0 0 0.2rem rgba(255, 0, 0, 0.5), 0 0 0 0.2rem rgba(0, 0, 0, 0.125) !important;
        /* Rosso per X */
    }

    .esito-btn.active i {
        color: inherit !important;
    }

    .esito-btn.active[data-value="V"] i {
        color: green !important;
        /* Colore verde per V */
    }

    .esito-btn.active[data-value="X"] i {
        color: red !important;
        /* Colore rosso per X */
    }
</style>

<div id="page-wrapper">
    <div class="col-lg-12">
        <h2 class="page-header page-action-links text-left">**Nuovo Test**
            <span style="background-color:#ededed;padding:5px;border-radius:10px;margin:5px;">
                (Cartellino: <?php echo htmlspecialchars($informazione["Cartel"], ENT_QUOTES, 'UTF-8'); ?>
                - Commessa: <?php echo htmlspecialchars($informazione["Commessa Cli"], ENT_QUOTES, 'UTF-8'); ?>)
            </span><span class="page-header page-action-links text-right"
                style="background-color:orange; padding:5px;border-radius:10px;color:White;margin:5px;"><?php echo $orario ?></span>
        </h2>
    </div>
    <hr>

    <form action="process_test.php" method="post" id="test_form">
        <input type="hidden" name="cartellino"
            value="<?php echo htmlspecialchars($cartellino, ENT_QUOTES, 'UTF-8'); ?>">
        <div class="table-responsive">
            <table class="table table-bordered" id="test_table">
                <thead class="table-info">
                    <tr">
                        <th width="10%">CALZATA</th>
                        <th width="40%">TEST</th>
                        <th width="40%">ANNOTAZIONI</th>
                        <th width="10%">ESITO</th>
                        </tr>
                </thead>
                <tbody>
                    <?php for ($i = 0; $i < 5; $i++): ?>
                        <tr data-row-id="<?php echo $i; ?>">
                            <td>
                                <select name="calzata[]" class="form-control">
                                    <option value=""></option>
                                    <?php foreach ($calzateOptions as $option): ?>
                                        <option value="<?php echo $option; ?>"><?php echo $option; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <button type="button" class="btn btn-primary barcode-btn">
                                    <i class="fas fa-barcode"></i>
                                </button>
                                <button type="button" class="btn btn-secondary pen-btn">
                                    <i class="fas fa-pen"></i>
                                </button>
                                <input type="hidden" name="test[]" class="test-input">
                            </td>
                            <td>
                                <textarea name="note[]" class="form-control"></textarea>
                            </td>
                            <td>
                                <button type="button" class="btn btn-light esito-btn" data-value="V">
                                    <i class="fas fa-check-circle"></i>
                                </button>
                                <button type="button" class="btn btn-light esito-btn" data-value="X">
                                    <i class="fas fa-times-circle"></i>
                                </button>
                                <input type="hidden" name="esito[]" class="esito-input">
                            </td>
                            <input type="hidden" name="row_ids[]" class="row-id-input">
                        </tr>
                    <?php endfor; ?>
                </tbody>
            </table>
        </div>

        <div class="form-group text-center">
            <button type="submit" class="btn btn-primary">Salva</button>
        </div>
    </form>
</div>

<!-- Modal -->
<div class="modal fade" id="barcodeModal" tabindex="-1" role="dialog" aria-labelledby="barcodeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="barcodeModalLabel">Sparare il codice del test eseguito</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="text" id="barcodeInput" class="form-control" placeholder="Sparare il codice qui">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
                <button type="button" class="btn btn-primary" id="saveBarcode">Salva</button>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        var currentInput;
        var timeoutId;

        document.querySelectorAll('.barcode-btn').forEach(function (button) {
            button.addEventListener('click', function () {
                currentInput = this.nextElementSibling.nextElementSibling;
                $('#barcodeModal').modal('show');
            });
        });

        $('#barcodeModal').on('shown.bs.modal', function () {
            $('#barcodeInput').focus();
        });

        document.getElementById('barcodeInput').addEventListener('input', function () {
            clearTimeout(timeoutId); // Cancella il timeout precedente se presente
            var barcodeValue = this.value.trim();
            if (barcodeValue && currentInput) {
                timeoutId = setTimeout(function () {
                    var url = 'process_barcode.php?barcode=' + encodeURIComponent(barcodeValue);
                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                currentInput.value = data.test;
                                currentInput.previousElementSibling.previousElementSibling.textContent = data.test;
                                $('#barcodeModal').modal('hide');
                                // Svuota il contenuto del campo di testo dopo il salvataggio
                                document.getElementById('barcodeInput').value = '';
                            } else {
                                alert(data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Errore:', error);
                            alert('Si è verificato un errore durante il recupero del test.');
                        });
                }, 750); // Ritardo di 1 secondo (1000 millisecondi)
            } else {
                alert('Inserisci un codice a barre valido');
            }
        });

        document.querySelectorAll('.pen-btn').forEach(function (button) {
            button.addEventListener('click', function () {
                var input = prompt("Inserisci manualmente il test:");
                if (input) {
                    var testInput = this.nextElementSibling;
                    testInput.value = input;
                    this.previousElementSibling.textContent = input;
                }
            });
        });
    });
    function handleEsitoButtonClick() {
        // Rimuove la classe attiva da tutti i pulsanti
        var esitoButtons = this.parentNode.querySelectorAll('.esito-btn');
        esitoButtons.forEach(function (btn) {
            btn.classList.remove('active');
        });

        // Aggiunge la classe attiva al pulsante cliccato
        this.classList.add('active');

        // Imposta il valore dell'esito nell'input nascosto
        var esitoInput = this.parentNode.querySelector('.esito-input');
        esitoInput.value = this.getAttribute('data-value');
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Abilita gli eventi sui pulsanti esito nella riga iniziale
        var esitoButtons = document.querySelectorAll('#test_table .esito-btn');
        esitoButtons.forEach(function (button) {
            button.addEventListener('click', handleEsitoButtonClick);
        });

        // Disabilita tutti gli elementi del form tranne i pulsanti "Aggiungi Riga" e "Salva"
        document.querySelectorAll('#test_form input, #test_form button ,#test_form textarea').forEach(function (element) {
            element.disabled = true;
        });

        // Abilita i pulsanti "Aggiungi Riga" e "Salva"
        document.querySelectorAll('#add_row, #test_form button[type="submit"]').forEach(function (button) {
            button.disabled = false;
        });

        document.querySelectorAll('#test_form select[name="calzata[]"]').forEach(function (select) {
            select.addEventListener('change', function () {
                var selectedOption = this.value;
                var currentRow = this.closest('tr');

                // Abilita gli elementi del form nella riga corrente solo se la calzata è stata selezionata
                if (selectedOption) {
                    currentRow.querySelectorAll('input,button, textarea').forEach(function (element) {
                        element.disabled = false;
                    });
                } else {
                    // Se non viene selezionata alcuna calzata, disabilita gli elementi del form nella riga corrente
                    currentRow.querySelectorAll('input,button, textarea').forEach(function (element) {
                        element.disabled = true;
                    });
                }
            });
        });
    });
    document.getElementById('save_button').addEventListener('click', function () {
        var formData = new FormData();
        var rows = document.querySelectorAll('#test_table tbody tr');

        rows.forEach(function (row) {
            var rowId = row.getAttribute('data-row-id');
            var calzata = row.querySelector('select[name="calzata[]"]').value;
            var test = row.querySelector('input[name="test[]"]').value;
            var note = row.querySelector('textarea[name="note[]"]').value;
            var esito = row.querySelector('.esito-btn.active').getAttribute('data-value');

            formData.append('row_ids[]', rowId);
            formData.append('calzata[]', calzata);
            formData.append('test[]', test);
            formData.append('note[]', note);
            formData.append('esito[]', esito);
        });

        fetch('process_save.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Dati salvati con successo!');
                } else {
                    alert('Si è verificato un errore durante il salvataggio dei dati.');
                }
            })
            .catch(error => {
                console.error('Errore:', error);
                alert('Si è verificato un errore durante il salvataggio dei dati.');
            });
    });
</script>

<?php include_once BASE_PATH . '/includes/footer.php'; ?>