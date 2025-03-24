<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Request Blood') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('request.store') }}" class="space-y-6">
                    @csrf

                    <!-- Recipient Name -->
                    <div>
                        <x-input-label for="recipient_name" :value="__('Recipient Name')" />
                        <x-text-input id="recipient_name" class="block mt-1 w-full" type="text" name="recipient_name" :value="old('recipient_name')" required autofocus />
                        <x-input-error :messages="$errors->get('recipient_name')" class="mt-2" />
                    </div>

                    <!-- Blood Group -->
                    <div>
                        <x-input-label for="blood_group" :value="__('Blood Group')" />
                        <select id="blood_group" name="blood_group" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            <option value="">Select Blood Group</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                        </select>
                        <x-input-error :messages="$errors->get('blood_group')" class="mt-2" />
                    </div>

                    <!-- Units Required -->
                    <div>
                        <x-input-label for="units_required" :value="__('Units Required')" />
                        <x-text-input id="units_required" class="block mt-1 w-full" type="number" name="units_required" :value="old('units_required')" min="1" required />
                        <x-input-error :messages="$errors->get('units_required')" class="mt-2" />
                    </div>

                    <!-- Hospital Name -->
                    <div>
                        <x-input-label for="hospital_name" :value="__('Hospital Name')" />
                        <x-text-input id="hospital_name" class="block mt-1 w-full" type="text" name="hospital_name" :value="old('hospital_name')" required />
                        <x-input-error :messages="$errors->get('hospital_name')" class="mt-2" />
                    </div>

                    <!-- Hospital Address -->
                    <div>
                        <x-input-label for="hospital_address" :value="__('Hospital Address')" />
                        <x-text-input id="hospital_address" class="block mt-1 w-full" type="text" name="hospital_address" :value="old('hospital_address')" required />
                        <x-input-error :messages="$errors->get('hospital_address')" class="mt-2" />
                    </div>

                    <!-- Contact Number -->
                    <div>
                        <x-input-label for="contact_number" :value="__('Contact Number')" />
                        <x-text-input id="contact_number" class="block mt-1 w-full" type="text" name="contact_number" :value="old('contact_number')" required />
                        <x-input-error :messages="$errors->get('contact_number')" class="mt-2" />
                    </div>

                    <!-- Request Date -->
                    <div>
                        <x-input-label for="request_date" :value="__('Request Date')" />
                        <x-text-input id="request_date" class="block mt-1 w-full" type="date" name="request_date" :value="old('request_date', date('Y-m-d'))" required />
                        <x-input-error :messages="$errors->get('request_date')" class="mt-2" />
                    </div>

                    <!-- Urgency Level -->
                    <div>
                        <x-input-label for="urgency_level" :value="__('Urgency Level')" />
                        <select id="urgency_level" name="urgency_level" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            <option value="normal">Normal</option>
                            <option value="urgent">Urgent</option>
                            <option value="critical">Critical</option>
                        </select>
                        <x-input-error :messages="$errors->get('urgency_level')" class="mt-2" />
                    </div>

                    <!-- Additional Information -->
                    <div>
                        <x-input-label for="additional_info" :value="__('Additional Information')" />
                        <textarea id="additional_info" name="additional_info" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" rows="4">{{ old('additional_info') }}</textarea>
                        <x-input-error :messages="$errors->get('additional_info')" class="mt-2" />
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
</x-app-layout>
