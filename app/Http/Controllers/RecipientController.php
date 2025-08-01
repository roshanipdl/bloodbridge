<?php

namespace App\Http\Controllers;

use App\Models\Recipient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecipientController extends Controller
{
    /**
     * Show the form for creating a new recipient.
     */
    public function create()
    {
        return view('recipients.create');
    }

    /**
     * Store a newly created recipient in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'contact' => 'required|string|max:20',
                'address' => 'required|string|max:255',
                'blood_type_needed' => 'required|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
                'medical_notes' => 'nullable|string',
                'special_requirements' => 'nullable|json'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            dd($e->errors());
        }

        $recipient = new Recipient($validated);
        $recipient->user_id = Auth::id();
        $recipient->save();

        return redirect()->route('dashboard')
            ->with('success', 'Recipient profile created successfully.');
    }

    /**
     * Show the form for editing the recipient.
     */
    public function edit(Recipient $recipient)
    {
        $this->authorize('update', $recipient);
        return view('recipients.edit', compact('recipient'));
    }

    /**
     * Update the specified recipient in storage.
     */
    public function update(Request $request, Recipient $recipient)
    {
        $this->authorize('update', $recipient);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'blood_type_needed' => 'required|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'medical_notes' => 'nullable|string'
        ]);

        $recipient->update($validated);

        return redirect()->route('dashboard')
            ->with('success', 'Recipient profile updated successfully.');
    }
}
