<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-center space-x-6 mb-8">
                    <a href="{{ route('donate') }}" class="inline-flex items-center px-6 py-3 bg-red-600 border border-transparent rounded-md font-semibold text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Donate Blood') }}
                    </a>
                    <a href="{{ route('request') }}" class="inline-flex items-center px-6 py-3 bg-gray-500 border border-transparent rounded-md font-semibold text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Request Blood') }}
                    </a>
                </div>
                
                <div id="dashboard-content" class="mt-4">
                    <!-- Content will be loaded here based on button clicks -->
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
