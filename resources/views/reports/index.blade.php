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
                                <a href="{{ route('websites.show', $website) }}" class="ml-1 text-gray-500 hover:text-indigo-600 transition-colors duration-200">
                                    {{ Str::limit($website->url, 30) }}
                                </a>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                                <span class="ml-1 text-gray-700 font-medium">Test Reports</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-600">
                    {{ __('Execution History') }}
                </h2>
            </div>
            <a href="{{ route('websites.show', $website) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white/80 backdrop-blur-xl border border-white/20 shadow-xl sm:rounded-2xl overflow-hidden ring-1 ring-black/5 relative">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-32 h-32 bg-purple-500/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">All Test Runs</h3>
                            <p class="text-sm text-gray-500">Comprehensive history of all test executions for this website.</p>
                        </div>
                   </div>

                    @if($testDefinitions->isEmpty())
                        <div class="rounded-xl bg-gray-50 border border-gray-100 p-12 text-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900">No reports found</h3>
                            <p class="mt-1 text-gray-500">No test definitions or runs have been recorded yet.</p>
                             <div class="mt-6">
                                <a href="{{ route('test-definitions.create', ['website_id' => $website->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Create First Test
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="overflow-hidden rounded-xl border border-gray-100/75 bg-white/50">
                            <div class="divide-y divide-gray-100">
                                @foreach($testDefinitions as $definition)
                                    @foreach($definition->testCases as $case)
                                         @foreach($case->testRuns as $run)
                                            <div class="group py-4 px-6 hover:bg-white transition-all duration-200 block border-l-4 {{ $run->result == 'pass' ? 'border-green-500' : ($run->result == 'error' || $run->result == 'fail' ? 'border-red-500' : 'border-gray-200') }}">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap-4">
                                                        <div class="flex-shrink-0">
                                                            @if($run->result == 'pass')
                                                                <div class="items-center justify-center w-10 h-10 rounded-full bg-green-100/50 text-green-600 group-hover:bg-green-100 transition-colors flex shadow-sm">
                                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                                </div>
                                                            @elseif($run->result == 'fail' || $run->result == 'error')
                                                                 <div class="items-center justify-center w-10 h-10 rounded-full bg-red-100/50 text-red-600 group-hover:bg-red-100 transition-colors flex shadow-sm">
                                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                                </div>
                                                            @else
                                                                <div class="items-center justify-center w-10 h-10 rounded-full bg-gray-100/50 text-gray-600 group-hover:bg-gray-100 transition-colors flex shadow-sm">
                                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <div class="flex items-center gap-2">
                                                                <h4 class="text-sm font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">
                                                                    <a href="{{ route('reports.show', $run) }}">
                                                                        {{ $definition->description }}
                                                                    </a>
                                                                </h4>
                                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide {{ $run->result == 'pass' ? 'bg-green-50 text-green-700 border border-green-100' : ($run->result == 'fail' || $run->result == 'error' ? 'bg-red-50 text-red-700 border border-red-100' : 'bg-gray-50 text-gray-600 border border-gray-100') }}">
                                                                    {{ $run->result ?? 'Running' }}
                                                                </span>
                                                            </div>
                                                            <div class="flex items-center gap-4 mt-1">
                                                                <p class="text-xs text-gray-500 flex items-center gap-1">
                                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                                    {{ $run->executed_at ? $run->executed_at->format('M j, Y, g:i a') : 'Pending' }}
                                                                </p>
                                                                <p class="text-xs text-gray-400 flex items-center gap-1">
                                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path></svg>
                                                                    Run #{{ $run->id }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center gap-3 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                                        <a href="{{ route('reports.show', $run) }}" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all">
                                                            <span class="sr-only">View Details</span>
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                        <div class="mt-4 flex justify-center">
                            <p class="text-xs text-gray-400">Showing most recent executions first</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
