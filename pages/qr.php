<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>QR Dinámico</title>

   
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<div class="qr-page">

    <h2 class="title">Generador de QR</h2>
    <p class="subtitle">El código se actualiza cada 30 segundos</p>

    <div class="qr-container">
        <div id="qrcode"></div>
    </div>

    <div class="timer-box">
        <span>Tiempo restante:</span>
        <strong id="timer">30</strong>s
    </div>

</div>

<!-- Librería QR -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
let qr;
let timeLeft = 30;


function generarQR() {
    const container = document.getElementById("qrcode");
    container.innerHTML = "";

   
    const data = "DorayPaty-" + Date.now();

    qr = new QRCode(container, {
        text: data,
        width: 220,
        height: 220
    });

    timeLeft = 30;
}

// ⏱ contador
setInterval(() => {
    timeLeft--;

    if (timeLeft <= 0) {
        generarQR();
    }

    document.getElementById("timer").innerText = timeLeft;
}, 1000);

// primera carga
generarQR();
</script>

</body>
</html>