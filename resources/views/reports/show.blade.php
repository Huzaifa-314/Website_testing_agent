<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                 <nav class="flex text-sm text-gray-500 mb-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                             <a href="{{ route('websites.index') }}" class="inline-flex items-center hover:text-indigo-600 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                My Websites
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                                <a href="{{ route('websites.show', $testRun->testCase->testDefinition->website) }}" class="ml-1 text-gray-500 hover:text-indigo-600 transition-colors duration-200">
                                    {{ Str::limit($testRun->testCase->testDefinition->website->url, 20) }}
                                </a>
                            </div>
                        </li>
                         <li>
                            <div class="flex items-center">
                                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                                <a href="{{ route('reports.index', $testRun->testCase->testDefinition->website) }}" class="ml-1 text-gray-500 hover:text-indigo-600 transition-colors duration-200">
                                    Reports
                                </a>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                                <span class="ml-1 text-gray-700 font-medium">Run #{{ $testRun->id }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h2 class="text-4xl font-black text-gray-900 tracking-tight mb-2 bg-clip-text text-transparent bg-gradient-to-r from-gray-900 via-indigo-900 to-indigo-600">
                    {{ __('Execution Details') }}
                </h2>
            </div>
             <div class="flex items-center gap-3">
                <a href="{{ route('reports.export.json', $testRun) }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-2 shadow-sm">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    JSON
                </a>
                <a href="{{ route('reports.export.csv', $testRun) }}" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-2 shadow-sm">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    CSV
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Status Header Card -->
            <div class="bg-white/80 backdrop-blur-xl border border-white/20 shadow-xl sm:rounded-2xl overflow-hidden p-6 ring-1 ring-black/5 relative">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                             <h3 class="text-xl font-bold text-gray-900">{{ $testRun->testCase->testDefinition->description }}</h3>
                             <span class="px-2.5 py-0.5 rounded-full text-xs font-black uppercase tracking-widest {{ $testRun->result == 'pass' ? 'bg-emerald-50/80 text-emerald-600 border border-emerald-200' : ($testRun->result == 'fail' || $testRun->result == 'error' ? 'bg-red-50/80 text-red-600 border border-red-200' : 'bg-gray-100 text-gray-700 border border-gray-200') }}">
                                {{ $testRun->result ?? 'PENDING' }}
                            </span>
                        </div>
                        <div class="flex items-center gap-6 text-sm text-gray-500">
                             <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span>{{ $testRun->executed_at ? $testRun->executed_at->format('F j, Y, g:i a') : 'Pending' }}</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                <span>Run ID: #{{ $testRun->id }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex gap-4">
                        <div class="text-right">
                             <p class="text-xs text-gray-500 uppercase tracking-widest font-semibold mb-1">Duration</p>
                             <p class="text-lg font-mono font-bold text-gray-800">2.4s</p> <!-- Placeholder, needs duration logic if available -->
                        </div>
                         <div class="text-right pl-4 border-l border-gray-200">
                             <p class="text-xs text-gray-500 uppercase tracking-widest font-semibold mb-1">Steps</p>
                             <p class="text-lg font-mono font-bold text-gray-800">{{ count($testRun->testCase->steps ?? []) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Terminal Output -->
            <div class="rounded-2xl overflow-hidden shadow-2xl border border-gray-800/50 bg-[#1e1e1e] ring-1 ring-black/50">
                <div class="bg-[#2d2d2d] px-4 py-3 flex items-center justify-between border-b border-[#3e3e3e]">
                    <div class="flex items-center gap-2">
                        <div class="flex gap-2">
                            <div class="w-3 h-3 rounded-full bg-[#ff5f56] border border-[#ff5f56]/50"></div>
                            <div class="w-3 h-3 rounded-full bg-[#ffbd2e] border border-[#ffbd2e]/50"></div>
                            <div class="w-3 h-3 rounded-full bg-[#27c93f] border border-[#27c93f]/50"></div>
                        </div>
                        <div class="ml-4 flex items-center gap-2 text-xs text-gray-400 font-mono bg-[#1e1e1e] px-3 py-1 rounded-md border border-[#3e3e3e]">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            execution-log.log
                        </div>
                    </div>
                    <div class="text-[10px] text-gray-500 font-mono uppercase tracking-widest">
                        Read-only
                    </div>
                </div>
                
                <div class="p-6 font-mono text-sm leading-relaxed overflow-x-auto bg-[#1e1e1e] min-h-[300px]">
                    @foreach($testRun->logs as $log)
                        <div class="mb-1.5 flex group hover:bg-[#2d2d2d]/30 -mx-6 px-6 py-0.5 transition-colors">
                            <span class="text-gray-600 mr-4 select-none w-6 text-right">$</span>
                            <div class="flex-1">
                                @if(Str::contains($log, 'FAILED') || Str::contains($log, 'Error') || Str::contains($log, 'Exception'))
                                    <span class="text-red-400 font-semibold">{{ $log }}</span>
                                @elseif(Str::contains($log, 'OK') || Str::contains($log, 'Success') || Str::contains($log, 'Passed'))
                                     @php
                                        $parts = explode('OK', $log);
                                     @endphp
                                     <span>{{ $parts[0] }}<span class="text-green-400 font-bold bg-green-400/10 px-1 rounded">OK</span>{{ $parts[1] ?? '' }}</span>
                                @elseif(Str::contains($log, 'WARN') || Str::contains($log, 'Warning'))
                                     <span class="text-yellow-400">{{ $log }}</span>
                                @elseif(Str::contains($log, 'INFO'))
                                     <span class="text-blue-400">{{ $log }}</span>
                                @else
                                    <span class="text-gray-300">{{ $log }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                    
                    <div class="mt-8 pt-4 border-t border-gray-800 animate-pulse">
                        <div class="flex items-center gap-2">
                            <span class="text-green-500 font-bold">➜</span>
                            @if($testRun->result)
                                <span class="{{ $testRun->result == 'pass' ? 'text-green-400' : 'text-red-400' }} font-bold">Process exited with result: {{ strtoupper($testRun->result) }}</span>
                            @else
                                <span class="text-gray-400 font-bold">Process is still running...</span>
                                <span class="inline-block w-2 h-4 bg-gray-400 ml-1 animate-blink"></span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .animate-blink {
            animation: blink 1s step-end infinite;
        }
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0; }
        }
    </style>
</x-app-layout>
