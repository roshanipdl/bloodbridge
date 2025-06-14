<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Add New Recipient') }}
            </h2>
            <a href="{{ route('request') }}"
                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Back to Request') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('recipients.store') }}" class="space-y-8">
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
                                        placeholder="Enter recipient's full name" />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>

                                <!-- Contact -->
                                <div>
                                    <x-input-label for="contact" :value="__('Contact Number')" class="text-gray-700" />
                                    <x-text-input id="contact" class="block mt-1 w-full" type="text" name="contact" 
                                        :value="old('contact')" required 
                                        placeholder="Enter contact number" />
                                    <x-input-error :messages="$errors->get('contact')" class="mt-2" />
                                </div>

                                <!-- Address -->
                                <div class="md:col-span-2">
                                    <x-input-label for="address" :value="__('Address')" class="text-gray-700" />
                                    <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" 
                                        :value="old('address')" required 
                                        placeholder="Enter complete address" />
                                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Blood Type Section -->
                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Blood Type Information</h3>
                            
                            <div>
                                <x-input-label for="blood_type_needed" :value="__('Required Blood Type')" class="text-gray-700" />
                                <select id="blood_type_needed" name="blood_type_needed" 
                                    class="block mt-1 w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm" 
                                    required>
                                    <option value="">Select Blood Type</option>
                                    <option value="A+" {{ old('blood_type_needed') == 'A+' ? 'selected' : '' }}>A+</option>
                                    <option value="A-" {{ old('blood_type_needed') == 'A-' ? 'selected' : '' }}>A-</option>
                                    <option value="B+" {{ old('blood_type_needed') == 'B+' ? 'selected' : '' }}>B+</option>
                                    <option value="B-" {{ old('blood_type_needed') == 'B-' ? 'selected' : '' }}>B-</option>
                                    <option value="AB+" {{ old('blood_type_needed') == 'AB+' ? 'selected' : '' }}>AB+</option>
                                    <option value="AB-" {{ old('blood_type_needed') == 'AB-' ? 'selected' : '' }}>AB-</option>
                                    <option value="O+" {{ old('blood_type_needed') == 'O+' ? 'selected' : '' }}>O+</option>
                                    <option value="O-" {{ old('blood_type_needed') == 'O-' ? 'selected' : '' }}>O-</option>
                                </select>
                                <x-input-error :messages="$errors->get('blood_type_needed')" class="mt-2" />
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

                        <!-- Additional Information Section -->
                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Information</h3>
                            
                            <div class="space-y-6">
                                <!-- Medical Notes -->
                                <div>
                                    <x-input-label for="medical_notes" :value="__('Medical Notes')" class="text-gray-700" />
                                    <textarea id="medical_notes" name="medical_notes" rows="3" 
                                        class="block mt-1 w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm"
                                        placeholder="Enter any relevant medical information...">{{ old('medical_notes') }}</textarea>
                                    <x-input-error :messages="$errors->get('medical_notes')" class="mt-2" />
                                </div>

                                <!-- Special Requirements -->
                                <div>
                                    <x-input-label for="special_requirements" :value="__('Special Requirements')" class="text-gray-700" />
                                    <textarea id="special_requirements" name="special_requirements" rows="3" 
                                        class="block mt-1 w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm"
                                        placeholder="Enter special requirements (one per line)...">{{ old('special_requirements') }}</textarea>
                                    <p class="mt-1 text-sm text-gray-500">Enter each requirement on a new line</p>
                                    <x-input-error :messages="$errors->get('special_requirements')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end">
                            <x-primary-button class="bg-red-600 hover:bg-red-700 focus:bg-red-700 active:bg-red-900">
                                {{ __('Create Recipient') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        window.GOOGLE_MAPS_CONFIG = {
            defaultLat: {{ number_format(config('services.google.maps_default_lat'), 6) }},
            defaultLng: {{ number_format(config('services.google.maps_default_lng'), 6) }},
            defaultZoom: {{ config('services.google.maps_default_zoom') }},
            apiKey: "{{ config('services.google.maps_api_key') }}"
        };
    </script>

    <script>
        let map;
        let marker;

        function initMap() {
            const defaultLocation = { 
                lat: GOOGLE_MAPS_CONFIG.defaultLat, 
                lng: GOOGLE_MAPS_CONFIG.defaultLng 
            };
            
            map = new google.maps.Map(document.getElementById('map'), {
                center: defaultLocation,
                zoom: GOOGLE_MAPS_CONFIG.defaultZoom,
                styles: [
                    {
                        featureType: "poi",
                        elementType: "labels",
                        stylers: [{ visibility: "off" }]
                    }
                ]
            });

            const lat = document.getElementById('latitude').value;
            const lng = document.getElementById('longitude').value;
            if (lat && lng) {
                const position = { lat: parseFloat(lat), lng: parseFloat(lng) };
                setMarker(position);
            }

            map.addListener('click', function(e) {
                const position = e.latLng.toJSON();
                setMarker(position);
                updateCoordinateInputs(position);
            });

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const userLocation = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };
                        map.setCenter(userLocation);
                        map.setZoom(15);
                        setMarker(userLocation);
                        updateCoordinateInputs(userLocation);
                    },
                    function(error) {
                        console.log('Error getting location:', error);
                    }
                );
            }
        }

        function setMarker(position) {
            if (marker) {
                marker.setMap(null);
            }
            marker = new google.maps.Marker({
                position: position,
                map: map
            });
        }

        function updateCoordinateInputs(position) {
            document.getElementById('latitude').value = position.lat;
            document.getElementById('longitude').value = position.lng;
        }

        document.querySelector('form').addEventListener('submit', function(e) {
            const specialReqsInput = document.getElementById('special_requirements');
            try {
                const requirements = specialReqsInput.value.split('\n')
                    .filter(line => line.trim())
                    .map(line => line.trim());
                specialReqsInput.value = JSON.stringify(requirements);
            } catch (error) {
                e.preventDefault();
                alert('Please enter valid special requirements (one per line)');
            }
        });
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&callback=initMap" async defer></script>
    @endpush
</x-app-layout>
