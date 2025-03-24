<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BloodRequest;
class BloodRequestController extends Controller
{
    /**
     * Display a listing of blood requests for potential donors.
     */
    public function index()
    {
        $bloodRequests = BloodRequest::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('blood.donate', compact('bloodRequests'));
    }


    /**
     * Show the form for creating a new blood request.
     */
    public function create()
    {
        return view('blood.request');
    }

    /**
     * Store a newly created blood request in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'blood_group' => 'required|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'units_required' => 'required|integer|min:1',
            'hospital_name' => 'required|string|max:255',
            'hospital_address' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'request_date' => 'required|date',
            'urgency_level' => 'required|string|in:normal,urgent,critical',
            'additional_info' => 'nullable|string',
        ]);
        
        $bloodRequest = new BloodRequest($validated);
        $bloodRequest->recipient_id = auth()->id();
        $bloodRequest->status = 'pending';
        $bloodRequest->save();
        
        return redirect()->route('dashboard')
            ->with('success', 'Blood request submitted successfully.');
    }

    /**
     * Respond to a blood request.
     */
    public function respond(BloodRequest $bloodRequest)
    {
        // Check if request is still pending
        if ($bloodRequest->status !== 'pending') {
            return redirect()->route('donate')
                ->with('error', 'This blood request has already been fulfilled or cancelled.');
        }
        
        // Update the blood request
        $bloodRequest->donor_id = auth()->id();
        $bloodRequest->status = 'fulfilled';
        $bloodRequest->fulfill_date = now()->toDateString();
        $bloodRequest->save();
        
        // Here you would implement notification logic
        // For example, sending an email to the recipient
        
        return redirect()->route('donate')
            ->with('success', 'Thank you for your willingness to donate! The recipient has been notified of your response.');
    }
}
