<x-landing-layout>
    <x-slot name="title">AI Powered QA</x-slot>

    <!-- Hero Section -->
    <div class="flex flex-col items-center justify-center text-center px-4 md:px-6 py-20 md:py-32 hero-bg">
        <div class="max-w-5xl mx-auto fade-in">
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
            
            <!-- Interactive Input Field -->
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
    </div>

    <!-- Features Section -->
    <section class="py-24 bg-white">
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
    <section class="py-20 bg-gradient-to-br from-purple-50 via-pink-50 to-indigo-50">
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

    <x-slot name="scripts">
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
    </x-slot>
</x-landing-layout>
