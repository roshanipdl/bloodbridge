<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add New Recipient') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('recipients.store') }}" class="space-y-6">
                    @csrf

                    <!-- Name -->
                    <div>
                        <x-input-label for="name" :value="__('Full Name')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Contact -->
                    <div>
                        <x-input-label for="contact" :value="__('Contact Number')" />
                        <x-text-input id="contact" class="block mt-1 w-full" type="text" name="contact" :value="old('contact')" required />
                        <x-input-error :messages="$errors->get('contact')" class="mt-2" />
                    </div>

                    <!-- Address -->
                    <div>
                        <x-input-label for="address" :value="__('Address')" />
                        <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address')" required />
                        <x-input-error :messages="$errors->get('address')" class="mt-2" />
                    </div>

                    <!-- Blood Type Needed -->
                    <div>
                        <x-input-label for="blood_type_needed" :value="__('Required Blood Type')" />
                        <select id="blood_type_needed" name="blood_type_needed" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
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

                    <!-- Location -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="latitude" :value="__('Latitude')" />
                            <x-text-input id="latitude" class="block mt-1 w-full" type="number" step="any" name="latitude" :value="old('latitude')" required />
                            <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="longitude" :value="__('Longitude')" />
                            <x-text-input id="longitude" class="block mt-1 w-full" type="number" step="any" name="longitude" :value="old('longitude')" required />
                            <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Location Map -->
                    <div>
                        <div id="map" class="w-full h-64 rounded-lg border border-gray-300 dark:border-gray-700"></div>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Click on the map to set your location</p>
                    </div>

                    <!-- Medical Notes -->
                    <div>
                        <x-input-label for="medical_notes" :value="__('Medical Notes')" />
                        <textarea id="medical_notes" name="medical_notes" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('medical_notes') }}</textarea>
                        <x-input-error :messages="$errors->get('medical_notes')" class="mt-2" />
                    </div>

                    <!-- Special Requirements -->
                    <div>
                        <x-input-label for="special_requirements" :value="__('Special Requirements')" />
                        <textarea id="special_requirements" name="special_requirements" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('special_requirements') }}</textarea>
                        <x-input-error :messages="$errors->get('special_requirements')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button class="ml-4">
                            {{ __('Create Recipient') }}
                        </x-primary-button>
                    </div>
                </form>
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
                zoom: GOOGLE_MAPS_CONFIG.defaultZoom
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
