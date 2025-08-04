<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Donor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class DonorController extends Controller
{


    public function create()
    {
        $donor = null;

        return view('donor.create', compact('donor'));
    }

    public function edit()
    {
        $donor = Auth::user()->donor;
        if (!$donor) {
            return redirect()->route('donor.create');
        }
        return view('donor.create', compact('donor'));
    }

    public function profileUpdate(Request $request, Donor $donor)
    {
        // Check if the logged-in user owns this donor profile
        if ($donor->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'blood_type' => ['required', 'string', Rule::in(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])],
            'contact' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s()]{10,20}$/'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'health_status' => ['required', 'string', Rule::in(['good', 'pending_review', 'not_eligible'])],
        ]);

        $donor->update($validated);

        return redirect()->route('donor.update', ["donor" => $donor])->with('success', 'Donor profile updated successfully.');
    }

    public function show(Donor $donor)
    {
        return view('donor.show', compact('donor'));
    }

    /**
     * Show the donation history of a donor
     */
    public function history(Donor $donor)
    {
        $donationHistory = $donor->donationHistory()->latest('donation_date')->paginate(10);
        return view('donor.history', compact('donor', 'donationHistory'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'blood_type' => ['required', 'string', Rule::in(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])],
                'contact' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s()]{10,20}$/'],
                'place_id' => ['nullable', 'string'],
                'latitude' => ['required', 'numeric', 'between:-90,90'],
                'longitude' => ['required', 'numeric', 'between:-180,180'],
                'is_available' => ['boolean'],
                'health_status' => ['required', 'string', Rule::in(['good', 'pending_review', 'not_eligible'])],
            ], [
                'name.required' => 'Please enter your full name.',
                'name.max' => 'Your name cannot exceed 255 characters.',
                'blood_type.required' => 'Please select your blood type.',
                'blood_type.in' => 'Please select a valid blood type.',
                'contact.required' => 'Please enter your contact number.',
                'contact.regex' => 'Please enter a valid contact number (10-20 digits, may include +, -, spaces, and parentheses).',
                'contact.max' => 'Contact number cannot exceed 20 characters.',
                'address.required' => 'Please enter your address.',
                'address.max' => 'Address cannot exceed 255 characters.',
                'place_id.string' => 'Invalid location data. Please try selecting the location again.',
                'latitude.required' => 'Please select a location on the map.',
                'latitude.between' => 'Invalid latitude value. Please select a location on the map.',
                'longitude.required' => 'Please select a location on the map.',
                'longitude.between' => 'Invalid longitude value. Please select a location on the map.',
                'health_status.required' => 'Please select your health status.',
                'health_status.in' => 'Please select a valid health status.',
            ]);

            // Convert checkbox value to boolean
            $validated['is_available'] = $request->has('is_available');

            $donor = new Donor($validated);
            $donor->user_id = Auth::id();
            $donor->save();

            return redirect()->route('donor.edit')
                ->with('success', 'Donor profile created successfully.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            Log::error('Error creating donor profile: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'request_data' => $request->except(['password']),
                'exception' => $e
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'An unexpected error occurred. Please try again later.']);
        }
    }

    public function update(Request $request, Donor $donor)
    {
        try {
            $this->authorize('update', $donor);

            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'blood_type' => ['required', 'string', Rule::in(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])],
                'contact' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s()]{10,20}$/'],
                'address' => ['required', 'string', 'max:255'],
                'latitude' => ['required', 'numeric', 'between:-90,90'],
                'longitude' => ['required', 'numeric', 'between:-180,180'],
                'is_available' => ['boolean'],
                'health_status' => ['required', 'string', Rule::in(['good', 'pending_review', 'not_eligible'])],
            ], [
                'name.required' => 'Please enter your full name.',
                'name.max' => 'Your name cannot exceed 255 characters.',
                'blood_type.required' => 'Please select your blood type.',
                'blood_type.in' => 'Please select a valid blood type.',
                'contact.required' => 'Please enter your contact number.',
                'contact.regex' => 'Please enter a valid contact number (10-20 digits, may include +, -, spaces, and parentheses).',
                'contact.max' => 'Contact number cannot exceed 20 characters.',
                'address.required' => 'Please enter your address.',
                'address.max' => 'Address cannot exceed 255 characters.',
                'latitude.required' => 'Please select a location on the map.',
                'latitude.between' => 'Invalid latitude value. Please select a location on the map.',
                'longitude.required' => 'Please select a location on the map.',
                'longitude.between' => 'Invalid longitude value. Please select a location on the map.',
                'health_status.required' => 'Please select your health status.',
                'health_status.in' => 'Please select a valid health status.',
            ]);

            // Convert checkbox value to boolean
            $validated['is_available'] = $request->has('is_available');

            $donor->update($validated);

            return redirect()->route('donor.edit')
                ->with('success', 'Donor profile updated successfully.');
        }

        catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            Log::error('Error deleting donor profile: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'donor_id' => $donor->id,
                'exception' => $e
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'An unexpected error occurred. Please try again later.']);
        }   
    }

    public function destroy(Donor $donor)
    {
        try {
            $donor->delete();

            return redirect()->route('donor.create')
                ->with('success', 'Donor profile deleted successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            Log::error('Error  deleting donor profile: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'donor_id' => $donor->id,
                'exception' => $e
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'An unexpected error occurred. Please try again later.']);
        }      
    }
} 