<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $donor ? __('Edit Donor Profile') : __('Create Donor Profile') }}
            </h2>
            <div class="flex space-x-2">
                @if($donor && $donor->donationHistory->count() > 0)
                    <a href="{{ route('donor.history', $donor) }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-2">
                        {{ __('View Donation History') }}
                    </a>
                @endif
                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Back to Dashboard') }}
                </a>
            </div>
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

                    <form method="POST" action="{{ $donor ? route('donor.update', $donor) : route('donor.store') }}" class="space-y-8">
                        @csrf
                        @if($donor)
                            @method('PUT')
                        @endif

                        <!-- Personal Information Section -->
                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Name -->
                                <div>
                                    <x-input-label for="name" :value="__('Full Name')" class="text-gray-700" />
                                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" 
                                        :value="old('name', $donor?->name)" required autofocus 
                                        placeholder="Enter your full name" />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>

                                <!-- Contact -->
                                <div>
                                    <x-input-label for="contact" :value="__('Contact Number')" class="text-gray-700" />
                                    <x-text-input id="contact" class="block mt-1 w-full" type="text" name="contact" 
                                        :value="old('contact', $donor?->contact)" required 
                                        placeholder="Enter your contact number" />
                                    <x-input-error :messages="$errors->get('contact')" class="mt-2" />
                                </div>

                                <!-- Blood Type -->
                                <div>
                                    <x-input-label for="blood_type" :value="__('Blood Type')" class="text-gray-700" />
                                    <select id="blood_type" name="blood_type" 
                                        class="block mt-1 w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm"
                                        :value="old('blood_type', $donor?->blood_type)">
                                        <option value="">Select your blood type</option>
                                        <option value="A+" {{ old('blood_type', $donor?->blood_type) == 'A+' ? 'selected' : '' }}>A+</option>
                                        <option value="A-" {{ old('blood_type', $donor?->blood_type) == 'A-' ? 'selected' : '' }}>A-</option>
                                        <option value="B+" {{ old('blood_type', $donor?->blood_type) == 'B+' ? 'selected' : '' }}>B+</option>
                                        <option value="B-" {{ old('blood_type', $donor?->blood_type) == 'B-' ? 'selected' : '' }}>B-</option>
                                        <option value="AB+" {{ old('blood_type', $donor?->blood_type) == 'AB+' ? 'selected' : '' }}>AB+</option>
                                        <option value="AB-" {{ old('blood_type', $donor?->blood_type) == 'AB-' ? 'selected' : '' }}>AB-</option>
                                        <option value="O+" {{ old('blood_type', $donor?->blood_type) == 'O+' ? 'selected' : '' }}>O+</option>
                                        <option value="O-" {{ old('blood_type', $donor?->blood_type) == 'O-' ? 'selected' : '' }}>O-</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('blood_type')" class="mt-2" />
                                </div>

                                <!-- Health Status -->
                                <div>
                                    <x-input-label for="health_status" :value="__('Health Status')" class="text-gray-700" />
                                    <select id="health_status" name="health_status" 
                                        class="block mt-1 w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm"
                                        :value="old('health_status', $donor?->health_status)">
                                        <option value="good" {{ old('health_status', $donor?->health_status) == 'good' ? 'selected' : '' }}>Good</option>
                                        <option value="pending_review" {{ old('health_status', $donor?->health_status) == 'pending_review' ? 'selected' : '' }}>Pending Review</option>
                                        <option value="not_eligible" {{ old('health_status', $donor?->health_status) == 'not_eligible' ? 'selected' : '' }}>Not Eligible</option>
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

                                <!-- Coordinates -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="latitude" :value="__('Latitude')" class="text-gray-700" />
                                        <x-text-input id="latitude" class="block mt-1 w-full" type="number" step="any" name="latitude" 
                                        :value="old('latitude', $donor?->latitude)" required 
                                        placeholder="Latitude" />
                                        <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="longitude" :value="__('Longitude')" class="text-gray-700" />
                                        <x-text-input id="longitude" class="block mt-1 w-full" type="number" step="any" name="longitude" 
                                        :value="old('longitude', $donor?->longitude)" required 
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
                                    {{ old('is_available', $donor?->is_available) ? 'checked' : '' }} 
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
                                {{ $donor ? __('Update Donor Profile') : __('Create Donor Profile') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script type="module">
        let map;
        let marker;

        function markerClick(position) {
            const lat = position.lat;
            const lng = position.lng;
            
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
        }

        async function initMap() {
            // Get initial position from form values if editing
            const initialLat = document.getElementById('latitude').value;
            const initialLng = document.getElementById('longitude').value;
            const position = {
                lat: initialLat ? parseFloat(initialLat) : 27.7103,
                lng: initialLng ? parseFloat(initialLng) : 85.3222
            };

            const { Map } = await google.maps.importLibrary("maps");
            const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

            map = new Map(document.getElementById("map"), {
                zoom: 12,
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

            // Create marker with initial position
            marker = new AdvancedMarkerElement({
                position: position,
                map: map,
                title: 'Your Location'
            });

            // Add click listener to marker
            marker.addListener('click', () => {
                markerClick(position);
            });

            // Simulate marker click if editing to auto-fill fields
            if (initialLat && initialLng) {
                markerClick(position);
            }

            map.addListener('click', (e) => {
                const lat = e.latLng.lat();
                const lng = e.latLng.lng();
                
                marker.position = { lat, lng };
                markerClick({ lat, lng });
            });

            document.getElementById("latitude").addEventListener("change", updateMarkerPosition);
            document.getElementById("longitude").addEventListener("change", updateMarkerPosition);
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

        initMap();

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
    <script>(g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})
    ({key: '{{ config("services.google.places_api_key") }}', v: "weekly"});</script>
    @endpush
</x-app-layout> 