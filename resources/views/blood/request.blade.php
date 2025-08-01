<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Request Blood') }}
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
                    <form method="POST" action="{{ route('request.store') }}" class="space-y-8">
                        @csrf

                        <!-- Recipient Information Section -->
                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Recipient Information</h3>
                            
                            <div class="space-y-6">
                                <!-- Recipient Selection -->
                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <x-input-label for="recipient_id" :value="__('Select Recipient')" class="text-gray-700" />
                                        <a href="{{ route('recipients.create') }}"
                                            class="text-sm text-red-600 hover:text-red-500 font-medium">
                                            <span class="mr-1">+</span>{{ __('Add New Recipient') }}
                                        </a>
                                    </div>
                                    <select id="recipient_id" name="recipient_id"
                                        class="block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm">
                                        <option value="">Select a Recipient</option>
                                        @foreach ($recipients as $recipient)
                                            <option value="{{ $recipient->id }}"
                                                data-blood-type="{{ $recipient->blood_type_needed }}">
                                                {{ $recipient->name }} - {{ $recipient->blood_type_needed }} (Contact:
                                                {{ $recipient->contact }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('recipient_id')" class="mt-2" />
                                </div>

                                <!-- Blood Type -->
                                <div>
                                    <x-input-label for="blood_group" :value="__('Required Blood Type')" class="text-gray-700" />
                                    <select id="blood_group" name="blood_group"
                                        class="block mt-1 w-full bg-white border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm">
                                        <option value="">Select a Blood Type</option>
                                        <option value="A+">A+</option>
                                        <option value="A-">A−</option>
                                        <option value="B+">B+</option>
                                        <option value="B-">B−</option>
                                        <option value="AB+">AB+</option>
                                        <option value="AB-">AB−</option>
                                        <option value="O+">O+</option>
                                        <option value="O-">O−</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('blood_type_needed')" class="mt-2" />
                                </div>
                                
                            </div>
                        </div>

                        <!-- Request Details Section -->
                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Request Details</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Units Required -->
                                <div>
                                    <x-input-label for="units_required" :value="__('Units Required')" class="text-gray-700" />
                                    <x-text-input id="units_required" class="block mt-1 w-full" type="number" name="units_required"
                                        min="1" :value="old('units_required', 1)" required />
                                    <x-input-error :messages="$errors->get('units_required')" class="mt-2" />
                                </div>

                                <!-- Required By Date -->
                                <div>
                                    <x-input-label for="required_by_date" :value="__('Required By Date')" class="text-gray-700" />
                                    <x-text-input id="required_by_date" class="block mt-1 w-full" type="date"
                                        name="required_by_date" :value="old('required_by_date')" required />
                                    <x-input-error :messages="$errors->get('required_by_date')" class="mt-2" />
                                </div>

                                <!-- Urgency Level -->
                                <div class="md:col-span-2">
                                    <x-input-label for="urgency_level" :value="__('Urgency Level')" class="text-gray-700" />
                                    <select id="urgency_level" name="urgency_level"
                                        class="block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm">
                                        <option value="normal" selected>Normal - Regular request</option>
                                        <option value="urgent">Urgent - Needed soon</option>
                                        <option value="emergency">Emergency - Immediate need</option>
                                    </select>

                                    <x-input-error :messages="$errors->get('urgency_level')" class="mt-2" />
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
                                    <p class="mt-2 text-sm text-gray-600">Click on the map to set your location</p>
                                </div>

                                <!-- Coordinates -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="latitude" :value="__('Latitude')" class="text-gray-700" />
                                        <x-text-input id="latitude" class="block mt-1 w-full" type="number" step="any"
                                            name="latitude" :value="old('latitude')" required />
                                        <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="longitude" :value="__('Longitude')" class="text-gray-700" />
                                        <x-text-input id="longitude" class="block mt-1 w-full" type="number" step="any"
                                            name="longitude" :value="old('longitude')" required />
                                        <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Notes Section -->
                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Information</h3>
                            
                            <div>
                                <x-input-label for="notes" :value="__('Additional Notes')" class="text-gray-700" />
                                <textarea id="notes" name="notes" rows="3"
                                    class="block mt-1 w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm"
                                    placeholder="Add any additional information about the blood request...">{{ old('notes') }}</textarea>
                                <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end">
                            <x-primary-button class="bg-red-600 hover:bg-red-700 focus:bg-red-700 active:bg-red-900">
                                {{ __('Submit Request') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script type="module">
        // Initialize and add the map
        let map;
        let marker;

        async function initMap() {
            const position = {
                lat: 27.7103,
                lng: 85.3222
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

            marker = new AdvancedMarkerElement({
                map: map,
                position: position,
                title: "Selected Location",
            });

            map.addListener("click", (e) => {
                const lat = e.latLng.lat();
                const lng = e.latLng.lng();

                marker.position = e.latLng;
                document.getElementById("latitude").value = lat.toFixed(6);
                document.getElementById("longitude").value = lng.toFixed(6);
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
    </script>

    <!-- prettier-ignore -->
    <script>(g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})
    ({key: '{{ config("services.google.places_api_key") }}', v: "weekly"});</script>
</x-app-layout>
