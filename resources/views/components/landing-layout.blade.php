<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'Klydos') }} - AI Powered QA</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        
        * {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #ec4899 0%, #a855f7 50%, #6366f1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, rgba(236, 72, 153, 0.05) 0%, rgba(168, 85, 247, 0.05) 50%, rgba(99, 102, 241, 0.05) 100%);
        }
        
        .hero-bg {
            background: radial-gradient(ellipse at top, rgba(236, 72, 153, 0.08) 0%, rgba(168, 85, 247, 0.05) 30%, rgba(255, 255, 255, 1) 70%);
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        
        .gradient-button {
            background: linear-gradient(135deg, #ec4899 0%, #a855f7 50%, #6366f1 100%);
            transition: all 0.3s ease;
        }
        
        .gradient-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 40px rgba(168, 85, 247, 0.3);
        }
        
        .feature-card {
            transition: all 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .floating {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .fade-in {
            animation: fadeIn 0.8s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .gradient-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.3;
            animation: pulse 8s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.3; }
            50% { transform: scale(1.2); opacity: 0.5; }
        }
        
        .input-glow:focus {
            box-shadow: 0 0 0 3px rgba(168, 85, 247, 0.2);
        }
    </style>
    {{ $head ?? '' }}
</head>
<body class="antialiased bg-white">
    <div class="relative min-h-screen overflow-hidden flex flex-col">
        <!-- Animated gradient orbs -->
        <div class="gradient-orb bg-pink-500 w-96 h-96 -top-48 -left-48"></div>
        <div class="gradient-orb bg-purple-500 w-96 h-96 top-1/2 -right-48" style="animation-delay: 2s;"></div>
        <div class="gradient-orb bg-indigo-500 w-96 h-96 bottom-0 left-1/3" style="animation-delay: 4s;"></div>
        
        <!-- Navigation -->
        <nav class="relative z-50 w-full glass-effect border-b border-gray-100/50">
            <div class="w-full max-w-7xl mx-auto flex items-center justify-between px-6 md:px-12 py-6">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.svg') }}" alt="Klydos Logo" class="w-10 h-10">
                    <a href="/" class="text-2xl font-bold tracking-tight bg-gradient-to-r from-pink-500 to-purple-500 bg-clip-text text-transparent">Klydos</a>
                </div>

                <!-- Middle Links -->
                <div class="hidden md:flex items-center gap-10">
                    <a href="{{ route('pricing') }}" class="{{ request()->routeIs('pricing') ? 'font-bold text-purple-600' : 'font-semibold text-gray-700 hover:text-purple-600' }} transition-colors text-sm uppercase tracking-wider">Pricing</a>
                    <a href="{{ route('docs') }}" class="{{ request()->routeIs('docs') ? 'font-bold text-purple-600' : 'font-semibold text-gray-700 hover:text-purple-600' }} transition-colors text-sm uppercase tracking-wider">Docs</a>
                </div>

                <!-- Right Side -->
                <div class="flex items-center gap-6">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-700 hover:text-purple-600 transition-colors">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="font-semibold text-gray-700 hover:text-purple-600 transition-colors">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-5 py-2.5 gradient-button text-white rounded-xl font-semibold shadow-lg">
                                    Get Started
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </nav>

        <main class="relative z-10 flex-grow">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="relative z-10 bg-gray-900 text-white py-16">
            <div class="max-w-7xl mx-auto px-6 md:px-12">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <img src="{{ asset('images/logo.svg') }}" alt="Klydos Logo" class="w-10 h-10">
                            <span class="text-2xl font-bold">Klydos</span>
                        </div>
                        <p class="text-gray-400 text-sm">© 2026 Klydos Inc. All rights reserved.</p>
                    </div>
                    <div class="flex flex-wrap gap-8">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">Privacy</a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">Terms</a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">Contact</a>
                        <a href="{{ route('docs') }}" class="text-gray-400 hover:text-white transition-colors">Documentation</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    
    {{ $scripts ?? '' }}
</body>
</html>
