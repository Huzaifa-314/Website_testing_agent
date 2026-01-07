<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Klydos - AI Powered QA</title>
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
</head>
<body class="antialiased bg-white">
    <div class="relative min-h-screen overflow-hidden">
        <!-- Animated gradient orbs -->
        <div class="gradient-orb bg-pink-500 w-96 h-96 -top-48 -left-48"></div>
        <div class="gradient-orb bg-purple-500 w-96 h-96 top-1/2 -right-48" style="animation-delay: 2s;"></div>
        <div class="gradient-orb bg-indigo-500 w-96 h-96 bottom-0 left-1/3" style="animation-delay: 4s;"></div>
        
        <!-- Navigation -->
        <nav class="relative z-50 w-full glass-effect border-b border-gray-100/50">
            <div class="w-full max-w-7xl mx-auto flex items-center justify-between px-6 md:px-12 py-6">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.svg') }}" alt="Klydos Logo" class="w-10 h-10">
                <span class="text-2xl font-bold tracking-tight bg-gradient-to-r from-pink-500 to-purple-500 bg-clip-text text-transparent">Klydos</span>
            </div>
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

        <!-- Hero Section -->
        <main class="relative z-10 flex-grow flex flex-col items-center justify-center text-center px-4 md:px-6 py-20 md:py-32 hero-bg">
            <div class="max-w-5xl mx-auto fade-in">
                <!-- Status Badge -->
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-gradient-to-r from-pink-50 via-purple-50 to-indigo-50 border border-purple-100 text-purple-700 text-sm font-medium mb-8">
                    <span class="flex h-2 w-2 rounded-full bg-gradient-to-r from-pink-500 to-purple-500 animate-pulse"></span>
                    v1.0 Prototype Live
                </div>
                
                <!-- Main Headline -->
                <h1 class="text-5xl md:text-7xl lg:text-8xl font-extrabold tracking-tight mb-8 leading-tight">
                    <span class="text-gray-900">The AI QA Engineer</span><br/>
                    <span class="gradient-text">You Can Trust.</span>
                </h1>
                
                <!-- Subheadline -->
                <p class="text-xl md:text-2xl text-gray-600 mb-12 max-w-3xl mx-auto leading-relaxed font-light">
                    Automate your website testing with plain English instructions. 
                    <span class="font-medium text-gray-800">Describe your test, and our AI agent executes it instantly—no code required.</span>
                </p>
                
                <!-- Interactive Input Field (bolt.new style) -->
                <div class="mb-10 max-w-2xl mx-auto">
                    <div class="relative group">
                        <div class="absolute inset-0 bg-gradient-to-r from-pink-500 via-purple-500 to-indigo-500 rounded-2xl blur-xl opacity-20 group-hover:opacity-30 transition-opacity"></div>
                        <div class="relative bg-white rounded-2xl border-2 border-gray-200 shadow-xl p-6 hover:border-purple-200 transition-all">
                            <div class="flex items-center gap-4">
                                <div class="flex-1">
                                    <input 
                                        type="text" 
                                        placeholder="Describe your test scenario..." 
                                        class="w-full text-lg text-gray-800 placeholder-gray-400 bg-transparent border-none outline-none focus:outline-none input-glow"
                                        id="testInput"
                                    />
                                </div>
                                <button class="px-6 py-3 gradient-button text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all">
                                    Run Test
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-16">
                    <a href="{{ route('register') }}" class="px-8 py-4 gradient-button text-white text-lg font-bold rounded-xl shadow-lg hover:shadow-xl transition-all w-full sm:w-auto">
                        Start Testing for Free
                    </a>
                    <a href="#demo" class="px-8 py-4 bg-white text-gray-700 text-lg font-bold rounded-xl border-2 border-gray-200 hover:border-purple-300 hover:bg-purple-50 transition-all w-full sm:w-auto">
                        View Demo
                    </a>
                </div>

                <!-- Dashboard Preview -->
                <div class="mt-20 relative w-full max-w-6xl mx-auto floating">
                    <div class="absolute inset-0 bg-gradient-to-r from-pink-500 via-purple-500 to-indigo-500 blur-3xl opacity-20 transform scale-90 rounded-full"></div>
                    <div class="relative rounded-3xl shadow-2xl border border-gray-200 overflow-hidden bg-white">
                        <div class="bg-gradient-to-r from-pink-500 via-purple-500 to-indigo-500 h-2"></div>
                        <div class="p-8 bg-gray-50">
                            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                                <div class="flex items-center gap-4 mb-4">
                                    <div class="w-12 h-12 bg-gradient-to-r from-pink-500 to-purple-500 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-900">Test Execution Dashboard</h3>
                                        <p class="text-sm text-gray-500">Real-time test monitoring and results</p>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-200">
                                        <span class="text-sm font-medium text-green-800">✓ Login test passed</span>
                                        <span class="text-xs text-green-600">2.3s</span>
                                    </div>
                                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-200">
                                        <span class="text-sm font-medium text-green-800">✓ Navigation test passed</span>
                                        <span class="text-xs text-green-600">1.8s</span>
                                    </div>
                                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg border border-blue-200">
                                        <span class="text-sm font-medium text-blue-800">⏳ Form validation test running...</span>
                                        <span class="text-xs text-blue-600">Processing</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Features Section -->
        <section class="relative z-10 py-24 bg-white">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-20 fade-in">
                    <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4">
                        Everything you need to <span class="gradient-text">ship confident code</span>
                    </h2>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">Simplify your QA process with our intelligent agent.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="feature-card p-8 rounded-2xl bg-gradient-to-br from-white to-purple-50/30 border border-gray-100 hover:border-purple-200">
                        <div class="w-14 h-14 bg-gradient-to-r from-pink-500 to-purple-500 rounded-xl flex items-center justify-center mb-6 shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Natural Language Input</h3>
                        <p class="text-gray-600 leading-relaxed text-lg">
                            "Check if the login button works." Klydos translates your words into executable test scripts automatically.
                        </p>
                    </div>
                    
                    <!-- Feature 2 -->
                    <div class="feature-card p-8 rounded-2xl bg-gradient-to-br from-white to-indigo-50/30 border border-gray-100 hover:border-indigo-200">
                        <div class="w-14 h-14 bg-gradient-to-r from-purple-500 to-indigo-500 rounded-xl flex items-center justify-center mb-6 shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Instant Simulation</h3>
                        <p class="text-gray-600 leading-relaxed text-lg">
                            Run tests in seconds. Our optimized backend simulator checks external and internal routes without browser overhead.
                        </p>
                    </div>
                    
                    <!-- Feature 3 -->
                    <div class="feature-card p-8 rounded-2xl bg-gradient-to-br from-white to-pink-50/30 border border-gray-100 hover:border-pink-200">
                        <div class="w-14 h-14 bg-gradient-to-r from-pink-500 to-purple-500 rounded-xl flex items-center justify-center mb-6 shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Detailed Reporting</h3>
                        <p class="text-gray-600 leading-relaxed text-lg">
                            Get granular feedback. See exactly which step failed, execution logs, and historical performance trends.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="relative z-10 py-20 bg-gradient-to-br from-purple-50 via-pink-50 to-indigo-50">
            <div class="max-w-7xl mx-auto px-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center">
                    <div class="fade-in">
                        <div class="text-5xl font-extrabold gradient-text mb-2">10x</div>
                        <div class="text-gray-600 text-lg">Faster Testing</div>
                    </div>
                    <div class="fade-in" style="animation-delay: 0.2s;">
                        <div class="text-5xl font-extrabold gradient-text mb-2">0</div>
                        <div class="text-gray-600 text-lg">Code Required</div>
                    </div>
                    <div class="fade-in" style="animation-delay: 0.4s;">
                        <div class="text-5xl font-extrabold gradient-text mb-2">100%</div>
                        <div class="text-gray-600 text-lg">AI Powered</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="relative z-10 bg-gray-900 text-white py-16">
            <div class="max-w-7xl mx-auto px-6">
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
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">Documentation</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    
    <script>
        // Add smooth scroll behavior
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
        
        // Add input interaction
        const testInput = document.getElementById('testInput');
        if (testInput) {
            testInput.addEventListener('focus', function() {
                this.parentElement.parentElement.classList.add('ring-2', 'ring-purple-300');
            });
            testInput.addEventListener('blur', function() {
                this.parentElement.parentElement.classList.remove('ring-2', 'ring-purple-300');
            });
        }
    </script>
</body>
</html>
