<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cetak Kupon Bantuan</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary: #f8fafc;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f1f5f9;
            margin: 0;
            padding: 40px 20px;
            color: var(--text-main);
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .controls {
            text-align: center;
            margin-bottom: 40px;
        }

        .btn-print {
            background-color: var(--primary);
            color: white;
            padding: 14px 28px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 16px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);
        }

        .btn-print:hover {
            background-color: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 6px 8px -1px rgba(37, 99, 235, 0.3);
        }

        .print-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 24px;
            max-width: 1000px;
            margin: 0 auto;
        }

        .coupon-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
            display: flex;
            overflow: hidden;
            border: 1px solid var(--border-color);
            position: relative;
            page-break-inside: avoid;
        }

        .coupon-left {
            flex: 1;
            padding: 32px 24px;
            border-right: 2px dashed #cbd5e1;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* Cutout circles */
        .coupon-left::before, .coupon-left::after {
            content: '';
            position: absolute;
            right: -11px;
            width: 20px;
            height: 20px;
            background-color: #f1f5f9;
            border-radius: 50%;
            border: 1px solid var(--border-color);
            z-index: 1;
        }
        .coupon-left::before { 
            top: -11px; 
            border-top-color: transparent; 
            border-left-color: transparent; 
            transform: rotate(45deg); 
        }
        .coupon-left::after { 
            bottom: -11px; 
            border-bottom-color: transparent; 
            border-left-color: transparent; 
            transform: rotate(135deg); 
        }

        .coupon-right {
            width: 160px;
            background-color: var(--secondary);
            padding: 24px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .header-title {
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 1.5px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 8px;
        }

        .beneficiary-name {
            font-size: 22px;
            font-weight: 800;
            margin: 0 0 4px 0;
            color: var(--text-main);
            line-height: 1.2;
        }

        .beneficiary-nik {
            font-size: 14px;
            color: var(--text-muted);
            margin: 0 0 20px 0;
            font-family: monospace;
            background: #f8fafc;
            padding: 4px 8px;
            border-radius: 6px;
            display: inline-block;
        }

        .period-badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 14px;
            background-color: #eff6ff;
            color: #1e3a8a;
            border-radius: 20px;
            font-weight: 600;
            font-size: 13px;
            margin-bottom: 16px;
            border: 1px solid #bfdbfe;
        }

        .qr-wrapper {
            background: white;
            padding: 8px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            border: 1px solid var(--border-color);
        }

        .qr-wrapper img {
            display: block;
            width: 110px;
            height: 110px;
        }

        .qr-label {
            font-size: 10px;
            color: var(--text-muted);
            margin-top: 12px;
            text-align: center;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .footer-note {
            font-size: 11px;
            color: var(--text-muted);
            margin-top: auto;
            line-height: 1.5;
            display: flex;
            align-items: flex-start;
            gap: 6px;
        }

        .footer-note svg {
            width: 14px;
            height: 14px;
            flex-shrink: 0;
            color: var(--primary);
        }

        @media print {
            body { 
                background-color: white; 
                padding: 0; 
            }
            .controls { display: none; }
            .print-container { 
                display: block;
                max-width: 100%;
            }
            .coupon-card { 
                box-shadow: none; 
                border: 2px solid #cbd5e1; 
                margin-bottom: 24px;
                break-inside: avoid;
            }
            .coupon-left::before, .coupon-left::after {
                background-color: white;
            }
        }
    </style>
</head>
<body>

    <div class="controls">
        <button class="btn-print" onclick="window.print()">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            Cetak Kupon Sekarang
        </button>
    </div>

    <div class="print-container">
        @foreach($kupons as $kupon)
            <div class="coupon-card">
                <div class="coupon-left">
                    <div class="header-title">Voucher Pencairan Bantuan</div>
                    
                    <h2 class="beneficiary-name">{{ $kupon['warga']->nama_kepala_keluarga }}</h2>
                    <div class="beneficiary-nik">NIK: {{ substr($kupon['warga']->nik_kepala_keluarga ?? '', 0, 6) }}&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;</div>
                    
                    <div>
                        <span class="period-badge">
                            <svg width="14" height="14" style="margin-right: 6px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Periode: {{ $kupon['periode_text'] }}
                        </span>
                    </div>

                    <div class="footer-note">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Tunjukkan QR Code ini kepada pengurus tempat penyalur untuk proses verifikasi.
                    </div>
                </div>
                
                <div class="coupon-right">
                    <div class="qr-wrapper">
                        <img src="{{ $kupon['qrDataUri'] }}" alt="QR Code Kupon">
                    </div>
                    <div class="qr-label">Scan untuk<br>Verifikasi</div>
                </div>
            </div>
        @endforeach
    </div>

</body>
</html>
