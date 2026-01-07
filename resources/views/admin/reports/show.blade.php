<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Report Details') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.reports.export.json', $report) }}" target="_blank" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Export JSON
                </a>
                <a href="{{ route('admin.reports.export.csv', $report) }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    Export CSV
                </a>
                <a href="{{ route('admin.reports.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        @if(session('success'))
            <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Report Information -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Report Information</h3>
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Report ID</dt>
                        <dd class="mt-1 text-sm text-gray-900">#{{ $report->id }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Created At</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $report->created_at->format('M d, Y H:i:s') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">User</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <a href="{{ route('admin.users.show', $report->user) }}" class="text-indigo-600 hover:text-indigo-900">
                                {{ $report->user->name }}
                            </a>
                            <div class="text-xs text-gray-500">{{ $report->user->email }}</div>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Website</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <a href="{{ route('admin.websites.show', $report->website) }}" class="text-indigo-600 hover:text-indigo-900">
                                {{ $report->website->url }}
                            </a>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Summary -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Summary</h3>
                <div class="prose max-w-none">
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $report->summary }}</p>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>


