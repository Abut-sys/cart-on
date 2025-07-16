@extends('layouts.index')

@section('title', 'QR/Barcode Scanner')

@section('content')
    <div class="container py-5">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-body text-center">
                <h2 class="fw-bold mb-3"><i class="fas fa-qrcode me-2"></i>QR / Barcode Scanner</h2>
                <p class="text-muted mb-4">Arahkan kamera ke QR Code produk untuk membuka halaman detail atau edit.</p>

                <div class="mx-auto mb-3" style="max-width: 500px;" id="reader"></div>

                <div id="scan-result" class="mt-3"></div>

                <a href="{{ route('products.index') }}" class="btn btn-secondary mt-4">
                    <i class="fas fa-arrow-left"></i> Kembali ke Produk
                </a>
            </div>
        </div>
    </div>

    <!-- QR Scanner Script -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        const resultContainer = document.getElementById('scan-result');

        function onScanSuccess(decodedText, decodedResult) {
            resultContainer.innerHTML = `<div class="alert alert-success">
                <strong>Scanned:</strong> ${decodedText}
            </div>`;

            // Redirect otomatis jika hasil valid
            if (decodedText.startsWith('http')) {
                window.location.href = decodedText;
            } else if (!isNaN(decodedText)) {
                window.location.href = `/products/${decodedText}/edit`;
            }
        }

        const html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", {
                fps: 10,
                qrbox: { width: 250, height: 250 }
            });

        html5QrcodeScanner.render(onScanSuccess);
    </script>
@endsection
