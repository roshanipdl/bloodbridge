<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $recipient ? __('Edit Recipient') : __('Add New Recipient') }}
            </h2>
            <a href="{{ route('recipients.my') }}"
                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Back to Recipients') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ $recipient ? route('recipient.update', $recipient) : route('recipients.store') }}" class="space-y-8">
                        @csrf
                        @if($recipient)
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
                                        :value="old('name', $recipient ? $recipient->name : '')" required autofocus 
                                        placeholder="Enter recipient's full name" />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>

                                <!-- Contact -->
                                <div>
                                    <x-input-label for="contact" :value="__('Contact Number')" class="text-gray-700" />
                                    <x-text-input id="contact" class="block mt-1 w-full" type="text" name="contact" 
                                        :value="old('contact', $recipient ? $recipient->contact : '')" required 
                                        placeholder="Enter recipient's contact number" />
                                    <x-input-error :messages="$errors->get('contact')" class="mt-2" />
                                </div>

                                 <!-- Address -->
                                 <div>
                                    <x-input-label for="address" :value="__('Address')" class="text-gray-700" />
                                    <textarea id="address" name="address" 
                                        class="block mt-1 w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm min-h-[100px]"
                                        placeholder="Enter recipient's address">{{ old('address', $recipient ? $recipient->address : '') }}</textarea>
                                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                                </div>  

                                <!-- Blood Type -->
                                <div>
                                    <x-input-label for="blood_group" :value="__('Blood Group')" class="text-gray-700" />
                                    <select id="blood_group" name="blood_group"
                                        class="block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm">
                                        <option value="">Select Blood Group</option>
                                        <option value="A+" {{ $recipient && $recipient->blood_group == 'A+' ? 'selected' : '' }}>A+</option>
                                        <option value="A-" {{ $recipient && $recipient->blood_group == 'A-' ? 'selected' : '' }}>A-</option>
                                        <option value="B+" {{ $recipient && $recipient->blood_group == 'B+' ? 'selected' : '' }}>B+</option>
                                        <option value="B-" {{ $recipient && $recipient->blood_group == 'B-' ? 'selected' : '' }}>B-</option>
                                        <option value="AB+" {{ $recipient && $recipient->blood_group == 'AB+' ? 'selected' : '' }}>AB+</option>
                                        <option value="AB-" {{ $recipient && $recipient->blood_group == 'AB-' ? 'selected' : '' }}>AB-</option>
                                        <option value="O+" {{ $recipient && $recipient->blood_group == 'O+' ? 'selected' : '' }}>O+</option>
                                        <option value="O-" {{ $recipient && $recipient->blood_group == 'O-' ? 'selected' : '' }}>O-</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('blood_group')" class="mt-2" />
                                </div>
                            </div>

                            <!-- Medical Notes -->
                            <div>
                                <x-input-label for="medical_notes" :value="__('Medical Notes')" class="text-gray-700" />
                                <textarea id="medical_notes" name="medical_notes" 
                                    class="block mt-1 w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm min-h-[100px]"
                                    placeholder="Enter any medical notes or special requirements">{{ old('medical_notes', $recipient ? $recipient->medical_notes : '') }}</textarea>
                                <x-input-error :messages="$errors->get('medical_notes')" class="mt-2" />
                            </div>  
                        </div>

                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('recipients.my') }}"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button class="bg-red-600 hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                {{ $recipient ? __('Update Recipient Profile') : __('Create Recipient') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script type="module">

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
