<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Donor Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('donor.store') }}" class="space-y-6">
                    @csrf

                    <!-- Name -->
                    <div>
                        <x-input-label for="name" :value="__('Full Name')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Blood Type -->
                    <div>
                        <x-input-label for="blood_type" :value="__('Blood Type')" />
                        <select id="blood_type" name="blood_type" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
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

                    <!-- Hidden place_id field -->
                    <input type="hidden" id="place_id" name="place_id" value="{{ old('place_id') }}">

                    <!-- Location Map -->
                    <div>
                        <div id="map" class="w-full h-64 rounded-lg border border-gray-300 dark:border-gray-700" style="height: 500px;">
                        </div>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Click on the map to set your location</p>
                        <x-input-error :messages="$errors->get('place_id')" class="mt-2" />
                    </div>

                    <!-- Availability -->
                    <div>
                        <x-input-label for="is_available" :value="__('Available for Donation')" />
                        <input type="checkbox" id="is_available" name="is_available" value="1" {{ old('is_available') ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                        <x-input-error :messages="$errors->get('is_available')" class="mt-2" />
                    </div>

                    <!-- Health Status -->
                    <div>
                        <x-input-label for="health_status" :value="__('Health Status')" />
                        <select id="health_status" name="health_status" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            <option value="good" {{ old('health_status') == 'good' ? 'selected' : '' }}>Good</option>
                            <option value="pending_review" {{ old('health_status') == 'pending_review' ? 'selected' : '' }}>Pending Review</option>
                            <option value="not_eligible" {{ old('health_status') == 'not_eligible' ? 'selected' : '' }}>Not Eligible</option>
                        </select>
                        <x-input-error :messages="$errors->get('health_status')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button>
                            {{ __('Create Donor Profile') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="module">
        // Initialize and add the map
        let map;
        let marker;

        async function initMap() {
            try {
                // The location of Kathmandu
                const position = {
                    lat: 27.7103,
                    lng: 85.3222
                };
                // Request needed libraries.
                //@ts-ignore
                const { Map } = await google.maps.importLibrary("maps");
                const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

                // The map, centered at Kathmandu
                map = new Map(document.getElementById("map"), {
                    zoom: 15,
                    center: position,
                    mapId: "DEMO_MAP_ID",
                });

                // The marker, positioned at Kathmandu
                marker = new AdvancedMarkerElement({
                    map: map,
                    position: position,
                    title: "Selected Location",
                });

                // Add click listener to map
                map.addListener("click", (e) => {
                    const lat = e.latLng.lat();
                    const lng = e.latLng.lng();

                    // Update marker position
                    marker.position = e.latLng;

                    // Update input fields
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

            } catch (error) {
                console.error('Error initializing map:', error);
            }
        }

        function updateMarkerPosition() {
            const lat = parseFloat(document.getElementById("latitude").value);
            const lng = parseFloat(document.getElementById("longitude").value);

            if (!isNaN(lat) && !isNaN(lng)) {
                const newPosition = {
                    lat,
                    lng
                };
                marker.position = newPosition;
                map.setCenter(newPosition);
            }
        }

        initMap();
    </script>

    <!-- prettier-ignore -->
    <script>(g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})
    ({key: "AIzaSyAMrmDSTM1LKOR0s5VR_aGUXfVSwLL6cPg", v: "weekly", libraries: ["maps", "marker"]});</script>
</x-app-layout> 