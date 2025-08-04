<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BloodRequest;
use App\Models\Donor;
use App\Models\DonationHistory;
use App\Models\Recipient;
use Illuminate\Support\Facades\DB;
use App\Services\DonorScoringService;
use Illuminate\Support\Facades\Auth;

class BloodRequestController extends Controller
{

    /**
     * Display a listing of blood requests for potential donors.
     */
    public function index()
    {
        $user = Auth::user();
        $donor = \App\Models\Donor::where('user_id', $user->id)->first();

        if (!$donor) {
            // Redirect to donor creation form if no donor entry exists
            return redirect()->route('donor.create');
        }

        $bloodRequests = BloodRequest::where('status', 'pending')
                            ->where('created_by', '!=', $user->id)
                            ->orderBy('created_at', 'DESC')
                            ->get();
        $scoringService = app(DonorScoringService::class);

        // For each request, score the current donor
        $scoredRequests = $bloodRequests->map(function ($request) use ($donor, $scoringService) {
            // Adapt the scoring service: expects a collection of donors, so wrap $donor in a collection
            $scored = $scoringService->scoreAndRankDonors($request, collect([$donor]))->first();
            $score = $scored ? $scored['score'] : 0;
            $request->donor_score = $score;
            $request->distance = $scoringService->calculateDistance($donor->latitude, $donor->longitude, $request->latitude, $request->longitude);
            $request->blood_group = $request->recipient->blood_group;

            return $request;
        })->filter(function ($request) {
            return $request->donor_score >= 0.5;
        });

        // Sort by score descending, take top 10
        $topRequests = $scoredRequests->sortByDesc('donor_score')->take(10)->values();

        return view('blood.donate', ['bloodRequests' => $topRequests]);
    }

    /**
     * Display the authenticated user's blood requests
     */
    public function myRequests(Request $request)
    {
        $query = BloodRequest::where('created_by', auth()->id())
            ->with(['donor', 'recipient']);

        // Filter by recipient_id if provided
        if ($request->has('recipient_id')) {
            $query->where('recipient_id', $request->recipient_id);
        }

        $bloodRequests = $query->latest()->paginate(10);

        return view('blood.my-requests', [
            'bloodRequests' => $bloodRequests
        ]);
    }

    /**
     * Show the form for creating a new blood request.
     */
    public function create()
    {
        $bloodRequest = null;
        $recipients = Recipient::where('user_id', Auth::id())->get(['id', 'name', 'contact', 'blood_group']);


        return view('blood.request', compact('recipients', 'bloodRequest'));
    }

    /**
     * Show the form for editing a blood request.
     */
    public function edit(BloodRequest $bloodRequest)
    {
        // Ensure the user owns this request
        if ($bloodRequest->created_by !== Auth::id()) {
            abort(403, 'Unauthorized action');
        }

        $recipients = Recipient::where('user_id', Auth::id())->get(['id', 'name', 'contact']);
        return view('blood.request', compact('bloodRequest', 'recipients'));
    }

