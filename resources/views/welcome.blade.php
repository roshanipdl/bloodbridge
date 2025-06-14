<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>BloodBridge - Connect Blood Donors with Recipients</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            .hero-pattern {
                background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="min-h-screen bg-gray-50">
            <!-- Navigation -->
            <nav class="bg-white shadow-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <div class="flex-shrink-0 flex items-center">
                                <span class="text-2xl font-bold text-red-600">BloodBridge</span>
                            </div>
                        </div>
                        <div class="flex items-center">
                            @if (Route::has('login'))
                                <div class="space-x-4">
                                    @auth
                                        <a href="{{ url('/dashboard') }}" class="text-sm text-gray-700 hover:text-red-600">Dashboard</a>
                                    @else
                                        <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-red-600">Log in</a>
                                        @if (Route::has('register'))
                                            <a href="{{ route('register') }}" class="text-sm text-gray-700 hover:text-red-600">Register</a>
                                        @endif
                                    @endauth
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Hero Section -->
            <div class="relative bg-red-600">
                <div class="absolute inset-0" style="background-image: url('https://images.unsplash.com/photo-1615461065921-4b603f7c6c52?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80'); background-size: cover; background-position: center;">
                    <div class="absolute inset-0 bg-red-600 mix-blend-multiply"></div>
                </div>
                <div class="relative max-w-7xl mx-auto py-24 px-4 sm:py-32 sm:px-6 lg:px-8">
                    <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">Save Lives Through Blood Donation</h1>
                    <p class="mt-6 text-xl text-red-100 max-w-3xl">
                        Join our community of life-savers. Your blood donation can make the difference between life and death for someone in need.
                    </p>
                    <div class="mt-10 flex space-x-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-red-600 bg-white hover:bg-red-50">
                                Go to Dashboard
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-red-600 bg-white hover:bg-red-50">
                                Become a Donor
                            </a>
                            <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-red-700 hover:bg-red-800">
                                Sign In
                            </a>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white py-16">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                        <!-- Find Blood -->
                        <div class="bg-red-50 rounded-lg p-6">
                            <div class="text-red-600">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <h3 class="mt-4 text-lg font-medium text-red-900">Need Blood?</h3>
                            <p class="mt-2 text-red-700">Search for available blood donors in your area</p>
                            <a href="{{ route('login') }}" class="mt-4 inline-flex items-center text-red-600 hover:text-red-500">
                                Search Now
                                <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>

                        <!-- Donate Blood -->
                        <div class="bg-red-50 rounded-lg p-6">
                            <div class="text-red-600">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </div>
                            <h3 class="mt-4 text-lg font-medium text-red-900">Donate Blood</h3>
                            <p class="mt-2 text-red-700">Register as a donor and help save lives</p>
                            <a href="{{ route('register') }}" class="mt-4 inline-flex items-center text-red-600 hover:text-red-500">
                                Register Now
                                <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>

                        <!-- Emergency -->
                        <div class="bg-red-50 rounded-lg p-6">
                            <div class="text-red-600">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <h3 class="mt-4 text-lg font-medium text-red-900">Emergency?</h3>
                            <p class="mt-2 text-red-700">Quick access to emergency blood requests</p>
                            <a href="{{ route('login') }}" class="mt-4 inline-flex items-center text-red-600 hover:text-red-500">
                                Emergency Request
                                <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Why Donate -->
            <div class="bg-gray-50 py-16">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center">
                        <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                            Why Donate Blood?
                        </h2>
                        <p class="mt-4 text-lg text-gray-500">
                            Your donation can make a life-saving difference. Here's why it matters:
                        </p>
                    </div>

                    <div class="mt-12">
                        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                            <div class="bg-white rounded-lg p-6 shadow">
                                <div class="text-red-600">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                </div>
                                <h3 class="mt-4 text-lg font-medium text-gray-900">Save Lives</h3>
                                <p class="mt-2 text-gray-500">
                                    One donation can save up to three lives. Your blood could be the difference between life and death for someone in need.
                                </p>
                            </div>

                            <div class="bg-white rounded-lg p-6 shadow">
                                <div class="text-red-600">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <h3 class="mt-4 text-lg font-medium text-gray-900">Quick & Easy</h3>
                                <p class="mt-2 text-gray-500">
                                    The entire process takes about an hour. The actual donation only takes 8-10 minutes. It's a small time investment for a huge impact.
                                </p>
                            </div>

                            <div class="bg-white rounded-lg p-6 shadow">
                                <div class="text-red-600">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <h3 class="mt-4 text-lg font-medium text-gray-900">Regular Need</h3>
                                <p class="mt-2 text-gray-500">
                                    Blood is needed every two seconds. Your regular donations help maintain a stable blood supply for emergencies.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Call to Action -->
            <div class="bg-red-600">
                <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between">
                    <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                        <span class="block">Ready to make a difference?</span>
                        <span class="block text-red-200">Join our community of life-savers today.</span>
                    </h2>
                    <div class="mt-8 flex lg:mt-0 lg:flex-shrink-0">
                        <div class="inline-flex rounded-md shadow">
                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-red-600 bg-white hover:bg-red-50">
                                Become a Donor
                            </a>
                        </div>
                        <div class="ml-3 inline-flex rounded-md shadow">
                            <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-red-700 hover:bg-red-800">
                                Sign In
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="bg-white">
                <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">About BloodBridge</h3>
                            <p class="mt-4 text-base text-gray-500">
                                Connecting blood donors with recipients to save lives. Join our mission to ensure a stable blood supply for those in need.
                            </p>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Quick Links</h3>
                            <ul class="mt-4 space-y-4">
                                <li>
                                    <a href="{{ route('register') }}" class="text-base text-gray-500 hover:text-gray-900">
                                        Become a Donor
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('login') }}" class="text-base text-gray-500 hover:text-gray-900">
                                        Sign In
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Contact</h3>
                            <ul class="mt-4 space-y-4">
                                <li>
                                    <a href="mailto:support@bloodbridge.com" class="text-base text-gray-500 hover:text-gray-900">
                                        support@bloodbridge.com
                                    </a>
                                </li>
                                <li>
                                    <a href="tel:+1234567890" class="text-base text-gray-500 hover:text-gray-900">
                                        (123) 456-7890
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="mt-8 border-t border-gray-200 pt-8">
                        <p class="text-base text-gray-400 text-center">
                            &copy; {{ date('Y') }} BloodBridge. All rights reserved.
                        </p>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
