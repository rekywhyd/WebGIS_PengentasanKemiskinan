<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'WebGIS Pengentasan Kemiskinan') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800|outfit:500,600,700,800" rel="stylesheet" />

        <!-- Scripts -->
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
                animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
                opacity: 0;
                transform: translateY(20px);
            }
            @keyframes fadeInUp {
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        </style>
    </head>
    <body class="antialiased bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 relative overflow-x-hidden selection:bg-indigo-500 selection:text-white min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        
        <!-- Background Elements -->
        <div class="fixed inset-0 bg-grid-pattern pointer-events-none z-[-2]"></div>
        <div class="fixed inset-0 pointer-events-none z-[-1] overflow-hidden">
            <div class="blob-1"></div>
            <div class="blob-2"></div>
        </div>

        <div class="animate-fade-in-up w-full flex flex-col items-center">
            <div class="mb-8">
                <a href="/" class="flex flex-col items-center gap-3 group">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-indigo-500/30 group-hover:scale-105 transition-transform duration-300">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="font-outfit font-bold text-2xl tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-400 dark:to-purple-400">
                        WebGIS Kesra
                    </span>
                </a>
            </div>

            <div class="w-full sm:max-w-md px-8 py-10 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden sm:rounded-2xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
