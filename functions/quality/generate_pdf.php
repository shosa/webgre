<?php
session_start();
require_once '../../config/config.php';
require_once BASE_PATH . '/includes/auth_validate.php';

// Recupera la data selezionata dall'URL
$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

// Recupera i dati relativi alla data selezionata dal database
$db = getDbInstance();
$db->where('data', $date);
$data = $db->get('cq_records');

// Include la libreria TCPDF
require_once ('../../assets/tcpdf/tcpdf.php');
require_once ('../../assets/tcpdf/tcpdf_barcodes_1d.php');

// Crea il contenuto del PDF
$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); // Orizzontale
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Emmegiemme');
$pdf->SetTitle('Report giornaliero');
$pdf->SetSubject('Controllo qualità');
$pdf->SetKeywords('TCPDF, PDF, report, giornaliero');

// Logo

$pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT, true);
$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

$pdf->SetFont('helvetica', '', 15);

$pdf->AddPage();


// Creazione del barcode
$barcodeOptions = array('code' => 'C128', 'display' => true, 'scale' => 0.75, 'text' => $date, 'align' => 'C', 'label' => false);
$barcode = $pdf->write1DBarcode($date, 'C128', '', '', '', 10, 0.3, $barcodeOptions);
$pdf->Ln();
// Titolo e data
$pdf->Cell(0, 10, 'TEST CONTROLLO QUALITA', 0, 0, 'L');
$pdf->Cell(0, 10, 'Data: ' . $date, 0, 1, 'R');
$logoPath = '../../src/img/logo.png'; // Assicurati che il percorso sia corretto
$pdf->Image($logoPath, 200, 10, 70, '', 'PNG');
$uniqueCartellini = array();
$totalPa = 0;

// Calcola la somma totale dei PA
foreach ($data as $record) {
    $cartellino = $record['cartellino'];
    $pa = $record['pa'];

    if (!isset($uniqueCartellini[$cartellino])) {
        $uniqueCartellini[$cartellino] = 0;
    }

    $uniqueCartellini[$cartellino] += $pa;
}

$totalPa = array_sum($uniqueCartellini); // Somma totale dei PA
$negativeEsiti = 0;

foreach ($data as $record) {
    if ($record['esito'] === 'X') {
        $negativeEsiti++;
    }
}
if ($totalPa > 0) {
    $negativePercentage = ($negativeEsiti / $totalPa) * 100;
} else {
    $negativePercentage = 0; // Se non ci sono pa, la percentuale di esiti negativi è 0%
}

$pdf->SetFont('helvetica', '', 12);
// Aggiungi la percentuale di scarto dopo la riga vuota
$pdf->Cell(0, 10, 'Percentuale di scarto registrata: ' . $negativePercentage . '%', 0, 1, 'L');

$pdf->SetFont('helvetica', '', 12);
// Calcola la larghezza massima per ogni colonna
$colWidths = array_fill(0, 7, 0); // Array inizializzato con 7 colonne
$columnTitles = array('N° TEST', 'CARTELLINO/COMMESSA', 'ARTICOLO', 'CALZATA', 'ORA', 'TEST', 'ANNOTAZIONI', 'ESITO');

foreach ($columnTitles as $index => $title) {
    $colWidths[$index] = $pdf->GetStringWidth($title);
}

foreach ($data as $record) {
    $colWidths[0] = max($colWidths[0], $pdf->GetStringWidth($record['testid']));
    $colWidths[1] = max($colWidths[1], $pdf->GetStringWidth($record['cartellino'] . ' / ' . $record['commessa']));
    $colWidths[2] = max($colWidths[2], $pdf->GetStringWidth($record['articolo']) / 1.4);
    $colWidths[3] = max($colWidths[3], $pdf->GetStringWidth($record['calzata']));
    $colWidths[4] = max($colWidths[4], $pdf->GetStringWidth($record['orario']));
    // Calcola la larghezza massima per il testo e le note
    $colWidths[5] = max($colWidths[5], $pdf->GetStringWidth($record['test']) / 1.1);
    $colWidths[6] = max($colWidths[6], $pdf->GetStringWidth($record['note']) / 2);
    $colWidths[7] = max($colWidths[7], $pdf->GetStringWidth($record['esito']));
}

// Disegna la tabella
$pdf->SetFont('helvetica', '', 8);
$pdf->SetFillColor(191, 245, 243);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetLineWidth(0.1);
$pdf->setFont('', 'B');
$fill = false; // Variabile di controllo per il colore di sfondo delle righe

foreach ($colWidths as $index => $width) {
    // Se l'intestazione è "ESITO", la larghezza deve essere doppia
    $cellWidth = $index === 7 ? $width * 2 : $width;
    $pdf->Cell($cellWidth, 10, $columnTitles[$index], 1, 0, 'C', 1); // Titoli delle colonne
}
$pdf->Ln();
$pdf->SetFillColor(255, 255, 255);
$pdf->SetTextColor(0);
$pdf->setFont('', '');

foreach ($data as $record) {
    // Imposta il colore di sfondo della riga
    $pdf->SetFillColor($fill ? 240 : 255);
    $pdf->Cell($colWidths[0], 10, $record['testid'], 1, 0, 'C', $fill);
    $pdf->Cell($colWidths[1], 10, $record['cartellino'] . ' / ' . $record['commessa'], 1, 0, 'C', $fill);
    $pdf->Cell($colWidths[2], 10, $record['articolo'], 1, 0, 'C', $fill);
    $pdf->Cell($colWidths[3], 10, $record['calzata'], 1, 0, 'C', $fill);
    $pdf->Cell($colWidths[4], 10, $record['orario'], 1, 0, 'C', $fill);
    $pdf->MultiCell($colWidths[5], 10, $record['test'], 1, 'C', $fill, 0);
    $pdf->MultiCell($colWidths[6], 10, $record['note'], 1, 'C', $fill, 0);
    // Aggiungiamo due celle sotto ESITO
    if ($record['esito'] === 'V') {
        $pdf->Cell($colWidths[7], 10, '', 1, 0, 'C', $fill);
        $pdf->Cell($colWidths[7], 10, 'V', 1, 1, 'C', $fill);
    } elseif ($record['esito'] === 'X') {
        $pdf->Cell($colWidths[7], 10, 'X', 1, 0, 'C', $fill);
        $pdf->Cell($colWidths[7], 10, '', 1, 1, 'C', $fill);
    } else {
        $pdf->Cell($colWidths[7], 10, '', 1, 0, 'C', $fill);
        $pdf->Cell($colWidths[7], 10, '', 1, 1, 'C', $fill);
    }
    // Cambia il colore di sfondo per la riga successiva
    $fill = !$fill;
}
// Output del PDF



$pdf->Output('CQ DEL' . $record['data'] . '.pdf', 'I');