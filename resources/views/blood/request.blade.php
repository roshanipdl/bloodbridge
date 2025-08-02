<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $bloodRequest ? __('Edit Blood Request') : __('Request Blood') }}
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
                    <form method="POST" action="{{ $bloodRequest ? route('request.update', $bloodRequest) : route('request.store') }}" class="space-y-8">
                        @csrf
                        @if($bloodRequest)
                            @method('PUT')
                        @endif

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
                                                data-blood-type="{{ $recipient->blood_type_needed }}"
                                                {{ $bloodRequest && $bloodRequest->recipient_id == $recipient->id ? 'selected' : '' }}>
                                                {{ $recipient->name }} - {{ $recipient->blood_type_needed }} (Contact:
                                                {{ $recipient->contact }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('recipient_id')" class="mt-2" />
                                </div>

                                <!-- Blood Type -->
                                <div>
                                    <x-input-label for="blood_group" :value="__('Blood Group')" class="text-gray-700" />
                                    <select id="blood_group" name="blood_group"
                                        class="block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm">
                                        <option value="">Select Blood Group</option>
                                        <option value="A+" {{ $bloodRequest && $bloodRequest->blood_group == 'A+' ? 'selected' : '' }}>A+</option>
                                        <option value="A-" {{ $bloodRequest && $bloodRequest->blood_group == 'A-' ? 'selected' : '' }}>A-</option>
                                        <option value="B+" {{ $bloodRequest && $bloodRequest->blood_group == 'B+' ? 'selected' : '' }}>B+</option>
                                        <option value="B-" {{ $bloodRequest && $bloodRequest->blood_group == 'B-' ? 'selected' : '' }}>B-</option>
                                        <option value="AB+" {{ $bloodRequest && $bloodRequest->blood_group == 'AB+' ? 'selected' : '' }}>AB+</option>
                                        <option value="AB-" {{ $bloodRequest && $bloodRequest->blood_group == 'AB-' ? 'selected' : '' }}>AB-</option>
                                        <option value="O+" {{ $bloodRequest && $bloodRequest->blood_group == 'O+' ? 'selected' : '' }}>O+</option>
                                        <option value="O-" {{ $bloodRequest && $bloodRequest->blood_group == 'O-' ? 'selected' : '' }}>O-</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('blood_group')" class="mt-2" />
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
                                    <input type="number" name="units_required" id="units_required" min="1" max="10"
                                        class="block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm"
                                        value="{{ $bloodRequest ? $bloodRequest->units_required : 1 }}">
                                    <x-input-error :messages="$errors->get('units_required')" class="mt-2" />
                                </div>

                                <!-- Required By Date -->
                                <div>
                                    <x-input-label for="required_by_date" :value="__('Required By Date')" class="text-gray-700" />
                                    <x-text-input id="required_by_date" class="block mt-1 w-full" type="date"
                                        name="required_by_date" :value="$bloodRequest ? ($bloodRequest->required_by_date ? $bloodRequest->required_by_date->format('Y-m-d') : '') : old('required_by_date')" required />
                                    <x-input-error :messages="$errors->get('required_by_date')" class="mt-2" />
                                </div>

                                <!-- Urgency Level -->
                                <div class="flex justify-between items-center mb-2">
                                    <x-input-label for="urgency_level" :value="__('Urgency Level')" class="text-gray-700" />
                                    <span class="text-sm text-gray-500">{{ __('Select how urgent this request is') }}</span>
                                </div>
                                <select id="urgency_level" name="urgency_level"
                                    class="block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm">
                                    <option value="normal" {{ $bloodRequest && $bloodRequest->urgency_level == 'normal' ? 'selected' : '' }}>{{ __('Normal') }}</option>
                                    <option value="urgent" {{ $bloodRequest && $bloodRequest->urgency_level == 'urgent' ? 'selected' : '' }}>{{ __('Urgent') }}</option>
                                    <option value="emergency" {{ $bloodRequest && $bloodRequest->urgency_level == 'emergency' ? 'selected' : '' }}>{{ __('Emergency') }}</option>
                                </select>
                                <x-input-error :messages="$errors->get('urgency_level')" class="mt-2" />
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
                                            name="latitude" :value="$bloodRequest ? $bloodRequest->latitude : old('latitude')" required />
                                        <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="longitude" :value="__('Longitude')" class="text-gray-700" />
                                        <x-text-input id="longitude" class="block mt-1 w-full" type="number" step="any"
                                            name="longitude" :value="$bloodRequest ? $bloodRequest->longitude : old('longitude')" required />
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
                                    placeholder="Add any additional information about the blood request...">{{ $bloodRequest ? $bloodRequest->notes : old('notes') }}</textarea>
                                <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end">
                            <x-primary-button class="bg-red-600 hover:bg-red-700 focus:bg-red-700 active:bg-red-900">
                                {{ $bloodRequest ? __('Update Request') : __('Submit Request') }}
                                {{ __('Submit Request') }}
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