    /**
     * Update the specified blood request in storage.
     */
    public function update(Request $request, BloodRequest $bloodRequest)
    {
        // Ensure the user owns this request
        if ($bloodRequest->created_by !== Auth::id()) {
            abort(403, 'Unauthorized action');
        }
        
        $validated = $request->validate([
            'recipient_id' => 'required|exists:recipients,id',
            'units_required' => 'required|integer|min:1',
            'urgency_level' => 'required|in:normal,urgent,critical',
            'hospital_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'required_by_date' => 'required|date|after_or_equal:today',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric'
        ]);

        // Get recipient details
        $recipient = Recipient::findOrFail($validated['recipient_id']);

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Update the blood request
            $bloodRequest->update([
                'recipient_name' => $recipient->name,
                'units_required' => $validated['units_required'],
                'urgency_level' => $validated['urgency_level'],
                'hospital_name' => $validated['hospital_name'] ?? null,
                'notes' => $validated['notes'],
                'status' => 'pending',
                'recipient_id' => $recipient->id,
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'required_by_date' => $validated['required_by_date']
            ]);

            DB::commit();

            return redirect()->route('blood.requests.my')
                ->with('success', 'Blood request updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while updating the request. Please try again.');
        }
    }

    /**
     * Store a newly created blood request in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipient_id' => 'required|exists:recipients,id',
            'units_required' => 'required|integer|min:1',
            'urgency_level' => 'required|in:normal,urgent,critical',
            'hospital_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'required_by_date' => 'required|date|after_or_equal:today'
        ]);

        // Get recipient details
        $recipient = Recipient::findOrFail($validated['recipient_id']);

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Create the blood request
            $bloodRequest = BloodRequest::create([
                'recipient_name' => $recipient->name,
                'units_required' => $validated['units_required'],
                'urgency_level' => $validated['urgency_level'],
                'hospital_name' => $validated['hospital_name'] ?? null,
                'notes' => $validated['notes'],
                'status' => 'pending',
                'recipient_id' => $recipient->id,
                'latitude' => $recipient->latitude,
                'longitude' => $recipient->longitude,
                'required_by_date' => $validated['required_by_date'],
                'created_by' => Auth::id()
            ]);

            DB::commit();

            return redirect()->route('dashboard')
                ->with('success', 'Blood request submitted successfully.');
        } catch (\Exception $e) {

            dd($e->getMessage());
            // DB::rollBack();
            // return back()->with('error', 'An error occurred while processing your request. Please try again.');
        }
    }

    /**
     * Respond to a blood request.
     */
    public function respond(BloodRequest $bloodRequest)
    {
        // Check if request is still pending
        if ($bloodRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'This request is no longer pending.');
        }

        $user = Auth::user();
        $donor = $user->donor;

        if (!$donor) {
            return redirect()->route('donor.create');
        }

        // Check if donor is already assigned to this request
        if ($bloodRequest->donor_id) {
            return redirect()->back()->with('error', 'This request already has a donor assigned.');
        }

        // Update the blood request with donor information
        $bloodRequest->update([
            'status' => 'processing',
        ]);

        // Create entry in donation_requests
        $donationRequest = \App\Models\DonationRequest::create([
            'blood_request_id' => $bloodRequest->id,
            'donor_id' => $donor->id,
            'status' => 'pending',
            'notes' => 'Assigned to blood request via response'
        ]);

        // Create new donation history record
        \App\Models\DonationHistory::create([
            'donor_id' => $donor->id,
            'blood_request_id' => $bloodRequest->id,
            'donation_request_id' => $donationRequest->id,
            'donation_date' => now(),
            'blood_group' => $bloodRequest->recipient->blood_group,
            'notes' => 'Assigned to blood request via response'
        ]);

        // Update donor's last donation date
        $donor->update([
            'last_donation_date' => now()
        ]);

        // Redirect with success message
        return redirect()->route('requests.my')->with('success', 'Successfully responded to blood request.');
    }

    /**
     * Remove the specified blood request from storage.
     */
    public function destroy(BloodRequest $bloodRequest)
    {
        // Ensure the user owns this request
        if ($bloodRequest->created_by !== Auth::id()) {
            abort(403, 'Unauthorized action');
        }

        // Only allow deletion of pending requests
        if ($bloodRequest->status !== 'pending') {
            return redirect()->route('blood.requests.my')
                ->with('error', 'Only pending requests can be deleted.');
        }

        $bloodRequest->delete();

        return redirect()->route('requests.my')
            ->with('success', 'Blood request has been deleted.');
    }

    /**
     * Display matching donors for a blood request.
     */
    public function viewMatchingDonors(BloodRequest $bloodRequest)
    {
        // Ensure the user owns this request
        if ($bloodRequest->created_by !== Auth::id()) {
            abort(403, 'Unauthorized action');
        }

        // Find matching donors
        $scoringService = app(DonorScoringService::class);
        $matchingDonors = $scoringService->findMatchingDonors($bloodRequest);

        // Calculate distances for display
        foreach ($matchingDonors as &$match) {
            $match['distance'] = $scoringService->calculateDistance(
                $match['donor']->latitude,
                $match['donor']->longitude,
                $bloodRequest->latitude,
                $bloodRequest->longitude
            );
        }

        return view('blood.matching-donors', [
            'bloodRequest' => $bloodRequest,
            'matchingDonors' => $matchingDonors
        ]);
    }
}
