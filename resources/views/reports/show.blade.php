<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Test Execution Details') }}
            </h2>
             <a href="{{ route('reports.index', $testRun->testCase->testDefinition->website) }}" class="text-sm text-gray-600 hover:text-gray-900 border-b border-gray-300 hover:border-gray-600 transition-colors">
                &larr; Back to Reports
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-8">
        <!-- Status Header -->
        <div class="bg-white rounded-t-xl p-6 border border-gray-200 border-b-0 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-bold text-gray-900">{{ $testRun->testCase->testDefinition->description }}</h3>
                <p class="text-sm text-gray-500">Run ID: #{{ $testRun->id }} &bull; {{ $testRun->executed_at ? $testRun->executed_at->toDayDateTimeString() : 'Pending' }}</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="flex gap-2">
                    <a href="{{ route('reports.export.json', $testRun) }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        JSON
                    </a>
                    <a href="{{ route('reports.export.csv', $testRun) }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        CSV
                    </a>
                </div>
                <span class="px-4 py-2 rounded-lg text-sm font-bold uppercase tracking-wider {{ $testRun->result == 'pass' ? 'bg-green-100 text-green-700 border border-green-200' : ($testRun->result == 'fail' ? 'bg-red-100 text-red-700 border border-red-200' : 'bg-gray-100 text-gray-700 border border-gray-200') }}">
                    {{ $testRun->result ?? 'PENDING' }}
                </span>
            </div>
        </div>

        <!-- Terminal Output -->
        <div class="bg-[#1e1e1e] rounded-b-xl shadow-2xl overflow-hidden border border-gray-800">
            <div class="bg-[#2d2d2d] px-4 py-2 flex items-center gap-2 border-b border-[#3e3e3e]">
                <div class="flex gap-2">
                    <div class="w-3 h-3 rounded-full bg-[#ff5f56]"></div>
                    <div class="w-3 h-3 rounded-full bg-[#ffbd2e]"></div>
                    <div class="w-3 h-3 rounded-full bg-[#27c93f]"></div>
                </div>
                <div class="ml-4 text-xs text-gray-400 font-mono">klydos-agent execution-log</div>
            </div>
            <div class="p-6 font-mono text-sm leading-relaxed overflow-x-auto">
                @foreach($testRun->logs as $log)
                    <div class="mb-1 flex">
                        <span class="text-gray-500 mr-4 select-none">$</span>
                        @if(Str::contains($log, 'FAILED'))
                            <span class="text-red-400">{{ $log }}</span>
                        @elseif(Str::contains($log, 'OK'))
                            <span>{{ Str::before($log, 'OK') }}<span class="text-green-400 font-bold">OK</span></span>
                        @else
                            <span class="text-gray-300">{{ $log }}</span>
                        @endif
                    </div>
                @endforeach
                
                <div class="mt-4 pt-4 border-t border-gray-700">
                    <span class="text-gray-500 mr-4 select-none">></span>
                    @if($testRun->result)
                        <span class="{{ $testRun->result == 'pass' ? 'text-green-400' : 'text-red-400' }} font-bold">Process exited with result: {{ strtoupper($testRun->result) }}</span>
                    @else
                        <span class="text-gray-400 font-bold">Process is still running...</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
