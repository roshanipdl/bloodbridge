<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Request Blood') }}
            </h2>
            <a href="{{ route('recipients.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Add New Recipient') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('request.store') }}" class="space-y-6">
                    @csrf

                    <!-- Recipient Selection -->
                    <div>
                        <div class="flex justify-between items-center">
                            <x-input-label for="recipient_id" :value="__('Select Recipient')" />
                            <a href="{{ route('recipients.create') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300">
                                <span class="mr-1">+</span>{{ __('Add New Recipient') }}
                            </a>
                        </div>
                        <select id="recipient_id" name="recipient_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            <option value="">Select a Recipient</option>
                            @foreach($recipients as $recipient)
                                <option value="{{ $recipient->id }}" data-blood-type="{{ $recipient->blood_type_needed }}">
                                    {{ $recipient->name }} - {{ $recipient->blood_type_needed }} (Contact: {{ $recipient->contact }})
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('recipient_id')" class="mt-2" />
                    </div>

                    <!-- Blood Type (Auto-filled based on recipient selection) -->
                    <div>
                        <x-input-label for="blood_type_needed" :value="__('Required Blood Type')" />
                        <x-text-input id="blood_type_needed" class="block mt-1 w-full bg-gray-100" type="text" name="blood_type_needed" readonly />
                        <x-input-error :messages="$errors->get('blood_type_needed')" class="mt-2" />
                    </div>

                    <!-- Units Required -->
                    <div>
                        <x-input-label for="units_required" :value="__('Units Required')" />
                        <x-text-input id="units_required" class="block mt-1 w-full" type="number" name="units_required" min="1" :value="old('units_required', 1)" required />
                        <x-input-error :messages="$errors->get('units_required')" class="mt-2" />
                    </div>

                    <!-- Urgency Level -->
                    <div>
                        <x-input-label for="urgency_level" :value="__('Urgency Level')" />
                        <select id="urgency_level" name="urgency_level" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                            <option value="normal">Normal</option>
                            <option value="urgent">Urgent</option>
                            <option value="emergency">Emergency</option>
                        </select>
                        <x-input-error :messages="$errors->get('urgency_level')" class="mt-2" />
                    </div>

                    <!-- Additional Notes -->
                    <div>
                        <x-input-label for="notes" :value="__('Additional Notes')" />
                        <textarea id="notes" name="notes" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('notes') }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button class="ml-4">
                            {{ __('Submit Request') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('recipient_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const bloodType = selectedOption.getAttribute('data-blood-type');
            document.getElementById('blood_type_needed').value = bloodType || '';
        });
    </script>
    @endpush
</x-app-layout>
