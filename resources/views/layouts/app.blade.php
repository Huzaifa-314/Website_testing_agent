<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
            
            * {
                font-family: 'Inter', system-ui, -apple-system, sans-serif;
            }

            .gradient-orb {
                position: absolute;
                border-radius: 50%;
                filter: blur(80px);
                opacity: 0.2;
                animation: pulse 8s ease-in-out infinite;
                z-index: 0;
                pointer-events: none;
            }
            
            @keyframes pulse {
                0%, 100% { transform: scale(1); opacity: 0.2; }
                50% { transform: scale(1.2); opacity: 0.4; }
            }

            .glass-effect {
                background: rgba(255, 255, 255, 0.7);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-white">
        <div class="relative min-h-screen overflow-hidden flex flex-col bg-gray-50/30">
            <!-- Animated gradient orbs -->
            <div class="gradient-orb bg-pink-500 w-96 h-96 -top-48 -left-48"></div>
            <div class="gradient-orb bg-purple-500 w-96 h-96 top-1/2 -right-48" style="animation-delay: 2s;"></div>
            <div class="gradient-orb bg-indigo-500 w-96 h-96 bottom-0 left-1/3" style="animation-delay: 4s;"></div>

            <div class="relative z-10 flex flex-col min-h-screen">
                @include('layouts.navigation')

                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white/60 backdrop-blur-md shadow-sm border-b border-gray-100">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="py-10 flex-grow">
                    {{ $slot }}
                </main>
            </div>
        </div>
        @stack('scripts')
    </body>
</html>
