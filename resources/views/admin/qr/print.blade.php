<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print QR - {{ $token->branch->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page {
            size: A5;
            margin: 0;
        }
        body {
            background: white;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            height: 100vh; /* Full viewport height */
            width: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .print-container {
            width: 148mm;
            height: 208mm; /* Slightly less than 210mm to avoid overflow page break */
            margin: auto;
            border: 1px solid #e2e8f0;
            position: relative;
            background: white;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            box-sizing: border-box; /* Important for padding */
        }

        @media print {
            body {
                height: auto;
                background: white;
            }
            .print-container {
                border: none;
                width: 100%;
                height: 100%;
                margin: 0;
                padding: 0;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                page-break-after: avoid;
                page-break-before: avoid;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body class="bg-slate-100 flex items-center justify-center min-h-screen">
    
    <div class="fixed top-4 right-4 no-print z-50">
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow-lg flex items-center gap-2 transition-transform transform hover:scale-105">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Print A5
        </button>
    </div>

    <div class="print-container shadow-2xl rounded-sm">
        <div class="w-full h-full border-4 border-slate-800 rounded-xl flex flex-col items-center justify-between p-8 relative overflow-hidden">
            
            <!-- Header Pattern -->
            <div class="absolute top-0 left-0 w-full h-4 bg-slate-800"></div>
            
            <div class="w-full text-center mt-6">
                <h1 class="text-3xl font-black uppercase tracking-widest text-slate-800 mb-2">{{ config('app.name', 'HRM System') }}</h1>
                <div class="h-1.5 w-24 bg-blue-600 mx-auto rounded-full"></div>
            </div>

            <div class="text-center flex-1 flex flex-col justify-center w-full">
                <h2 class="text-xl font-bold text-slate-500 uppercase tracking-wider mb-2">Attendance Scan</h2>
                <h3 class="text-3xl font-extrabold text-blue-700 mb-6">{{ $token->branch->name }}</h3>
                
                <div class="relative bg-white p-4 rounded-xl border-2 border-slate-100 shadow-sm inline-block mx-auto">
                    <!-- QR Code -->
                    <img src="{{ route('admin.attendance-qr.image', $token) }}" class="w-64 h-64 object-contain mx-auto" alt="Attendance QR Code" />
                    
                    <!-- Corner Accents for QR -->
                    <div class="absolute top-0 left-0 w-8 h-8 border-t-4 border-l-4 border-blue-600 -mt-1 -ml-1 rounded-tl-lg"></div>
                    <div class="absolute top-0 right-0 w-8 h-8 border-t-4 border-r-4 border-blue-600 -mt-1 -mr-1 rounded-tr-lg"></div>
                    <div class="absolute bottom-0 left-0 w-8 h-8 border-b-4 border-l-4 border-blue-600 -mb-1 -ml-1 rounded-bl-lg"></div>
                    <div class="absolute bottom-0 right-0 w-8 h-8 border-b-4 border-r-4 border-blue-600 -mb-1 -mr-1 rounded-br-lg"></div>
                </div>

                <div class="mt-8 space-y-1">
                    <p class="text-slate-600 font-semibold text-lg">Scan to check-in/out</p>
                    <p class="text-sm text-slate-400 uppercase tracking-wide font-medium">{{ $token->token_date->format('l, F d, Y') }}</p>
                </div>
            </div>

            <div class="w-full text-center mb-2">
                <div class="text-[10px] text-slate-300 uppercase tracking-widest font-medium">
                    Official Document • {{ $token->branch->name }}
                </div>
            </div>
            
            <!-- Footer Pattern -->
            <div class="absolute bottom-0 left-0 w-full h-4 bg-slate-800"></div>
        </div>
    </div>

</body>
</html>
