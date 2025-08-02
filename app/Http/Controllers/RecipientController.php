<?php

namespace App\Http\Controllers;

use App\Models\Recipient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecipientController extends Controller
{
    /**
     * Display the authenticated user's recipients.
     */
    public function myRecipients()
    {
        $recipients = Recipient::where('user_id', Auth::id())->get();
        return view('recipients.my-recipients', compact('recipients'));
    }

    /**
     * Show the form for creating a new recipient.
     */
    public function create()
    {
        $recipient = null;

        return view('recipients.create', compact('recipient'));
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
                'medical_notes' => 'nullable|string',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            dd($e->errors());
        }

        $recipient = new Recipient();
        $recipient->name = $validated['name'];
        $recipient->contact = $validated['contact'];
        $recipient->address = $validated['address'];
        $recipient->medical_notes = $validated['medical_notes'];
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
        // Ensure the recipient belongs to the authenticated user
        if ($recipient->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action');
        }

        return view('recipients.create', compact('recipient'));
    }

    /**
     * Update the specified recipient in storage.
     */
    public function update(Request $request, Recipient $recipient)
    {
        // Ensure the recipient belongs to the authenticated user
        if ($recipient->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'medical_notes' => 'nullable|string'
        ]);

        $recipient->update($validated);

        return redirect()->route('dashboard')
            ->with('success', 'Recipient profile updated successfully.');
    }
}
