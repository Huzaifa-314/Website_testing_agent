<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('User Management') }}
            </h2>
            <a href="{{ route('admin.users.create') }}" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                + Add User
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
        @if(session('success'))
            <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error') || $errors->any())
            <div class="bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded relative" role="alert">
                @if(session('error'))
                    <span class="block sm:inline">{{ session('error') }}</span>
                @endif
                @if($errors->any())
                    <ul class="mt-2 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endif

        <!-- Search and Filter -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <x-text-input 
                            id="search" 
                            class="block w-full" 
                            type="text" 
                            name="search" 
                            :value="request('search')" 
                            placeholder="Search by name or email..." 
                        />
                    </div>
                    <div>
                        <select name="role" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">All Roles</option>
                            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
                        </select>
                    </div>
                    <x-primary-button type="submit">Filter</x-primary-button>
                    @if(request('search') || request('role'))
                        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                            Clear
                        </a>
                    @endif
                    <a href="{{ route('admin.users.export', request()->query()) }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        Export CSV
                    </a>
                </form>
            </div>
        </div>

        <!-- Bulk Actions -->
        <div id="bulkActions" class="bg-white overflow-hidden shadow-sm sm:rounded-lg hidden">
            <div class="p-4 flex items-center justify-between">
                <span id="selectedCount" class="text-sm text-gray-700">0 users selected</span>
                <div class="flex gap-2">
                    <form id="bulkActivateForm" method="POST" action="{{ route('admin.users.bulk-activate') }}" class="inline">
                        @csrf
                        <input type="hidden" name="ids" id="bulkActivateIds">
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm">Activate</button>
                    </form>
                    <form id="bulkDeactivateForm" method="POST" action="{{ route('admin.users.bulk-deactivate') }}" class="inline">
                        @csrf
                        <input type="hidden" name="ids" id="bulkDeactivateIds">
                        <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition text-sm">Deactivate</button>
                    </form>
                    <select id="bulkRoleSelect" class="border-gray-300 rounded-md shadow-sm text-sm">
                        <option value="">Change Role To...</option>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                    <button type="button" id="bulkChangeRoleBtn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">Change Role</button>
                    <form id="bulkDeleteForm" method="POST" action="{{ route('admin.users.bulk-delete') }}" class="inline" onsubmit="return confirm('Are you sure you want to delete the selected users?');">
                        @csrf
                        <input type="hidden" name="ids" id="bulkDeleteIds">
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm">Delete</button>
                    </form>
                    <button type="button" id="clearSelection" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm">Clear Selection</button>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left">
                                <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email Verified</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Login</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" class="user-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" value="{{ $user->id }}" {{ $user->id === Auth::id() ? 'disabled' : '' }}>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ ($user->is_active ?? true) ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ($user->is_active ?? true) ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->email_verified_at ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $user->email_verified_at ? 'Verified' : 'Unverified' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $user->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.users.show', $user) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                        <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                        @if($user->id !== Auth::id())
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                            </form>
                                        @else
                                            <span class="text-gray-400">Delete</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No users found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($users->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.user-checkbox');
            const bulkActions = document.getElementById('bulkActions');
            const selectedCount = document.getElementById('selectedCount');
            const clearSelection = document.getElementById('clearSelection');
            const bulkActivateForm = document.getElementById('bulkActivateForm');
            const bulkDeactivateForm = document.getElementById('bulkDeactivateForm');
            const bulkDeleteForm = document.getElementById('bulkDeleteForm');
            const bulkChangeRoleBtn = document.getElementById('bulkChangeRoleBtn');
            const bulkRoleSelect = document.getElementById('bulkRoleSelect');

            function updateBulkActions() {
                const selected = Array.from(checkboxes).filter(cb => cb.checked);
                const count = selected.length;
                
                if (count > 0) {
                    bulkActions.classList.remove('hidden');
                    selectedCount.textContent = count + ' user(s) selected';
                    
                    const ids = selected.map(cb => cb.value);
                    document.getElementById('bulkActivateIds').value = JSON.stringify(ids);
                    document.getElementById('bulkDeactivateIds').value = JSON.stringify(ids);
                    document.getElementById('bulkDeleteIds').value = JSON.stringify(ids);
                } else {
                    bulkActions.classList.add('hidden');
                }
            }

            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => {
                    if (!cb.disabled) {
                        cb.checked = selectAll.checked;
                    }
                });
                updateBulkActions();
            });

            checkboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    updateBulkActions();
                    selectAll.checked = Array.from(checkboxes).filter(c => !c.disabled).every(c => c.checked);
                });
            });

            clearSelection.addEventListener('click', function() {
                checkboxes.forEach(cb => cb.checked = false);
                selectAll.checked = false;
                updateBulkActions();
            });

            bulkChangeRoleBtn.addEventListener('click', function() {
                const role = bulkRoleSelect.value;
                if (!role) {
                    alert('Please select a role');
                    return;
                }
                
                const selected = Array.from(checkboxes).filter(cb => cb.checked);
                const ids = selected.map(cb => cb.value);
                
                if (ids.length === 0) {
                    alert('Please select at least one user');
                    return;
                }

                if (confirm(`Change role to ${role} for ${ids.length} user(s)?`)) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("admin.users.bulk-change-role") }}';
                    form.innerHTML = `
                        @csrf
                        <input type="hidden" name="ids" value="${JSON.stringify(ids)}">
                        <input type="hidden" name="role" value="${role}">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    </script>
</x-admin-layout>


