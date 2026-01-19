<x-landing-layout>
    <x-slot name="title">Pricing</x-slot>

    <!-- Content -->
    <div class="py-20 px-6">
        <div class="max-w-7xl mx-auto fade-in">
            <div class="text-center mb-20">
                <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight mb-6">
                    <span class="text-gray-900">Simple Plans for</span><br/>
                    <span class="gradient-text">Massive Automation.</span>
                </h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto leading-relaxed font-light">
                    Ship confident code with a plan that scales with your team. No hidden fees, just pure efficiency.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">
                <!-- Starter Plan -->
                <div class="feature-card p-8 rounded-3xl glass-effect border border-gray-100 flex flex-col hover:border-purple-200">
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-400 uppercase tracking-widest mb-4">Starter</h3>
                        <div class="flex items-baseline gap-1">
                            <span class="text-5xl font-extrabold text-gray-900">$0</span>
                            <span class="text-gray-500 font-medium">/mo</span>
                        </div>
                    </div>
                    <ul class="space-y-4 mb-10 flex-1">
                        <li class="flex items-center text-gray-600 text-lg">
                            <svg class="h-6 w-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                            </svg>
                            5 Tests per month
                        </li>
                        <li class="flex items-center text-gray-600 text-lg">
                            <svg class="h-6 w-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                            </svg>
                            1 Website
                        </li>
                        <li class="flex items-center text-gray-600 text-lg">
                            <svg class="h-6 w-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Basic Reporting
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="w-full text-center py-4 bg-gray-100/50 hover:bg-gray-100 text-gray-900 font-bold rounded-2xl transition-all text-lg">
                        Start for Free
                    </a>
                </div>

                <!-- Professional Plan -->
                <div class="feature-card p-10 rounded-[2.5rem] bg-white border-2 border-purple-500 shadow-2xl flex flex-col relative transform scale-105 z-20">
                    <div class="absolute -top-5 left-1/2 -translate-x-1/2 gradient-button text-white text-xs font-bold px-5 py-2 rounded-full uppercase tracking-widest shadow-lg">Most Popular</div>
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-purple-600 uppercase tracking-widest mb-4">Professional</h3>
                        <div class="flex items-baseline gap-1">
                            <span class="text-5xl font-extrabold text-gray-900">$49</span>
                            <span class="text-gray-500 font-medium">/mo</span>
                        </div>
                    </div>
                    <ul class="space-y-4 mb-10 flex-1">
                        <li class="flex items-center text-gray-800 text-lg font-semibold">
                            <svg class="h-6 w-6 text-purple-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Unlimited Tests
                        </li>
                        <li class="flex items-center text-gray-800 text-lg font-semibold">
                            <svg class="h-6 w-6 text-purple-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                            </svg>
                            10 Websites
                        </li>
                        <li class="flex items-center text-gray-800 text-lg font-semibold">
                            <svg class="h-6 w-6 text-purple-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Parallel Execution
                        </li>
                        <li class="flex items-center text-gray-800 text-lg font-semibold">
                            <svg class="h-6 w-6 text-purple-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Priority Support
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="w-full text-center py-4 gradient-button text-white font-bold rounded-2xl shadow-xl transition-all text-lg">
                        Go Pro Now
                    </a>
                </div>

                <!-- Enterprise Plan -->
                <div class="feature-card p-8 rounded-3xl glass-effect border border-gray-100 flex flex-col hover:border-indigo-200">
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-400 uppercase tracking-widest mb-4">Enterprise</h3>
                        <div class="text-5xl font-extrabold text-gray-900 mb-2">Custom</div>
                        <p class="text-gray-500 text-sm">For teams with complex needs</p>
                    </div>
                    <ul class="space-y-4 mb-10 flex-1">
                        <li class="flex items-center text-gray-600 text-lg">
                            <svg class="h-6 w-6 text-indigo-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Dedicated Infrastructure
                        </li>
                        <li class="flex items-center text-gray-600 text-lg">
                            <svg class="h-6 w-6 text-indigo-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Custom Integrations
                        </li>
                        <li class="flex items-center text-gray-600 text-lg">
                            <svg class="h-6 w-6 text-indigo-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                            </svg>
                            24/7 Account Manager
                        </li>
                    </ul>
                    <a href="#" class="w-full text-center py-4 border-2 border-gray-900 text-gray-900 font-bold rounded-2xl hover:bg-gray-900 hover:text-white transition-all text-lg">
                        Contact Sales
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-landing-layout>
