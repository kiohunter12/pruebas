<!-- Sidebar para móvil -->
<div x-show="sidebarOpen" class="fixed inset-0 flex z-40 md:hidden" x-cloak>
    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-600 bg-opacity-75">
    </div>

    <div x-show="sidebarOpen"
         x-transition:enter="transition ease-in-out duration-300 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in-out duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="relative flex-1 flex flex-col max-w-xs w-full bg-white shadow-xl">
        
        <div class="absolute top-0 right-0 -mr-12 pt-2">
            <button @click="sidebarOpen = false" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
            <!-- Logo/Título -->
            <div class="flex-shrink-0 flex items-center px-4 mb-8">
                <div class="flex items-center">
                    <div class="h-8 w-8 bg-municipal-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Sistema</h2>
                        <p class="text-xs text-gray-500">Municipal</p>
                    </div>
                </div>
            </div>

            <!-- Navegación -->
            <nav class="px-2 space-y-1">
                @include('partials.sidebar-menu', ['mobile' => true])
            </nav>
        </div>

        <!-- User info en mobile -->
        <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
            <div class="flex items-center">
                <div class="h-8 w-8 bg-gray-300 rounded-full flex items-center justify-center">
                    <svg class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sidebar para desktop -->
<div class="hidden md:flex md:w-64 md:flex-col md:fixed md:inset-y-0">
    <div class="flex-1 flex flex-col min-h-0 bg-white border-r border-gray-200 shadow-sm">
        <div class="flex-1 flex flex-col pt-5 pb-4 overflow-y-auto">
            <!-- Logo/Título -->
            <div class="flex items-center flex-shrink-0 px-4 mb-8">
                <div class="flex items-center">
                    <div class="h-10 w-10 bg-municipal-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Sistema</h2>
                        <p class="text-sm text-gray-500">Municipal</p>
                    </div>
                </div>
            </div>

            <!-- Navegación -->
            <nav class="flex-1 px-2 space-y-1">
                @include('partials.sidebar-menu', ['mobile' => false])
            </nav>
        </div>

        <!-- User info en desktop -->
        <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
            <div class="flex items-center w-full">
                <div class="h-10 w-10 bg-gray-300 rounded-full flex items-center justify-center">
                    <svg class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                </div>
                <div class="ml-3">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-gray-600 transition-colors duration-200" title="Cerrar Sesión">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>