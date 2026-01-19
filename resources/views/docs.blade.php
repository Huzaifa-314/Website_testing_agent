<x-landing-layout>
    <x-slot name="title">Documentation</x-slot>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-6 py-12 flex flex-col md:flex-row gap-12">
        <!-- Sidebar -->
        <aside class="w-full md:w-64 flex-shrink-0">
            <nav class="sticky top-32 space-y-8 glass-effect p-6 rounded-3xl border border-gray-100 shadow-sm">
                <div class="space-y-4">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest px-2">Getting Started</h3>
                    <ul class="space-y-1">
                        <li><a href="#" class="block px-3 py-2 rounded-xl bg-purple-50 text-purple-600 font-bold border border-purple-100">Introduction</a></li>
                        <li><a href="#" class="block px-3 py-2 rounded-xl text-gray-600 hover:bg-gray-50 transition-colors">Quick Start</a></li>
                        <li><a href="#" class="block px-3 py-2 rounded-xl text-gray-600 hover:bg-gray-50 transition-colors">Key Concepts</a></li>
                    </ul>
                </div>
                <div class="space-y-4">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest px-2">Core Features</h3>
                    <ul class="space-y-1">
                        <li><a href="#" class="block px-3 py-2 rounded-xl text-gray-600 hover:bg-gray-50 transition-colors">Test Recording</a></li>
                        <li><a href="#" class="block px-3 py-2 rounded-xl text-gray-600 hover:bg-gray-50 transition-colors">AI Processing</a></li>
                        <li><a href="#" class="block px-3 py-2 rounded-xl text-gray-600 hover:bg-gray-50 transition-colors">Detailed Logs</a></li>
                    </ul>
                </div>
            </nav>
        </aside>

        <!-- Content Area -->
        <article class="flex-1 glass-effect p-10 md:p-16 rounded-[40px] border border-gray-100 shadow-xl min-h-[600px] fade-in">
            <div class="max-w-3xl">
                <h1 class="text-4xl md:text-6xl font-extrabold text-gray-900 mb-8 leading-tight">
                    Build <span class="gradient-text">Reliable Software</span> with AI.
                </h1>
                
                <p class="text-xl text-gray-600 mb-12 leading-relaxed font-light">
                    Klydos is the first AI-native QA platform that replaces complex Selenium/Playwright scripts with simple natural language instructions.
                </p>

                <div class="space-y-16">
                    <section>
                        <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center gap-4">
                            <span class="w-10 h-10 rounded-2xl bg-gradient-to-br from-pink-500 to-purple-500 shadow-lg shadow-purple-200 flex items-center justify-center text-white text-lg">1</span>
                            Introduction
                        </h2>
                        <p class="text-gray-600 text-lg leading-relaxed mb-6 font-light">
                            Traditional testing is brittle. When you change a CSS class, your tests break. Klydos uses Large Language Models to "see" your application like a human does, making your tests self-healing and resilient to UI changes.
                        </p>
                    </section>

                    <section class="bg-gradient-to-br from-purple-50 to-indigo-50 p-8 rounded-3xl border border-purple-100 relative overflow-hidden group">
                        <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-purple-500/10 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                        <h3 class="text-xl font-bold text-purple-900 mb-3 flex items-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Quick Tip
                        </h3>
                        <p class="text-purple-800 leading-relaxed relative z-10">
                            You don't need to specify IDs or Classes. Just say <code class="bg-white/50 px-2 py-0.5 rounded-lg text-purple-600 font-bold">"Click the blue signup button"</code> and our AI will find it instantly.
                        </p>
                    </section>

                    <section>
                        <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center gap-4">
                            <span class="w-10 h-10 rounded-2xl bg-gradient-to-br from-purple-500 to-indigo-500 shadow-lg shadow-indigo-200 flex items-center justify-center text-white text-lg">2</span>
                            How it works
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="p-6 rounded-2xl bg-white border border-gray-100 shadow-sm feature-card">
                                <h4 class="font-bold text-gray-900 mb-2">Natural Intent</h4>
                                <p class="text-gray-500 text-sm">We interpret your English instructions into executable steps.</p>
                            </div>
                            <div class="p-6 rounded-2xl bg-white border border-gray-100 shadow-sm feature-card">
                                <h4 class="font-bold text-gray-900 mb-2">Visual Logic</h4>
                                <p class="text-gray-500 text-sm">The AI analyzes the DOM and visual tree to locate elements.</p>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </article>
    </div>
</x-landing-layout>
