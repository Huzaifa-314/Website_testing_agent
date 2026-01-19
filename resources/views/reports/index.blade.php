<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Test Reports') }} <span class="text-gray-400">/</span> {{ $website->url }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
            <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Execution History</h3>
                    <p class="text-sm text-gray-500">All test runs for this website.</p>
                </div>
                <a href="{{ route('websites.show', $website) }}" class="text-indigo-600 font-semibold hover:text-indigo-900 text-sm">Back to Website</a>
            </div>

            @if($testDefinitions->isEmpty())
                <div class="p-12 text-center text-gray-500">No test definitions found.</div>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach($testDefinitions as $definition)
                        @foreach($definition->testCases as $case)
                             @foreach($case->testRuns as $run)
                                <div class="p-6 hover:bg-gray-50 transition block">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-4">
                                            <div class="flex-shrink-0">
                                                @if($run->result == 'pass')
                                                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    </div>
                                                @else
                                                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <h4 class="text-sm font-bold text-gray-900">{{ $definition->description }}</h4>
                                                <p class="text-xs text-gray-500">Executed {{ $run->executed_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-4">
                                            <span class="px-2 py-1 rounded-md text-xs font-bold uppercase {{ $run->result == 'pass' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $run->result }}
                                            </span>
                                            <a href="{{ route('reports.show', $run) }}" class="text-gray-400 hover:text-indigo-600">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
