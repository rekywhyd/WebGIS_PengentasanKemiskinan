<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Scan Kupon Bantuan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 flex flex-col items-center">
                    
                    <div class="text-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">Scanner Kupon QR Code</h3>
                        <p class="text-gray-500 dark:text-gray-400">Arahkan kamera ke QR Code kupon milik penerima bantuan untuk memverifikasi dan memproses pencairan.</p>
                    </div>

                    <div id="reader-container" class="w-full max-w-lg mx-auto bg-gray-50 dark:bg-gray-900 rounded-2xl overflow-hidden border-2 border-dashed border-gray-300 dark:border-gray-700 p-2">
                        <div id="reader" style="width: 100%;"></div>
                    </div>

                    <div id="scan-result" class="mt-6 w-full max-w-lg hidden">
                        <div class="bg-green-50 border border-green-200 rounded-xl p-4 flex items-center">
                            <div class="bg-green-100 text-green-600 rounded-full p-2 mr-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-green-800">QR Code Ditemukan!</h4>
                                <p class="text-sm text-green-700 mt-1">Mengarahkan ke halaman konfirmasi...</p>
                            </div>
                        </div>
                    </div>

                    <div id="scan-error" class="mt-6 w-full max-w-lg hidden">
                        <div class="bg-red-50 border border-red-200 rounded-xl p-4 flex items-center">
                            <div class="bg-red-100 text-red-600 rounded-full p-2 mr-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-red-800">QR Code Tidak Valid</h4>
                                <p class="text-sm text-red-700 mt-1" id="error-message">Ini bukan kupon bantuan yang valid.</p>
                            </div>
                        </div>
                        <button onclick="resumeScanning()" class="mt-3 w-full py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-medium transition-colors">
                            Coba Scan Lagi
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Include html5-qrcode library -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let html5QrcodeScanner = null;

            function onScanSuccess(decodedText, decodedResult) {
                // Hentikan scanner sementara
                if (html5QrcodeScanner) {
                    html5QrcodeScanner.pause();
                }

                try {
                    // Validasi apakah ini URL dari sistem kita (mengandung /konfirmasi)
                    const url = new URL(decodedText);
                    
                    if (url.pathname.includes('/konfirmasi')) {
                        // Tampilkan sukses
                        document.getElementById('scan-result').classList.remove('hidden');
                        document.getElementById('scan-error').classList.add('hidden');
                        
                        // Redirect ke URL konfirmasi setelah 1 detik
                        setTimeout(() => {
                            window.location.href = decodedText;
                        }, 1000);
                    } else {
                        throw new Error("Bukan QR Code Kupon dari sistem ini.");
                    }
                } catch (e) {
                    // Tampilkan error
                    document.getElementById('scan-result').classList.add('hidden');
                    document.getElementById('scan-error').classList.remove('hidden');
                }
            }

            function onScanFailure(error) {
                // handle scan failure, usually better to ignore and keep scanning
                // console.warn(`Code scan error = ${error}`);
            }

            html5QrcodeScanner = new Html5QrcodeScanner(
                "reader",
                { fps: 10, qrbox: {width: 250, height: 250} },
                /* verbose= */ false
            );
            
            html5QrcodeScanner.render(onScanSuccess, onScanFailure);

            window.resumeScanning = function() {
                document.getElementById('scan-error').classList.add('hidden');
                if (html5QrcodeScanner) {
                    html5QrcodeScanner.resume();
                }
            };
        });
    </script>
</x-app-layout>
