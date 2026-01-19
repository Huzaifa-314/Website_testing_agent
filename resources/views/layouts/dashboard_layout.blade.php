<x-app-layout mainClass="py-8">
    <div class="max-w-[1440px] mx-auto h-[calc(100vh-145px)] flex overflow-hidden bg-white rounded-[2.5rem] shadow-2xl shadow-indigo-100/50 border border-gray-100/50">
        @include('layouts.sidebar')

        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto bg-[#fafafa] custom-scrollbar relative">
            <div class="p-8 lg:p-12 max-w-6xl mx-auto">
                {{ $slot }}
            </div>
        </main>
    </div>

    @push('scripts')
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e5e7eb;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #d1d5db;
        }
        
        main {
            scrollbar-gutter: stable;
        }
    </style>
    @endpush
</x-app-layout>
