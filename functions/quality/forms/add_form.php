<fieldset class="p-3">
    <?php
    $db->orderBy("ID", "DESC");
    $max_testid = $db->getValue("cq_testid", "MAX(ID)");
    $new_testid = $max_testid + 1;
    ?>
    <div name="intestazione" class="p-3 mb-3 bg-light rounded">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="Codice" class="form-label">Codice Articolo</label>
                    <input type="text" name="Codice"
                        value="<?php echo htmlspecialchars($informazione['Articolo'], ENT_QUOTES, 'UTF-8'); ?>"
                        placeholder="Codice" class="form-control" required id="Codice" readonly>
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
                    <input type="text" name="Idrip" value="<?php echo $new_testid ?>" placeholder="ID Riparazione"
                        class="form-control" required id="Idrip" readonly>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="utente" class="form-label">Operatore</label>
                    <input name="utente" value="<?php echo strtoupper($_SESSION['username']); ?>" class="form-control"
                        type="text" readonly>
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
                    <label for="data" class="form-label">Orario</label>
                    <input type="text" name="data" value="<?php echo date('H:i'); ?>" placeholder="DD/MM/YYYY"
                        class="form-control" id="data" readonly>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group floating-button">
        <button type="submit" class="btn btn-primary btn-lg">INIZIA TEST <i class="fas fa-play"></i></button>
    </div>
</fieldset>