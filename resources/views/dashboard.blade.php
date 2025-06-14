<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold mb-2">Welcome, {{ Auth::user()->name }}! 👋</h2>
                            <p class="text-gray-600">Thank you for being part of our life-saving community.</p>
                        </div>
                        <div class="text-red-600">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Donate Blood Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg transform transition duration-300 hover:scale-105">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="p-3 bg-red-100 rounded-lg mr-4">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900">Donate Blood</h3>
                        </div>
                        <p class="text-gray-600 mb-4">Your donation can save up to three lives. Ready to make a difference?</p>
                        <a href="{{ route('donate') }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Donate Now
                        </a>
                    </div>
                </div>

                <!-- Request Blood Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg transform transition duration-300 hover:scale-105">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="p-3 bg-red-100 rounded-lg mr-4">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900">Request Blood</h3>
                        </div>
                        <p class="text-gray-600 mb-4">Need blood? Create a request and connect with potential donors.</p>
                        <a href="{{ route('request') }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Create Request
                        </a>
                    </div>
                </div>
            </div>

            <!-- Impact Section -->
            <div class="bg-gradient-to-r from-red-600 to-red-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-white">
                    <div class="max-w-3xl mx-auto text-center">
                        <h3 class="text-2xl font-bold mb-4">Every Drop Counts</h3>
                        <p class="text-red-100 mb-6">
                            "The blood you donate gives someone another chance at life. One day that someone may be a close relative, a friend, a loved one—or even you."
                        </p>
                        <div class="flex justify-center space-x-8">
                            <div class="text-center">
                                <div class="text-3xl font-bold">1</div>
                                <div class="text-sm text-red-200">Donation</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold">3</div>
                                <div class="text-sm text-red-200">Lives Saved</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold">∞</div>
                                <div class="text-sm text-red-200">Impact</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-lg mr-4">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900">Quick Process</h4>
                            <p class="text-gray-600">Donation takes only about an hour</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-lg mr-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900">Community</h4>
                            <p class="text-gray-600">Join our network of donors</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 rounded-lg mr-4">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900">24/7 Support</h4>
                            <p class="text-gray-600">Emergency assistance available</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
