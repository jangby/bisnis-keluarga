<div x-data="{ show: false, message: '', type: 'success' }"
     x-on:notify.window="
        show = true; 
        message = $event.detail.message; 
        type = $event.detail.type; 
        setTimeout(() => show = false, 3000)
     "
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform translate-x-8"
     x-transition:enter-end="opacity-100 transform translate-x-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 transform translate-x-0"
     x-transition:leave-end="opacity-0 transform translate-x-8"
     style="display: none;"
     class="fixed top-5 right-5 z-[99] max-w-sm w-full bg-white border shadow-lg rounded-xl pointer-events-auto flex ring-1 ring-black ring-opacity-5 overflow-hidden">
    
    <div class="flex-1 w-0 p-4">
        <div class="flex items-start">
            <div x-show="type === 'success'" class="flex-shrink-0 pt-0.5">
                <svg class="h-10 w-10 text-green-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            
            <div x-show="type === 'error'" class="flex-shrink-0 pt-0.5">
                <svg class="h-10 w-10 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
            </div>

            <div x-show="type === 'warning'" class="flex-shrink-0 pt-0.5">
                <svg class="h-10 w-10 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                   <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
            </div>

            <div class="ml-3 flex-1">
                <p class="text-sm font-medium text-gray-900" x-text="type === 'success' ? 'Berhasil!' : (type === 'error' ? 'Gagal!' : 'Perhatian')"></p>
                <p class="mt-1 text-sm text-gray-500" x-text="message"></p>
            </div>
        </div>
    </div>
    
    <div class="flex border-l border-gray-200">
        <button @click="show = false" class="w-full border border-transparent rounded-none rounded-r-lg p-4 flex items-center justify-center text-sm font-medium text-gray-600 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            Tutup
        </button>
    </div>
</div>