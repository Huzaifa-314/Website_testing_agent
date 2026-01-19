<x-app-layout>
    <div class="max-w-7xl mx-auto px-6 py-12 flex flex-col md:flex-row gap-12">
        @include('layouts.sidebar')

        <!-- Main Content Area -->
        <main class="flex-1 glass-effect p-8 lg:p-12 rounded-[40px] border border-gray-100 shadow-xl min-h-[600px] fade-in transform transition-all duration-300">
            {{ $slot }}
        </main>
    </div>
</x-app-layout>
