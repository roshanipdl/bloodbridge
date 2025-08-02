<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BloodRequest;
use App\Models\Recipient;
use App\Models\Donor;
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
                            ->where('blood_group', $donor->blood_type)
                            ->orderBy('created_at', 'DESC')
                            ->get();
        $scoringService = app(DonorScoringService::class);

        // For each request, score the current donor
        $scoredRequests = $bloodRequests->map(function ($request) use ($donor, $scoringService) {
            // Adapt the scoring service: expects a collection of donors, so wrap $donor in a collection
            $scored = $scoringService->scoreAndRankDonors($request, collect([$donor]))->first();
            $score = $scored ? $scored['score'] : 0;
            $request->donor_score = $score;
            return $request;
        });

        // Sort by score descending, take top 10
        $topRequests = $scoredRequests->sortByDesc('donor_score')->take(10)->values();

        return view('blood.donate', ['bloodRequests' => $topRequests]);
    }

    /**
     * Display the authenticated user's blood requests
     */
    public function myRequests()
    {
        $user = Auth::user();
        $bloodRequests = BloodRequest::where('created_by', $user->id)
            ->with(['creator', 'recipient'])
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('blood.my-requests', compact('bloodRequests'));
    }

    /**
     * Show the form for creating a new blood request.
     */
    public function create()
    {
        $bloodRequest = null;
        $recipients = Recipient::where('user_id', Auth::id())->get(['id', 'name', 'contact']);


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
            'blood_group' => 'required|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'units_required' => 'required|integer|min:1',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'urgency_level' => 'required|string|in:normal,urgent,emergency',
            'required_by_date' => 'required|date|after:today',
            'notes' => 'nullable|string',
        ]);

        // Get recipient details
        $recipient = Recipient::findOrFail($validated['recipient_id']);

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Update the blood request
            $bloodRequest->recipient_id = $validated['recipient_id'];
            $bloodRequest->recipient_name = $recipient->name;
            $bloodRequest->blood_group = $validated['blood_group'];
            $bloodRequest->units_required = $validated['units_required'];
            $bloodRequest->latitude = $validated['latitude'];
            $bloodRequest->longitude = $validated['longitude'];
            $bloodRequest->urgency_level = $validated['urgency_level'];
            $bloodRequest->required_by_date = $validated['required_by_date'];
            $bloodRequest->notes = $validated['notes'];
            $bloodRequest->save();

            DB::commit();

            return redirect()->route('requests.my')
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
            'blood_group' => 'required|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'units_required' => 'required|integer|min:1',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'urgency_level' => 'required|string|in:normal,urgent,emergency',
            'required_by_date' => 'required|date|after:today',
            'notes' => 'nullable|string',
        ]);

        // Get recipient details
        $recipient = Recipient::findOrFail($validated['recipient_id']);

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Create the blood request
            $bloodRequest = new BloodRequest();
            $bloodRequest->recipient_id = $validated['recipient_id'];
            $bloodRequest->recipient_name = $recipient->name;
            $bloodRequest->blood_group = $validated['blood_group'];
            $bloodRequest->units_required = $validated['units_required'];
            $bloodRequest->latitude = $validated['latitude'];
            $bloodRequest->longitude = $validated['longitude'];
            $bloodRequest->urgency_level = $validated['urgency_level'];
            $bloodRequest->required_by_date = $validated['required_by_date'];
            $bloodRequest->notes = $validated['notes'];
            $bloodRequest->status = 'pending';
            $bloodRequest->created_by = Auth::id();
            $bloodRequest->save();

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
     * Find matching donors using the weighted scoring algorithm.
     */
    private function findMatchingDonors(BloodRequest $bloodRequest): array
    {
        // Get eligible donors
        $donors = Donor::where('is_available', true)
            ->where('health_status', true)
            ->whereRaw('(last_donation_date IS NULL OR last_donation_date <= DATE_SUB(NOW(), INTERVAL 3 MONTH))')
            ->get();

        $matches = [];
        $maxDistance = 50; // Maximum distance in kilometers

        foreach ($donors as $donor) {
            // Skip if blood type is not compatible
            if (!$this->isBloodTypeCompatible($donor->blood_type, $bloodRequest->blood_group)) {
                continue;
            }

            // Calculate weighted score
            $score = $this->calculateDonorScore($donor, $bloodRequest, $maxDistance);

            if ($score > 0) {
                $matches[] = [
                    'donor' => $donor,
                    'score' => $score
                ];
            }
        }

        // Sort matches by score in descending order
        usort($matches, function($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        return $matches;
    }

    /**
     * Calculate donor score based on weighted criteria.
     */
    private function calculateDonorScore(Donor $donor, BloodRequest $bloodRequest, float $maxDistance): float
    {
        $score = 0;
        
        // 1. Location Proximity (35%)
        $distance = $this->calculateDistance(
            $donor->latitude, $donor->longitude,
            $bloodRequest->latitude, $bloodRequest->longitude
        );
        
        if ($distance <= $maxDistance) {
            $score += 35 * (1 - $distance / $maxDistance);
        }

        // 2. Eligibility (25%)
        $isEligible = !$donor->last_donation_date || 
            now()->diffInMonths($donor->last_donation_date) >= 3;
        if ($isEligible) {
            $score += 25;
        }

        // 3. Blood Type Compatibility (20%)
        if ($this->isBloodTypeCompatible($donor->blood_type, $bloodRequest->blood_group)) {
            $score += 20;
        }

        // 4. Donor Regularity (10%)
        $donationHistory = json_decode($donor->donation_history ?? '[]', true);
        $regularityScore = min(count($donationHistory), 8) / 8;
        $score += 10 * $regularityScore;

        // 5. Donor Availability (5%)
        if ($donor->is_available) {
            $score += 5;
        }

        // 6. Health Status (5%)
        if ($donor->health_status) {
            $score += 5;
        }

        return $score;
    }

    /**
     * Calculate distance between two points using Haversine formula.
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2): float
    {
        $earthRadius = 6371; // Earth's radius in kilometers

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($lonDelta / 2) * sin($lonDelta / 2);
            
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        
        return $earthRadius * $c;
    }

    /**
     * Check if donor blood type is compatible with recipient blood type.
     */
    private function isBloodTypeCompatible(string $donorType, string $recipientType): bool
    {
        $compatibility = [
            'O-'  => ['O-', 'O+', 'A-', 'A+', 'B-', 'B+', 'AB-', 'AB+'],
            'O+'  => ['O+', 'A+', 'B+', 'AB+'],
            'A-'  => ['A-', 'A+', 'AB-', 'AB+'],
            'A+'  => ['A+', 'AB+'],
            'B-'  => ['B-', 'B+', 'AB-', 'AB+'],
            'B+'  => ['B+', 'AB+'],
            'AB-' => ['AB-', 'AB+'],
            'AB+' => ['AB+']
        ];

        return in_array($recipientType, $compatibility[$donorType] ?? []);
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
            'donor_id' => $donor->id,
            'status' => 'assigned',
            'fulfill_date' => now()->addDays(7) // Set a default fulfillment date
        ]);

        // Update donor's donation history
        $donationHistory = json_decode($donor->donation_history ?? '[]', true);
        $donor->update([
            'donation_history' => json_encode($donationHistory),
            'last_donation_date' => now()
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'You have successfully responded to the blood request!');
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
        $matchingDonors = $this->findMatchingDonors($bloodRequest);

        // Calculate distances for display
        foreach ($matchingDonors as &$match) {
            $match['distance'] = $this->calculateDistance(
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
