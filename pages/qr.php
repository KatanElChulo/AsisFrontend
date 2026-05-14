<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>QR Asistencia</title>

    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/qr.css">

</head>

<body>

<div class="qr-page">

    <h1>QR de Asistencia</h1>

    <p>Este código se actualiza cada 30 segundos</p>

    <div class="qr-container">
        <div id="qrcode"></div>
    </div>

    <div class="timer-box">
        Se actualiza en:
        <span id="timer">30</span>s
    </div>

</div>

<div id="alerta" class="alerta"></div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>

let tiempo = 30;

function mostrarAlerta(mensaje, tipo = "success") {

    const alerta = document.getElementById("alerta");

    alerta.innerText = mensaje;

    alerta.classList.remove("success", "error");
    alerta.classList.add("mostrar", tipo);

    setTimeout(() => {
        alerta.classList.remove("mostrar");
    }, 3000);
}

async function generarQR() {

    try {

        const res = await fetch(
            "http://localhost/AsisProyecto/AsisBackend/api/generar_qr.php"
        );

        const data = await res.json();

        if (!data.success) {
            mostrarAlerta("Error al generar QR", "error");
            return;
        }

        document.getElementById("qrcode").innerHTML = "";

        new QRCode(document.getElementById("qrcode"), {
            text: data.token,
            width: 250,
            height: 250
        });

        tiempo = 30;

    } catch (error) {

        console.error(error);
        mostrarAlerta("Error al conectar con el servidor", "error");
    }
}

setInterval(() => {

    tiempo--;

    document.getElementById("timer").innerText = tiempo;

    if (tiempo <= 0) {
        generarQR();
    }

}, 1000);

generarQR();

</script>

</body>
</html>