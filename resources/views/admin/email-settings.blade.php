<x-admin-layout>
    <x-slot name="header">
        {{ __('Email Notification Settings') }}
    </x-slot>

    <div class="space-y-6">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h3 class="text-lg font-bold mb-6">Email Notification Preferences</h3>
                
                <form method="POST" action="{{ route('admin.email-settings.update') }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <!-- Test Completion Notifications -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" 
                                       name="notify_on_test_completion" 
                                       value="1"
                                       {{ old('notify_on_test_completion', $emailSettings['notify_on_test_completion']) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div class="ml-3">
                                <label class="text-sm font-medium text-gray-700">
                                    Notify on Test Completion
                                </label>
                                <p class="text-sm text-gray-500">
                                    Send email notifications when tests complete (both success and failure).
                                </p>
                            </div>
                        </div>

                        <!-- Test Failure Notifications -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" 
                                       name="notify_on_test_failure" 
                                       value="1"
                                       {{ old('notify_on_test_failure', $emailSettings['notify_on_test_failure']) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div class="ml-3">
                                <label class="text-sm font-medium text-gray-700">
                                    Notify on Test Failure
                                </label>
                                <p class="text-sm text-gray-500">
                                    Send email notifications immediately when tests fail.
                                </p>
                            </div>
                        </div>

                        <!-- User Registration Notifications -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" 
                                       name="notify_on_user_registration" 
                                       value="1"
                                       {{ old('notify_on_user_registration', $emailSettings['notify_on_user_registration']) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div class="ml-3">
                                <label class="text-sm font-medium text-gray-700">
                                    Notify on User Registration
                                </label>
                                <p class="text-sm text-gray-500">
                                    Send email notifications to admins when new users register.
                                </p>
                            </div>
                        </div>

                        <!-- Website Added Notifications -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" 
                                       name="notify_on_website_added" 
                                       value="1"
                                       {{ old('notify_on_website_added', $emailSettings['notify_on_website_added']) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div class="ml-3">
                                <label class="text-sm font-medium text-gray-700">
                                    Notify on Website Added
                                </label>
                                <p class="text-sm text-gray-500">
                                    Send email notifications when users add new websites.
                                </p>
                            </div>
                        </div>

                        <!-- Daily Summary Notifications -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" 
                                       name="notify_daily_summary" 
                                       value="1"
                                       {{ old('notify_daily_summary', $emailSettings['notify_daily_summary']) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div class="ml-3">
                                <label class="text-sm font-medium text-gray-700">
                                    Daily Summary
                                </label>
                                <p class="text-sm text-gray-500">
                                    Send daily email summaries with test execution statistics.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Success Message -->
                    @if(session('success'))
                        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Submit Button -->
                    <div class="flex justify-end pt-4 border-t border-gray-200">
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Save Email Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>

