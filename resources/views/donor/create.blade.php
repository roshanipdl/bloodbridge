<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create Donor Profile') }}
            </h2>
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Back to Dashboard') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('donor.store') }}" class="space-y-8">
                        @csrf

                        <!-- Personal Information Section -->
                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Name -->
                                <div>
                                    <x-input-label for="name" :value="__('Full Name')" class="text-gray-700" />
                                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" 
                                        :value="old('name')" required autofocus 
                                        placeholder="Enter your full name" />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>

                                <!-- Contact -->
                                <div>
                                    <x-input-label for="contact" :value="__('Contact Number')" class="text-gray-700" />
                                    <x-text-input id="contact" class="block mt-1 w-full" type="text" name="contact" 
                                        :value="old('contact')" required 
                                        placeholder="Enter your contact number" />
                                    <x-input-error :messages="$errors->get('contact')" class="mt-2" />
                                </div>

                                <!-- Blood Type -->
                                <div>
                                    <x-input-label for="blood_type" :value="__('Blood Type')" class="text-gray-700" />
                                    <select id="blood_type" name="blood_type" 
                                        class="block mt-1 w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm">
                                        <option value="">Select your blood type</option>
                                        <option value="A+" {{ old('blood_type') == 'A+' ? 'selected' : '' }}>A+</option>
                                        <option value="A-" {{ old('blood_type') == 'A-' ? 'selected' : '' }}>A-</option>
                                        <option value="B+" {{ old('blood_type') == 'B+' ? 'selected' : '' }}>B+</option>
                                        <option value="B-" {{ old('blood_type') == 'B-' ? 'selected' : '' }}>B-</option>
                                        <option value="AB+" {{ old('blood_type') == 'AB+' ? 'selected' : '' }}>AB+</option>
                                        <option value="AB-" {{ old('blood_type') == 'AB-' ? 'selected' : '' }}>AB-</option>
                                        <option value="O+" {{ old('blood_type') == 'O+' ? 'selected' : '' }}>O+</option>
                                        <option value="O-" {{ old('blood_type') == 'O-' ? 'selected' : '' }}>O-</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('blood_type')" class="mt-2" />
                                </div>

                                <!-- Health Status -->
                                <div>
                                    <x-input-label for="health_status" :value="__('Health Status')" class="text-gray-700" />
                                    <select id="health_status" name="health_status" 
                                        class="block mt-1 w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm">
                                        <option value="good" {{ old('health_status') == 'good' ? 'selected' : '' }}>Good</option>
                                        <option value="pending_review" {{ old('health_status') == 'pending_review' ? 'selected' : '' }}>Pending Review</option>
                                        <option value="not_eligible" {{ old('health_status') == 'not_eligible' ? 'selected' : '' }}>Not Eligible</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('health_status')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Location Section -->
                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Location</h3>
                            
                            <div class="space-y-6">
                                <!-- Location Map -->
                                <div>
                                    <div id="map" class="w-full h-[400px] rounded-lg border border-gray-300 shadow-sm"></div>
                                    <p class="mt-2 text-sm text-gray-600">Click on the map to set your location or use your current location</p>
                                </div>

                                <!-- Address -->
                                <div>
                                    <x-input-label for="address" :value="__('Address')" class="text-gray-700" />
                                    <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" 
                                        :value="old('address')" required 
                                        placeholder="Your address will be auto-filled when you select a location on the map" />
                                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                                </div>

                                <!-- Coordinates -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="latitude" :value="__('Latitude')" class="text-gray-700" />
                                        <x-text-input id="latitude" class="block mt-1 w-full" type="number" step="any" 
                                            name="latitude" :value="old('latitude')" required 
                                            placeholder="Latitude" />
                                        <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="longitude" :value="__('Longitude')" class="text-gray-700" />
                                        <x-text-input id="longitude" class="block mt-1 w-full" type="number" step="any" 
                                            name="longitude" :value="old('longitude')" required 
                                            placeholder="Longitude" />
                                        <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Availability Section -->
                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Donation Availability</h3>
                            
                            <div class="flex items-center space-x-3">
                                <input type="checkbox" id="is_available" name="is_available" value="1" 
                                    {{ old('is_available') ? 'checked' : '' }} 
                                    class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500">
                                <x-input-label for="is_available" :value="__('I am available for blood donation')" class="text-gray-700" />
                            </div>
                            <p class="mt-2 text-sm text-gray-500">Check this box if you are currently available to donate blood</p>
                            <x-input-error :messages="$errors->get('is_available')" class="mt-2" />
                        </div>

                        <!-- Hidden place_id field -->
                        <input type="hidden" id="place_id" name="place_id" value="{{ old('place_id') }}">

                        <div class="flex items-center justify-end">
                            <x-primary-button class="bg-red-600 hover:bg-red-700 focus:bg-red-700 active:bg-red-900">
                                {{ __('Create Donor Profile') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script type="module">
        // Initialize and add the map
        let map;
        let marker;

        async function initMap() {
            try {
                const position = {
                    lat: 27.7103,
                    lng: 85.3222
                };

                // Request needed libraries
                const { Map } = await google.maps.importLibrary("maps");
                const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

                // Create the map
                map = new Map(document.getElementById("map"), {
                    zoom: 15,
                    center: position,
                    mapId: "DEMO_MAP_ID",
                    styles: [
                        {
                            featureType: "poi",
                            elementType: "labels",
                            stylers: [{ visibility: "off" }]
                        }
                    ]
                });

                // Create the marker
                marker = new AdvancedMarkerElement({
                    map: map,
                    position: position,
                    title: "Selected Location",
                });

                // Add click listener to map
                map.addListener("click", (e) => {
                    const lat = e.latLng.lat();
                    const lng = e.latLng.lng();

                    marker.position = e.latLng;
                    document.getElementById("latitude").value = lat.toFixed(6);
                    document.getElementById("longitude").value = lng.toFixed(6);

                    // Update address using reverse geocoding
                    const geocoder = new google.maps.Geocoder();
                    const latlng = { lat: lat, lng: lng };
                    
                    geocoder.geocode({ 'location': latlng }, function(results, status) {
                        if (status === 'OK') {
                            if (results[0]) {
                                document.getElementById('address').value = results[0].formatted_address;
                            }
                        } else {
                            console.log('Geocoder failed due to: ' + status);
                        }
                    });
                });

                // Add input change listeners
                document.getElementById("latitude").addEventListener("change", updateMarkerPosition);
                document.getElementById("longitude").addEventListener("change", updateMarkerPosition);

                // Try to get user's current location
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            const userLocation = {
                                lat: position.coords.latitude,
                                lng: position.coords.longitude
                            };
                            map.setCenter(userLocation);
                            map.setZoom(15);
                            marker.position = userLocation;
                            document.getElementById("latitude").value = userLocation.lat.toFixed(6);
                            document.getElementById("longitude").value = userLocation.lng.toFixed(6);
                        },
                        function(error) {
                            console.log('Error getting location:', error);
                        }
                    );
                }

            } catch (error) {
                console.error('Error initializing map:', error);
            }
        }

        function updateMarkerPosition() {
            const lat = parseFloat(document.getElementById("latitude").value);
            const lng = parseFloat(document.getElementById("longitude").value);

            if (!isNaN(lat) && !isNaN(lng)) {
                const newPosition = { lat, lng };
                marker.position = newPosition;
                map.setCenter(newPosition);
            }
        }

        // Initialize the map
        initMap();
    </script>

    <!-- Load Google Maps API -->
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&callback=initMap">
    </script>
</x-app-layout> 