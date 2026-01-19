<x-dashboard-layout>
    <div class="max-w-3xl mx-auto">
        <div class="mb-10">
            <a href="{{ route('websites.index') }}" class="inline-flex items-center text-sm font-bold text-indigo-600 hover:text-indigo-700 transition-colors mb-4">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Website List
            </a>
            <h1 class="text-4xl font-black text-gray-900 tracking-tight mb-2">Connect New Website</h1>
            <p class="text-gray-500 font-medium text-lg">Add a new website to start automated testing and monitoring.</p>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-indigo-100/50 border border-gray-100 overflow-hidden group">
            <div class="p-10 border-b border-gray-50 bg-gray-50/30">
                <div class="w-16 h-16 bg-indigo-600 rounded-2xl flex items-center justify-center text-white mb-6 shadow-lg shadow-indigo-200 group-hover:rotate-12 transition-transform duration-500">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900">Website Configuration</h3>
            </div>
            
            <div class="p-10">
                <form method="POST" action="{{ route('websites.store') }}" class="space-y-8">
                    @csrf

                    <!-- URL Address -->
                    <div class="space-y-3">
                        <label for="url" class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Website URL</label>
                        <div class="relative group/input">
                            <input 
                                type="url" 
                                name="url" 
                                id="url"
                                value="{{ old('url') }}"
                                required 
                                autofocus 
                                placeholder="https://www.yourdomain.com"
                                class="w-full pl-6 pr-6 py-4 rounded-2xl border-gray-100 bg-gray-50/50 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-semibold text-gray-700 placeholder-gray-400 outline-none"
                            >
                             <div class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within/input:text-indigo-500 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('url')" class="mt-2 ml-1" />
                        <p class="text-[11px] text-gray-400 font-bold uppercase tracking-wider ml-1 mt-3">Ensure the URL includes http:// or https://</p>
                    </div>

                    <div class="pt-6 border-t border-gray-50 flex items-center justify-end gap-6">
                        <a href="{{ route('websites.index') }}" class="text-sm font-black text-gray-400 hover:text-gray-600 transition-colors uppercase tracking-widest">
                            Cancel
                        </a>
                        <button type="submit" class="px-10 py-4 bg-gradient-to-r from-pink-500 to-purple-500 text-white font-black rounded-2xl shadow-xl shadow-purple-200 hover:shadow-purple-300 transition-all hover:-translate-y-1 active:scale-95">
                            Add New Website
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="mt-12 p-8 bg-indigo-50/50 rounded-[2rem] border border-indigo-100/50 flex items-start gap-6">
            <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-indigo-600 shadow-sm shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h4 class="font-black text-indigo-900 uppercase tracking-tight text-sm mb-1">Testing Tip</h4>
                <p class="text-indigo-700/70 text-sm font-medium leading-relaxed">After adding your property, you can define specific test scenarios to monitor page speed, element availability, and user flows automatically.</p>
            </div>
        </div>
    </div>
</x-dashboard-layout>

