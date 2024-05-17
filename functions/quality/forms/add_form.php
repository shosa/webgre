<fieldset>
    <?php
    $db->orderBy("ID", "DESC");
    $max_testid = $db->getValue("cq_testid", "MAX(ID)");
    $new_testid = $max_testid + 1;
    ?>
    <div name="intestazione" style="padding:10px;">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="Codice">Codice Articolo</label>
                    <input type="text" name="Codice"
                        value="<?php echo htmlspecialchars($informazione['Articolo'], ENT_QUOTES, 'UTF-8'); ?>"
                        placeholder="Codice" class="form-control" required="required" id="Codice" readonly>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Cliente</label>
                    <input name="cliente"
                        value="<?php echo htmlspecialchars($informazione['Ragione Sociale'], ENT_QUOTES, 'UTF-8'); ?>"
                        placeholder="Birth date" class="form-control" type="cliente" readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-11">
                <div class="form-group">
                    <label for="Articolo">Articolo</label>
                    <input type="text" name="Articolo"
                        value="<?php echo htmlspecialchars($informazione['Descrizione Articolo'], ENT_QUOTES, 'UTF-8'); ?>"
                        placeholder="Descrizione Articolo" class="form-control" required="required" id="Articolo"
                        readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="cartellino"><b>Cartellino</b></label>
                    <input type="text" name="cartellino"
                        value="<?php echo htmlspecialchars($informazione['Cartel'], ENT_QUOTES, 'UTF-8'); ?>"
                        placeholder="Last Name" class="form-control" required="required" id="cartellino" readonly>
                </div>

            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label><b>Commessa</b></label>
                    <input name="commessa"
                        value="<?php echo htmlspecialchars($informazione['Commessa Cli'], ENT_QUOTES, 'UTF-8'); ?>"
                        placeholder="Commessa non presente" class="form-control" type="commessa" readonly>
                </div>
            </div>
        </div>
    </div>
    <div name="inserimento" style="background-color:#f4f7f9; border-radius:10px; padding:10px;">
        <div class="form-group">
            <label>Numerata</label>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-condensed">
                    <tr>
                        <?php
                        // Recupera i dati dalla tabella id_numerate utilizzando MysqliDb
                        $db->where('ID', $informazione['Nu']);
                        $idNumerateData = $db->getOne('id_numerate');
                        // Cicla attraverso i campi N01, N02, ecc. e crea le celle della tabella
                        for ($i = 1; $i <= 20; $i++) {
                            $fieldName = 'N' . str_pad($i, 2, '0', STR_PAD_LEFT); // Costruisci il nome del campo N01, N02, ecc.
                            // Aggiungi l'attributo 'disabled' ai campi N01, N02, ecc.
                            echo '<td style="width:50px;height:30px;font-weight:bold;color:white;background-color:#6610f2;border:solid 0.5pt black; text-align:center;"><span>' . htmlspecialchars($idNumerateData[$fieldName], ENT_QUOTES, 'UTF-8');
                            // Verifica se stai visualizzando il modulo in modalità di modifica ed escludi l'attributo 'disabled' in tal caso
                            echo '</span></td>';
                        }
                        ?>
                    </tr>
                    <tr
                        style="height:20px;font-weight:bold;color:black;background-color:#ededed;border:solid 0.5pt black; text-align:center;">
                        <!--CARICAMENTO VALORI P01...P20-->
                        <?php
                        for ($i = 1; $i <= 20; $i++) {
                            $fieldName = 'P' . str_pad($i, 2, '0', STR_PAD_LEFT); // Costruisci il nome del campo P01, P02, ecc.
                            $fieldValue = isset($informazione[$fieldName]) ? htmlspecialchars($informazione[$fieldName], ENT_QUOTES, 'UTF-8') : '';
                            echo '<td style="width:50px;"><span>' . $fieldValue . '</span></td>';
                        }
                        ?>
                    </tr>
                </table>

            </div>
        </div>
   
      

    </div>
    <div name="finale" style="padding:10px;">

        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="Articolo">ID Riparazione</label>
                    <input type="text" name="Idrip" value="<?php echo $new_testid ?>" placeholder="Idrip"
                        class="form-control" required="required" id="Idrip" readonly>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="nu">Numerata</label>
                    <input name="nu" value="<?php echo htmlspecialchars($informazione['Nu'], ENT_QUOTES, 'UTF-8'); ?>"
                        placeholder="" class="form-control" type="text" id="nu" readonly>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Utente</label>
                    <input name="utente" value="<?php echo strtoupper($_SESSION['username']); ?>"
                        placeholder="Birth date" class="form-control" type="utente" readonly>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="data">Data</label>
                    <input type="text" name="data" value="<?php echo date('d/m/Y'); ?>" placeholder="DD/MM/YYYY"
                        class="form-control" id="data" readonly>
                </div>
            </div>
        </div>
        <div class="form-group text-center">
            <label></label>
            <button type="submit" class="btn btn-warning">Salva <span class="glyphicon glyphicon-send"></span></button>
        </div>
    </div>
</fieldset>