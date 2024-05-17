<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>DYMO Label Framework JavaScript Library Samples: Preview and Print Label</title>
    <link rel="stylesheet" type="text/css" href="PreviewAndPrintLabel.css" />
    <script src="https://qajavascriptsdktests.azurewebsites.net/JavaScript/dymo.connect.framework.js"
        type="text/javascript" charset="UTF-8"> </script>
    <script src="eti_script.js" type="text/javascript" charset="UTF-8"> </script>
</head>

<body>
    <div class="content">
        <div class="printControls">
            <div id="printersDiv">
                <label for="printersSelect">Stampante:</label>
                <select id="printersSelect"></select>
            </div>
            <div id="printDiv">
                <button id="printButton">Go</button>
            </div>
        </div>
        <div>
            <label>***** <b>Dettagli Etichettatrice:</b> *****</label>
            <div id="printerDetail"></div>
        </div>
    </div>
    </div>

</body>

</html>