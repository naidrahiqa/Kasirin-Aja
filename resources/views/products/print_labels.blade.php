<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Label Barcode</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>

    <style>
        /* Base Styling */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            color: #111827;
            margin: 0;
            padding: 20px;
        }

        .no-print {
            text-align: center;
            margin-bottom: 20px;
            padding: 20px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .btn {
            background-color: #4f46e5;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            font-size: 16px;
            transition: background-color 0.2s;
        }
        
        .btn:hover {
            background-color: #4338ca;
        }

        /* 
           Print Grid Layout 
           Assumes standard A4 Sticker Paper with 3 columns (e.g., 3x10 stickers)
           Commonly: 33 labels per sheet (A4) or similar.
           We are making a fluid grid that works well when printed. 
        */
        .print-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            width: 210mm; /* A4 width */
            margin: 0 auto;
            background: white;
            padding: 10mm;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .label-item {
            border: 1px dashed #ccc;
            padding: 15px 10px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #fff;
            box-sizing: border-box;
            height: 100%;
        }

        .product-name {
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 5px;
            text-transform: uppercase;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2; /* Limit to 2 lines */
            -webkit-box-orient: vertical;
        }

        .product-price {
            font-size: 11px;
            color: #4b5563;
            margin-bottom: 5px;
        }

        .barcode-svg {
            max-width: 100%;
            height: auto;
            max-height: 50px; /* Keep it compact */
        }

        /* Print Media Rules */
        @media print {
            body {
                background: none;
                padding: 0;
                margin: 0;
            }
            .no-print {
                display: none !important;
            }
            .print-container {
                width: 100%;
                margin: 0;
                padding: 0;
                box-shadow: none;
                gap: 5mm; 
                /* Force page breaks neatly if overflowing */
                page-break-after: right;
            }
            .label-item {
                border: 0.1mm solid #000; /* Use solid thin line for cutting guides */
                page-break-inside: avoid;
            }
            @page {
                size: A4 portrait;
                margin: 10mm; /* Printer margins */
            }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <h2 style="margin-top: 0">Pratinjau Cetak Label Barcode</h2>
        <p style="color: #6b7280; font-size: 14px; margin-bottom: 20px;">
            Halaman ini didesain khusus untuk dicetak dengan ukuran kertas A4 (Kertas Stiker / HVS biasa).<br>
            Terdapat {{ count($products) }} produk dengan barcode.
        </p>
        <button class="btn" onclick="window.print()">🖨️ Cetak Sekarang</button>
        <button class="btn" style="background-color: #6b7280; margin-left: 10px;" onclick="window.close()">Tutup Laman</button>
    </div>

    <div class="print-container">
        @forelse($products as $product)
            {{-- We render the label multiple times for demo, but normally 1 label per product. 
                 To easily fill a sheet, a user might want multiple copies. For now, 1 copy per product. --}}
            <div class="label-item">
                <div class="product-name">{{ $product->name }}</div>
                <div class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                
                {{-- JSBarcode target SVG --}}
                <svg class="barcode-svg"
                     data-barcode-value="{{ $product->barcode }}"
                     data-barcode-text="{{ $product->barcode }}"
                ></svg>
            </div>
        @empty
            <div style="grid-column: span 3; text-align: center; padding: 50px; color: #9ca3af;">
                Tidak ada produk dengan barcode.
            </div>
        @endforelse
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Render all barcodes using JsBarcode
            const barcodeElements = document.querySelectorAll('.barcode-svg');
            
            barcodeElements.forEach(function(svg) {
                const value = svg.getAttribute('data-barcode-value');
                
                // JsBarcode will attempt to render CODE128 by default
                // which supports alphanumeric combinations well
                JsBarcode(svg, value, {
                    format: "CODE128", // Solid default
                    lineColor: "#000",
                    width: 1.5,
                    height: 40,
                    displayValue: true,
                    fontSize: 12,
                    fontOptions: "bold",
                    textMargin: 4,
                    margin: 0,
                    background: "transparent",
                    valid: function(valid) {
                        if (!valid) {
                            console.error("Invalid barcode value: " + value);
                            // Fallback rendering or error text inside SVG could go here
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
