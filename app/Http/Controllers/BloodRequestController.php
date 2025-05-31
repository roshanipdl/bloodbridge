<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BloodRequest;
use App\Models\Recipient;
use App\Models\Donor;
use Illuminate\Support\Facades\DB;
use App\Services\DonorScoringService;
use App\Services\GooglePlacesService;
use Illuminate\Support\Facades\Auth;

class BloodRequestController extends Controller
{
    protected $placesService;

    public function __construct(GooglePlacesService $placesService)
    {
        $this->placesService = $placesService;
    }

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

        $bloodRequests = BloodRequest::where('status', 'pending')->get();
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
     * Show the form for creating a new blood request.
     */
    public function create()
    {
        $recipients = Recipient::all(['id', 'name', 'blood_type_needed', 'contact']);
        return view('blood.request', compact('recipients'));
    }

    /**
     * Store a newly created blood request in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipient_id' => 'required|exists:recipients,id',
            'blood_type_needed' => 'required|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'units_required' => 'required|integer|min:1',
            'hospital_name' => 'required|string|max:255',
            'hospital_address' => 'required|string|max:255',
            'place_id' => 'required|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'urgency_level' => 'required|string|in:normal,urgent,emergency',
            'notes' => 'nullable|string',
        ]);

        // Get recipient details
        $recipient = Recipient::findOrFail($validated['recipient_id']);

        // Get place details from Google Places API
        $placeDetails = $this->placesService->getPlaceDetails($validated['place_id']);
        
        if ($placeDetails) {
            $validated['place_name'] = $placeDetails['place_name'];
            $validated['city'] = $placeDetails['city'];
            $validated['hospital_address'] = $placeDetails['formatted_address'];
            $validated['latitude'] = $placeDetails['latitude'];
            $validated['longitude'] = $placeDetails['longitude'];
        }

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Create the blood request
            $bloodRequest = new BloodRequest();
            $bloodRequest->recipient_id = $validated['recipient_id'];
            $bloodRequest->blood_type_needed = $validated['blood_type_needed'];
            $bloodRequest->units_required = $validated['units_required'];
            $bloodRequest->hospital_name = $validated['hospital_name'];
            $bloodRequest->hospital_address = $validated['hospital_address'];
            $bloodRequest->place_name = $validated['place_name'];
            $bloodRequest->city = $validated['city'];
            $bloodRequest->latitude = $validated['latitude'];
            $bloodRequest->longitude = $validated['longitude'];
            $bloodRequest->urgency_level = $validated['urgency_level'];
            $bloodRequest->notes = $validated['notes'];
            $bloodRequest->status = 'pending';
            $bloodRequest->save();

            // Find matching donors using weighted scoring algorithm
            $matchingDonors = $this->findMatchingDonors($recipient, $validated['blood_type_needed']);

            // Store donor matches with their scores
            foreach ($matchingDonors as $match) {
                $bloodRequest->potentialDonors()->attach($match['donor']->id, [
                    'score' => $match['score'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            DB::commit();

            return redirect()->route('dashboard')
                ->with('success', 'Blood request submitted successfully. Matching donors have been notified.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while processing your request. Please try again.');
        }
    }

    /**
     * Find matching donors using the weighted scoring algorithm.
     */
    private function findMatchingDonors(Recipient $recipient, string $bloodTypeNeeded)
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
            if (!$this->isBloodTypeCompatible($donor->blood_type, $bloodTypeNeeded)) {
                continue;
            }

            // Calculate weighted score
            $score = $this->calculateDonorScore($donor, $recipient, $maxDistance);

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

        // Return top 5 matches
        return array_slice($matches, 0, 5);
    }

    /**
     * Calculate donor score based on weighted criteria.
     */
    private function calculateDonorScore(Donor $donor, Recipient $recipient, float $maxDistance): float
    {
        $score = 0;
        
        // 1. Location Proximity (35%)
        $distance = $this->calculateDistance(
            $donor->latitude, $donor->longitude,
            $recipient->latitude, $recipient->longitude
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
        if ($this->isBloodTypeCompatible($donor->blood_type, $recipient->blood_type_needed)) {
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
            return redirect()->route('donate')
                ->with('error', 'This blood request has already been fulfilled or cancelled.');
        }
        
        // Update the blood request
        $bloodRequest->donor_id = auth()->id();
        $bloodRequest->status = 'fulfilled';
        $bloodRequest->fulfill_date = now();
        $bloodRequest->save();
        
        // Update donor's last donation date and donation history
        $donor = Donor::find(auth()->id());
        if ($donor) {
            $donor->last_donation_date = now();
            $donationHistory = json_decode($donor->donation_history ?? '[]', true);
            $donationHistory[] = [
                'date' => now()->toDateString(),
                'request_id' => $bloodRequest->id
            ];
            $donor->donation_history = json_encode($donationHistory);
            $donor->total_donations = count($donationHistory);
            $donor->save();
        }
        
        return redirect()->route('donate')
            ->with('success', 'Thank you for your willingness to donate! The recipient has been notified of your response.');
    }
}
