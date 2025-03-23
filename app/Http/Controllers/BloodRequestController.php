<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BloodRequestController extends Controller
{
    public function index()
    {
        // Get all pending blood requests
        $bloodRequests = BloodRequest::where('status', 'pending')
            ->latest()
            ->paginate(10);
            
        // Get all requests where the current user is the recipient
        $myRequests = BloodRequest::where('recipient_id', Auth::id())
            ->latest()
            ->get();
            
        return view('blood-requests.index', compact('bloodRequests', 'myRequests'));
    }

    public function create()
    {
        return view('blood-requests.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'blood_type' => 'required|string',
            'location' => 'required|string',
            'description' => 'nullable|string',
            'contact_number' => 'required|string',
            'hospital_name' => 'nullable|string',
        ]);

        // Create a new blood request
        BloodRequest::create([
            'status' => 'pending',
            'request_date' => now(),
            'fulfill_date' => null,
            'donor_id' => null,
            'recipient_id' => Auth::id(),
            // Store additional data in a separate table or as JSON
        ]);
        
        return redirect()->route('blood-requests.index')
            ->with('success', 'Blood request created successfully.');
    }

    public function show(BloodRequest $bloodRequest)
    {
        // Get additional request details if stored separately
        return view('blood-requests.show', compact('bloodRequest'));
    }

    public function edit(BloodRequest $bloodRequest)
    {
        $this->authorize('update', $bloodRequest);
        
        return view('blood-requests.edit', compact('bloodRequest'));
    }

    public function update(Request $request, BloodRequest $bloodRequest)
    {
        $this->authorize('update', $bloodRequest);
        
        $validated = $request->validate([
            'status' => 'required|in:pending,fulfilled,cancelled',
        ]);
        
        $bloodRequest->update([
            'status' => $validated['status'],
            'fulfill_date' => $validated['status'] === 'fulfilled' ? now() : null,
        ]);
        
        return redirect()->route('blood-requests.index')
            ->with('success', 'Blood request updated successfully.');
    }

    public function destroy(BloodRequest $bloodRequest)
    {
        $this->authorize('delete', $bloodRequest);
        
        $bloodRequest->delete();
        
        return redirect()->route('blood-requests.index')
            ->with('success', 'Blood request deleted successfully.');
    }
}

