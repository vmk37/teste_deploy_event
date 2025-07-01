document.addEventListener('DOMContentLoaded', function () {
    var qrcode = new QRCode(document.getElementById("qrcode"), {
        text: '{{ $ticket->qr_code }}',
        width: 150,
        height: 150
    });
});

