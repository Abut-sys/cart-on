<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code - {{ $product->name }}</title>
    <style>
        @page {
            size: auto;
            margin: 0mm;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #ffffff;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .print-container {
            max-width: 100%;
            width: 300px;
            text-align: center;
            padding: 30px;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            background-color: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        .company-logo {
            max-width: 150px;
            margin-bottom: 15px;
        }
        .product-name {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
            word-break: break-word;
        }
        .product-brand {
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
        }
        .qr-code-container {
            margin: 20px 0;
            padding: 15px;
            background-color: #fff;
            border: 1px solid #f0f0f0;
            border-radius: 8px;
            display: inline-block;
        }
        .qr-code-image {
            width: 200px;
            height: 200px;
        }
        .product-price {
            font-size: 16px;
            font-weight: 600;
            color: #ea1414;
            margin: 15px 0;
        }
        .print-footer {
            margin-top: 20px;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #f0f0f0;
            padding-top: 15px;
        }
        .print-date {
            font-size: 11px;
            color: #aaa;
            margin-top: 10px;
        }
        @media print {
            body {
                padding: 0;
                background: none;
            }
            .print-container {
                border: none;
                box-shadow: none;
                width: 100%;
                padding: 10px;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="print-container">
        <!-- Optional: Add your company logo -->
        <img src="{{ asset('image/Logo_baru.png') }}" alt="Company Logo" class="company-logo">

        <div class="product-info">
            <div class="product-name">{{ $product->name }}</div>
            <div class="product-brand">{{ $product->brand->name }}</div>
            <div class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
        </div>

        <div class="qr-code-container">
            <img src="{{ asset($product->qr_code_path) }}" alt="QR Code" class="qr-code-image">
        </div>

        <div class="print-footer">
            <p>Scan this QR code to view product details</p>
            <div class="print-date">Generated on {{ now()->format('d M Y H:i') }}</div>
        </div>
    </div>

    <script>
        // Auto-print and close after 500ms
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
                setTimeout(function() {
                    window.close();
                }, 500);
            }, 500);
        });
    </script>
</body>
</html>
