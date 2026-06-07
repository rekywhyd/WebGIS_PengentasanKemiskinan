<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'WebGIS Pengentasan Kemiskinan') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800|outfit:500,600,700,800" rel="stylesheet" />

    <!-- Scripts / Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Outfit', sans-serif;
        }
        .bg-grid-pattern {
            background-image: linear-gradient(to right, rgba(0,0,0,0.05) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(0,0,0,0.05) 1px, transparent 1px);
            background-size: 40px 40px;
        }
        .dark .bg-grid-pattern {
            background-image: linear-gradient(to right, rgba(255,255,255,0.03) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(255,255,255,0.03) 1px, transparent 1px);
        }
        .blob-1 {
            position: absolute;
            top: -10%;
            left: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(99,102,241,0.15) 0%, rgba(transparent) 70%);
            border-radius: 50%;
            filter: blur(40px);
            z-index: -1;
        }
        .blob-2 {
            position: absolute;
            bottom: -10%;
            right: -10%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(168,85,247,0.15) 0%, rgba(transparent) 70%);
            border-radius: 50%;
            filter: blur(40px);
            z-index: -1;
        }
        .dark .blob-1 { background: radial-gradient(circle, rgba(99,102,241,0.2) 0%, rgba(transparent) 70%); }
        .dark .blob-2 { background: radial-gradient(circle, rgba(168,85,247,0.2) 0%, rgba(transparent) 70%); }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
            transform: translateY(20px);
        }
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="antialiased bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 relative overflow-x-hidden selection:bg-indigo-500 selection:text-white">

    <!-- Background Elements -->
    <div class="fixed inset-0 bg-grid-pattern pointer-events-none z-[-2]"></div>
    <div class="fixed inset-0 pointer-events-none z-[-1] overflow-hidden">
        <div class="blob-1"></div>
        <div class="blob-2"></div>
    </div>

    <!-- Navigation -->
    <nav class="fixed w-full z-50 backdrop-blur-md bg-white/70 dark:bg-slate-900/70 border-b border-slate-200/50 dark:border-slate-800/50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-indigo-500/30">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="font-outfit font-bold text-xl tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-400 dark:to-purple-400">
                        WebGIS Kesra
                    </span>
                </div>
                
                @if (Route::has('login'))
                    <div class="flex items-center gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 rounded-full bg-slate-900 text-white dark:bg-white dark:text-slate-900 font-medium hover:scale-105 hover:shadow-lg transition-all duration-300">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-full font-medium text-slate-700 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                Masuk
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-6 py-2.5 rounded-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-medium hover:shadow-lg hover:shadow-indigo-500/30 hover:-translate-y-0.5 transition-all duration-300">
                                    Daftar Sekarang
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <main class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 flex flex-col items-center justify-center min-h-screen text-center px-4">
        
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-100 dark:border-indigo-500/20 text-indigo-600 dark:text-indigo-400 text-sm font-medium mb-8 animate-fade-in-up">
            <span class="flex w-2 h-2 rounded-full bg-indigo-600 dark:bg-indigo-400 animate-pulse"></span>
            Sistem Pemetaan Bantuan Sosial
        </div>

        <h1 class="text-5xl md:text-6xl lg:text-7xl font-extrabold tracking-tight mb-6 max-w-4xl animate-fade-in-up delay-100">
            Penyaluran Bantuan <br class="hidden md:block"/>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-400 dark:to-purple-400">
                Tepat Sasaran & Transparan
            </span>
        </h1>

        <p class="text-lg md:text-xl text-slate-600 dark:text-slate-400 max-w-2xl mb-10 leading-relaxed animate-fade-in-up delay-200">
            Sistem Informasi Geografis terpadu untuk mendata, memetakan, dan menyalurkan bantuan kepada warga yang membutuhkan melalui jaringan penyalur yang tepercaya.
        </p>

        <div class="flex flex-col sm:flex-row items-center gap-4 animate-fade-in-up delay-300">
            @auth
                <a href="{{ url('/dashboard') }}" class="w-full sm:w-auto px-8 py-4 rounded-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold text-lg hover:shadow-xl hover:shadow-indigo-500/30 hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-2">
                    Buka Dashboard
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            @else
                <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-4 rounded-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold text-lg hover:shadow-xl hover:shadow-indigo-500/30 hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-2">
                    Mulai Sekarang
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
                <a href="#features" class="w-full sm:w-auto px-8 py-4 rounded-full bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 font-semibold text-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-all duration-300 backdrop-blur-sm flex items-center justify-center gap-2">
                    Pelajari Fitur
                </a>
            @endauth
        </div>

        <!-- Dashboard Preview (Mockup Image) -->
        <div class="mt-20 w-full max-w-5xl relative animate-fade-in-up delay-300">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-50 dark:from-slate-950 via-transparent to-transparent z-10 bottom-0 h-1/2"></div>
            <div class="relative rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white/50 dark:bg-slate-900/50 backdrop-blur-xl shadow-2xl p-2 sm:p-4 overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>
                <!-- Mockup Browser Header -->
                <div class="flex items-center gap-2 p-3 border-b border-slate-200 dark:border-slate-800 mb-4">
                    <div class="w-3 h-3 rounded-full bg-red-400"></div>
                    <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                    <div class="w-3 h-3 rounded-full bg-green-400"></div>
                </div>
                <!-- Abstract Map UI Mockup -->
                <div class="aspect-video bg-slate-100 dark:bg-slate-800 rounded-lg overflow-hidden relative border border-slate-200 dark:border-slate-700 flex items-center justify-center">
                    <svg class="w-full h-full text-slate-200 dark:text-slate-700 absolute inset-0" preserveAspectRatio="none" viewBox="0 0 100 100"><path d="M0,50 Q25,25 50,50 T100,50 V100 H0 Z" fill="currentColor" opacity="0.5"/><path d="M0,70 Q25,45 50,70 T100,70 V100 H0 Z" fill="currentColor" opacity="0.3"/></svg>
                    
                    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCI+PHBhdGggZD0iTTIwIDBMNDAgMjBMMjAgNDBMMCAyMHoiIGZpbGw9Im5vbmUiIHN0cm9rZT0iIzAwMCIgc3Ryb2tlLW9wYWNpdHk9IjAuMDUiLz48L3N2Zz4=')] dark:bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCI+PHBhdGggZD0iTTIwIDBMNDAgMjBMMjAgNDBMMCAyMHoiIGZpbGw9Im5vbmUiIHN0cm9rZT0iI2ZmZiIgc3Ryb2tlLW9wYWNpdHk9IjAuMDUiLz48L3N2Zz4=')] opacity-50"></div>

                    <!-- Pins -->
                    <div class="absolute top-1/4 left-1/3 w-6 h-6 text-indigo-500 animate-bounce"><svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg></div>
                    <div class="absolute top-1/2 left-1/2 w-8 h-8 text-purple-500 drop-shadow-lg"><svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg></div>
                    <div class="absolute bottom-1/3 right-1/4 w-6 h-6 text-pink-500"><svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg></div>
                </div>
            </div>
        </div>
    </main>

    <!-- Features Section -->
    <section id="features" class="py-24 relative z-10 bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Fitur Unggulan Sistem</h2>
                <p class="text-slate-600 dark:text-slate-400 text-lg">Platform ini dirancang khusus untuk memudahkan koordinasi antara pemerintah, penyalur bantuan, dan masyarakat.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="p-8 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700/50 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div class="w-14 h-14 rounded-xl bg-indigo-100 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mb-6">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Pemetaan Interaktif</h3>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                        Visualisasi lokasi warga penerima bantuan secara akurat di atas peta digital, lengkap dengan radius pelayanan setiap penyalur.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="p-8 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700/50 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div class="w-14 h-14 rounded-xl bg-purple-100 dark:bg-purple-500/20 text-purple-600 dark:text-purple-400 flex items-center justify-center mb-6">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Sistem Kupon Digital</h3>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                        Penyaluran bantuan dilakukan melalui teknologi pemindaian kupon (QR Code) untuk memastikan transparansi dan mencegah duplikasi.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="p-8 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700/50 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div class="w-14 h-14 rounded-xl bg-pink-100 dark:bg-pink-500/20 text-pink-600 dark:text-pink-400 flex items-center justify-center mb-6">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Laporan Partisipatif</h3>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                        Masyarakat dapat melaporkan warga yang berhak menerima bantuan melalui tautan aman spesifik per masing-masing tempat ibadah.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-50 dark:bg-slate-950 py-12 border-t border-slate-200 dark:border-slate-800 relative z-10 text-center text-slate-500 dark:text-slate-400">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-center gap-2 mb-4">
                <div class="w-6 h-6 rounded bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="font-outfit font-bold text-slate-800 dark:text-slate-200">WebGIS Kesra</span>
            </div>
            <p>&copy; {{ date('Y') }} WebGIS Pengentasan Kemiskinan. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
