<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Klydos - AI Powered QA</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .gradient-text {
            background: linear-gradient(to right, #4f46e5, #9333ea);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .hero-bg {
            background-image: radial-gradient( circle at 50% 0%, #ede9fe 0%, #fff 50%);
        }
    </style>
</head>
<body class="antialiased text-gray-800 hero-bg">
    <div class="relative min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="flex items-center justify-between px-8 py-6 w-full max-w-7xl mx-auto">
            <div class="flex items-center gap-2">
                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-2xl font-bold tracking-tight text-gray-900">Klydos</span>
            </div>
            <div class="flex items-center gap-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-600 hover:text-indigo-600">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-indigo-600">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700 transition">Get Started</a>
                        @endif
                    @endauth
                @endif
            </div>
        </nav>

        <!-- Hero Section -->
        <main class="flex-grow flex flex-col items-center justify-center text-center px-4 mt-20 mb-32">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 text-sm font-medium mb-6">
                <span class="flex h-2 w-2 rounded-full bg-indigo-600"></span>
                v1.0 Prototype Live
            </div>
            <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight text-gray-900 mb-6 max-w-4xl">
                The AI QA Engineer <br/>
                <span class="gradient-text">You Can Trust.</span>
            </h1>
            <p class="text-lg text-gray-500 mb-10 max-w-2xl mx-auto">
                Klydos automates your website testing with plain English instructions. 
                Describe your test, and our AI agent executes it instantly—no code required.
            </p>
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('register') }}" class="px-8 py-4 bg-indigo-600 text-white text-lg font-bold rounded-xl hover:bg-indigo-700 shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
                    Start Testing for Free
                </a>
                <a href="#demo" class="px-8 py-4 bg-white text-gray-700 text-lg font-bold rounded-xl border border-gray-200 hover:border-gray-300 hover:bg-gray-50 transition">
                    View Demo
                </a>
            </div>

            <!-- Dashboard Preview -->
            <div class="mt-20 relative w-full max-w-5xl mx-auto">
                <div class="absolute inset-0 bg-indigo-600 blur-3xl opacity-20 transform scale-90 rounded-full"></div>
                <img src="https://placehold.co/1200x800/f3f4f6/a5b4fc?text=Klydos+Dashboard+Preview" alt="Dashboard Preview" class="relative rounded-2xl shadow-2xl border border-gray-200 w-full">
            </div>
        </main>

        <!-- Features Section -->
        <section class="py-24 bg-white">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold text-gray-900">Everything you need to ship confident code</h2>
                    <p class="mt-4 text-gray-500">Simplify your QA process with our intelligent agent.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                    <!-- Feature 1 -->
                    <div class="p-8 rounded-2xl bg-gray-50 hover:bg-indigo-50 transition duration-300">
                        <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Natural Language input</h3>
                        <p class="text-gray-500 leading-relaxed">
                            "Check if the login button works." Klydos translates your words into executable test scripts automatically.
                        </p>
                    </div>
                    <!-- Feature 2 -->
                    <div class="p-8 rounded-2xl bg-gray-50 hover:bg-indigo-50 transition duration-300">
                        <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Instant Simulation</h3>
                        <p class="text-gray-500 leading-relaxed">
                            Run tests in seconds. Our optimized backend simulator checks external and internal routes without browser overhead.
                        </p>
                    </div>
                    <!-- Feature 3 -->
                    <div class="p-8 rounded-2xl bg-gray-50 hover:bg-indigo-50 transition duration-300">
                        <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Detailed Reporting</h3>
                        <p class="text-gray-500 leading-relaxed">
                            Get granular feedback. See exactly which step failed, execution logs, and historical performance trends.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white py-12">
            <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center">
                <div class="mb-6 md:mb-0">
                    <span class="text-2xl font-bold">Klydos</span>
                    <p class="text-gray-400 text-sm mt-2">© 2026 Klydos Inc. All rights reserved.</p>
                </div>
                <div class="flex gap-6">
                    <a href="#" class="text-gray-400 hover:text-white transition">Privacy</a>
                    <a href="#" class="text-gray-400 hover:text-white transition">Terms</a>
                    <a href="#" class="text-gray-400 hover:text-white transition">Contact</a>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
