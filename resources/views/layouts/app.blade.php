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
    <body class="antialiased bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 relative overflow-x-hidden selection:bg-indigo-500 selection:text-white">
        
        <!-- Background Elements -->
        <div class="fixed inset-0 bg-grid-pattern pointer-events-none z-[-2]"></div>
        <div class="fixed inset-0 pointer-events-none z-[-1] overflow-hidden">
            <div class="blob-1"></div>
            <div class="blob-2"></div>
        </div>

        <div class="min-h-screen relative z-0 flex flex-col">
            <div class="sticky top-0 z-50 backdrop-blur-md bg-white/70 dark:bg-slate-900/70 border-b border-slate-200/50 dark:border-slate-800/50 transition-all duration-300">
                @include('layouts.navigation')
            </div>

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-sm border-b border-slate-200/50 dark:border-slate-800/50 shadow-sm relative z-10">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 animate-fade-in-up">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="flex-grow">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
