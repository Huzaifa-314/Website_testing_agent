<x-app-layout>
    <div class="h-[calc(100vh-6rem)] max-w-7xl mx-auto px-6 pb-6 pt-6 flex flex-col md:flex-row gap-8 overflow-hidden">
        <!-- Sidebar Wrapper -->
        @include('layouts.sidebar')

        <!-- Main Content Area -->
        <main class="flex-1 h-full overflow-y-auto glass-effect p-8 lg:p-12 rounded-[40px] border border-gray-100 shadow-xl custom-scrollbar pb-24">
            {{ $slot }}
        </main>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(200, 200, 200, 0.5);
            border-radius: 20px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(150, 150, 150, 0.8);
        }
    </style>
</x-app-layout>
