//----------------------------------------------------------------------------
//
//  $Id: PreviewAndPrintLabel.js 38773 2015-09-17 11:45:41Z nmikalko $ 
//
// Project -------------------------------------------------------------------
//
//  DYMO Label Framework
//
// Content -------------------------------------------------------------------
//
//  DYMO Label Framework JavaScript Library Samples: Preview and Print label
//
//----------------------------------------------------------------------------
//
//  Copyright (c), 2010, Sanford, L.P. All Rights Reserved.
//
//----------------------------------------------------------------------------


(function () {
    // stores loaded label info
    var label;
    var _printers = [];

    function createPrintersTableRow(table, name, value) {
        var row = document.createElement("tr");

        var cell1 = document.createElement("td");
        cell1.appendChild(document.createTextNode(name + ': '));

        var cell2 = document.createElement("td");
        cell2.appendChild(document.createTextNode(value));

        row.appendChild(cell1);
        row.appendChild(cell2);

        table.appendChild(row);
    }

    function populatePrinterDetail() {
        var printerDetail = document.getElementById("printerDetail");
        printerDetail.innerHTML = "";

        var myPrinter = _printers[document.getElementById("printersSelect").value];
        if (myPrinter === undefined)
            return;

        var table = document.createElement("table");
        createPrintersTableRow(table, 'Tipo di stampante', myPrinter['printerType'])
        createPrintersTableRow(table, 'Nome Stampante', myPrinter['name'])
        createPrintersTableRow(table, 'Modello', myPrinter['modelName'])
        createPrintersTableRow(table, 'Locale', myPrinter['isLocal'])
        createPrintersTableRow(table, 'Connessione', myPrinter['isConnected'])

        dymo.label.framework.is550PrinterAsync(myPrinter.name).then(function (isRollStatusSupported) {
            //fetch one consumable information in the printer list.
            if (isRollStatusSupported) {
                dymo.label.framework.getConsumableInfoIn550PrinterAsync(myPrinter.name).then(function (consumableInfo) {
                    createPrintersTableRow(table, 'SKU-Etichette', consumableInfo['sku'])
                    createPrintersTableRow(table, 'Nome Etichette', consumableInfo['name'])
                    createPrintersTableRow(table, 'Etichette rimanenti', consumableInfo['labelsRemaining'])
                    createPrintersTableRow(table, 'Roll inserito', consumableInfo['rollStatus'])
                }).thenCatch(function (e) {
                    createPrintersTableRow(table, 'SKU-Etichette', 'n/a')
                    createPrintersTableRow(table, 'Nome Etichette', 'n/a')
                    createPrintersTableRow(table, 'Etichette rimanenti', 'n/a')
                    createPrintersTableRow(table, 'Roll inserito', 'n/a')
                })
            } else {
                createPrintersTableRow(table, 'IsRollStatusSupported', 'False')
            }
        }).thenCatch(function (e) {
            createPrintersTableRow(table, 'IsRollStatusSupported', e.message)
        })

        printerDetail.appendChild(table);
    }

    // called when the document completly loaded
    function onload() {


        var printersSelect = document.getElementById('printersSelect');
        var printButton = document.getElementById('printButton');


        // initialize controls



        // Generates label preview and updates corresponend <img> element
        // Note: this does not work in IE 6 & 7 because they don't support data urls
        // if you want previews in IE 6 & 7 you have to do it on the server side


        // loads all supported printers into a combo box 
        function loadPrintersAsync() {
            _printers = [];
            dymo.label.framework.getPrintersAsync().then(function (printers) {
                if (printers.length == 0) {
                    alert("No DYMO printers are installed. Install DYMO printers.");
                    return;
                }
                _printers = printers;
                printers.forEach(function (printer) {
                    let printerName = printer["name"];
                    let option = document.createElement("option");
                    option.value = printerName;
                    option.appendChild(document.createTextNode(printerName));
                    printersSelect.appendChild(option);
                });
                populatePrinterDetail();
            }).thenCatch(function (e) {
                alert("Load Printers failed: " + e);;
                return;
            });
        }

        /* returns current address on the label 
        function getAddress() {
            if (!label || label.getAddressObjectCount() == 0)
                return "";

            return label.getAddressText(0);
        }

        // set current address on the label 
        function setAddress(address) {
            if (!label || label.getAddressObjectCount() == 0)
                return;

            return label.setAddressText(0, address);
        }
*/
        // loads label file thwn user selects it in file open dialog

        var testAddressLabelXml = '<?xml version="1.0" encoding="utf-8"?>\
        <DieCutLabel Version="8.0" Units="twips">\
            <PaperOrientation>Portrait</PaperOrientation>\
            <Id>Small30334</Id>\
            <PaperName>30334 2-1/4 in x 1-1/4 in</PaperName>\
            <DrawCommands>\
                <RoundRectangle X="0" Y="0" Width="3240" Height="1800" Rx="270" Ry="270" />\
            </DrawCommands>\
            <ObjectInfo>\
                <TextObject>\
                    <Name>TESTO</Name>\
                    <ForeColor Alpha="255" Red="0" Green="0" Blue="0" />\
                    <BackColor Alpha="0" Red="255" Green="255" Blue="255" />\
                    <LinkedObjectName></LinkedObjectName>\
                    <Rotation>Rotation0</Rotation>\
                    <IsMirrored>False</IsMirrored>\
                    <IsVariable>False</IsVariable>\
                    <HorizontalAlignment>Center</HorizontalAlignment>\
                    <VerticalAlignment>Top</VerticalAlignment>\
                    <TextFitMode>ShrinkToFit</TextFitMode>\
                    <UseFullFontHeight>True</UseFullFontHeight>\
                    <Verticalized>False</Verticalized>\
                    <StyledText />\
                </TextObject>\
                <Bounds X="198" Y="150" Width="2835" Height="240" />\
            </ObjectInfo>\
            <ObjectInfo>\
                <BarcodeObject>\
                    <Name>CODICE A BARRE</Name>\
                    <ForeColor Alpha="255" Red="0" Green="0" Blue="0" />\
                    <BackColor Alpha="0" Red="255" Green="255" Blue="255" />\
                    <LinkedObjectName></LinkedObjectName>\
                    <Rotation>Rotation0</Rotation>\
                    <IsMirrored>False</IsMirrored>\
                    <IsVariable>True</IsVariable>\
                    <Text>12345</Text>\
                    <Type>Code128Auto</Type>\
                    <Size>Small</Size>\
                    <TextPosition>Bottom</TextPosition>\
                    <TextFont Family="Arial" Size="8" Bold="False" Italic="False" Underline="False" Strikeout="False" />\
                    <CheckSumFont Family="Arial" Size="8" Bold="False" Italic="False" Underline="False" Strikeout="False" />\
                    <TextEmbedding>None</TextEmbedding>\
                    <ECLevel>0</ECLevel>\
                    <HorizontalAlignment>Center</HorizontalAlignment>\
                    <QuietZonesPadding Left="0" Top="0" Right="0" Bottom="0" />\
                </BarcodeObject>\
                <Bounds X="138" Y="480" Width="2880" Height="720" />\
            </ObjectInfo>\
            <ObjectInfo>\
                <TextObject>\
                    <Name>TESTO_1</Name>\
                    <ForeColor Alpha="255" Red="0" Green="0" Blue="0" />\
                    <BackColor Alpha="0" Red="255" Green="255" Blue="255" />\
                    <LinkedObjectName></LinkedObjectName>\
                    <Rotation>Rotation0</Rotation>\
                    <IsMirrored>False</IsMirrored>\
                    <IsVariable>False</IsVariable>\
                    <HorizontalAlignment>Center</HorizontalAlignment>\
                    <VerticalAlignment>Top</VerticalAlignment>\
                    <TextFitMode>ShrinkToFit</TextFitMode>\
                    <UseFullFontHeight>True</UseFullFontHeight>\
                    <Verticalized>False</Verticalized>\
                    <StyledText />\
                </TextObject>\
                <Bounds X="333" Y="1308" Width="2595" Height="330" />\
            </ObjectInfo>\
        </DieCutLabel>';
        label = dymo.label.framework.openLabelXml(testAddressLabelXml);



        // prints the label
        printButton.onclick = function () {
            try {
                if (!label) {
                    alert("Load label before printing");
                    return;
                }

                //alert(printersSelect.value);
                label.print(printersSelect.value);
                //label.print("unknown printer");
            }
            catch (e) {
                alert(e.message || e);
            }
        }

        printersSelect.onchange = populatePrinterDetail;

        // load printers list on startup
        loadPrintersAsync();
    };

    function initTests() {
        if (dymo.label.framework.init) {
            //dymo.label.framework.trace = true;
            dymo.label.framework.init(onload);
        } else {
            onload();
        }
    }

    // register onload event
    if (window.addEventListener)
        window.addEventListener("load", initTests, false);
    else if (window.attachEvent)
        window.attachEvent("onload", initTests);
    else
        window.onload = initTests;

}());