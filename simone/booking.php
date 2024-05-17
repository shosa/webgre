<!DOCTYPE html>
<html lang="it">
<?php
$serviceName = isset($_GET['service']) ? $_GET['service'] : 'Servizio Non Specificato';
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Service</title>
    <?php require ("content/header.php"); ?>

    <link rel="stylesheet" href="content/css/booking.css">
    <!-- Custom CSS -->
    <style>
        .progress-bar-container {
            margin-top: 20px;
        }

        .step {
            display: none;
        }

        .step.active {
            display: block;
        }

        .fa-check-circle {
            color: #28a745;
        }
    </style>
    <style>
        body,
        h1,
        h2,
        label,
        input,
        .btn {
            font-size: 18px;
            /* Dimensione del font più grande per una migliore leggibilità */
        }

        .container {
            max-width: 400px;
            /* Rende il contenuto più gestibile su dispositivi mobili */
            margin: auto;
            /* Centra il contenuto */
        }

        .form-control,
        .btn,
        .form-check-label {
            height: 50px;
            /* Altezza maggiore per facilitare l'interazione touch */
            margin-bottom: 20px;
            /* Più spazio tra gli elementi del form */
        }

        .form-check-input {
            width: 20px;
            height: 20px;
            margin-top: 15px;
            margin-right: 10px;
            vertical-align: middle;
        }

        .progress-bar-container {
            margin-top: 40px;
            /* Aumenta lo spazio sopra la barra di progresso */
            margin-bottom: 40px;
            /* Aumenta lo spazio sotto la barra di progresso */
        }

        .progress {
            height: 20px;
            /* Rende la barra di progresso più alta */
        }

        .btn {
            width: 100%;
            /* I pulsanti occupano l'intera larghezza del container */
            font-size: 20px;
            /* Dimensione del font più grande per i pulsanti */
        }

        #whatsappButton {
            display: flex;
            align-items: center;
            justify-content: center;
            /* Centra l'icona e il testo nel pulsante WhatsApp */
        }

        .fab {
            margin-right: 10px;
            /* Spazio tra l'icona e il testo del pulsante */
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <?php include ("content/components/navbar.php") ?>

    <!-- Booking Form -->
    <div class="container">
        <h1>
            <?php echo htmlspecialchars($serviceName); ?>
        </h1>
        <div class="progress-bar-container">
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0"
                    aria-valuemax="100"></div>
            </div>
        </div>

        <form id="bookingForm">
            <!-- Step 1: Choose between photo or video -->
            <div class="step active" id="step1">
                <h2>Scegli Servizio</h2>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="serviceType" id="photo" value="photo" checked>
                    <label class="form-check-label" for="photo">
                        Foto
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="serviceType" id="video" value="video">
                    <label class="form-check-label" for="video">
                        Video
                    </label>
                </div>
                <button type="button" class="btn btn-primary next-step">Prossimo</button>
            </div>
            <!-- Step 2 -->
            <div class="step" id="step2">
                <h2>Scegli Data</h2>
                <input type="text" id="datepicker" name="date" class="form-control" placeholder="Seleziona una data">
                <button type="button" class="btn btn-primary next-step">Prossimo</button>
            </div>
            <!-- Step 3  -->
            <div class="step" id="step3">
                <h2>Scegli Orario</h2>
                <input type="time" name="time" class="form-control">
                <button type="button" class="btn btn-primary next-step">Prossimo</button>
            </div>
            <!-- Step 4  -->
            <div class="step" id="step4">
                <h2>Scegli Luogo</h2>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="location" id="public" value="public" checked>
                    <label class="form-check-label" for="public">
                        Posto Pubblico
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="location" id="private" value="private">
                    <label class="form-check-label" for="private">
                        Posto Privato
                    </label>
                </div>
                <button type="button" class="btn btn-primary next-step">Prossimo</button>
            </div>
            <!-- Final step: Summary and WhatsApp button -->
            <div class="step" id="finalStep">
                <h2>Riepilogo</h2>
                <p>Qui verranno mostrati i dettagli inseriti...</p>
                <a href="#" class="btn btn-success" id="whatsappButton"><i class="fab fa-whatsapp"></i> Invia a
                    WhatsApp</a>
            </div>
        </form>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(document).ready(function () {
            $("#datepicker").datepicker({
                dateFormat: "dd/mm/yy"
            });

            var formData = {}; // Object to hold form data

            $(".next-step").click(function () {
                var step = $(this).closest(".step");
                step.removeClass("active").hide();
                step.next(".step").addClass("active").show();
                updateProgressBar(step.index() + 2); // +2 because index is 0-based and we have 5 steps

                // Collect data
                var input = step.find("input[type='radio']:checked, input[type='text'], input[type='time']");
                formData[input.attr("name")] = input.val();

                if (step.next(".step").is("#finalStep")) {
                    updateSummary();
                }
            });

            function updateProgressBar(step) {
                var percentage = step * 20; // 5 steps, each step equals 20%
                $(".progress-bar").css("width", percentage + "%").attr("aria-valuenow", percentage);
            }

            function updateSummary() {
                var summary = "Servizio: " + formData.serviceType + "<br>" +
                    "Data: " + formData.date + "<br>" +
                    "Orario: " + formData.time + "<br>" +
                    "Luogo: " + (formData.location === "public" ? "Posto Pubblico" : "Posto Privato");
                $("#finalStep p").html(summary);

                // Update WhatsApp button link
                var message = `Prenotazione ${$("#serviceName").text()}: ${summary.replace(/<br>/g, ", ")}`;
                $("#whatsappButton").attr("href", `https://api.whatsapp.com/send?phone=+393483318964&text=${encodeURIComponent(message)}`);
            }
        });
    </script>
</body>

</html>