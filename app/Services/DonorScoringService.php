<?php

namespace App\Services;

use App\Models\Donor;
use App\Models\BloodRequest;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * DonorScoringService
 * 
 * Implements a weighted scoring algorithm to match blood donors with recipients
 * based on multiple criteria including location, eligibility, blood compatibility,
 * donor regularity, availability, and health status.
 */
class DonorScoringService
{
    private const MAX_DISTANCE = 50; // in kilometers
    private const CACHE_TTL = 3600; // 1 hour in seconds
    private const WEIGHTS = [
        'location' => 0.35,
        'eligibility' => 0.25,
        'blood_compatibility' => 0.20,
        'donor_regularity' => 0.10,
        'availability' => 0.05,
        'health_status' => 0.05
    ];

    /**
     * Score and rank potential donors for a blood request
     *
     * @param BloodRequest $request The blood request to match donors for
     * @param Collection $donors Collection of potential donors
     * @return Collection Ranked and scored donors
     */
    public function scoreAndRankDonors(BloodRequest $request, Collection $donors)
    {
        $this->validateInput($request, $donors);

        return $donors->map(function ($donor) use ($request) {
            try {
                $scores = [
                    'location' => $this->calculateLocationScore($donor, $request),
                    'eligibility' => $this->calculateEligibilityScore($donor),
                    'blood_compatibility' => $this->calculateBloodCompatibilityScore($donor, $request),
                    'donor_regularity' => $this->calculateDonorRegularityScore($donor),
                    'availability' => $this->calculateAvailabilityScore($donor),
                    'health_status' => $this->calculateHealthStatusScore($donor)
                ];

                $totalScore = $this->calculateTotalScore($scores);

                return [
                    'donor' => $donor,
                    'score' => $totalScore,
                    'detailed_scores' => $scores
                ];
            } catch (\Exception $e) {
                Log::error('Error scoring donor', [
                    'donor_id' => $donor->id,
                    'error' => $e->getMessage()
                ]);
                return null;
            }
        })
        // ->filter()
        // ->filter(function ($result) {
        //     return $result['detailed_scores']['blood_compatibility'] > 0 
        //         && $result['detailed_scores']['eligibility'] > 0;
        // })
        // ->sortByDesc('score')
        ->values();
    }

    /**
     * Calculate location-based score using cached distance calculations
     */
    private function calculateLocationScore($donor, $request)
    {
        
            $distance = $this->calculateDistance(
                $donor->latitude,
                $donor->longitude,
                $request->latitude,
                $request->longitude
            );

            return max(0, 1 - ((float) $distance / self::MAX_DISTANCE));
    }

    /**
     * Calculate eligibility score based on time since last donation
     */
    private function calculateEligibilityScore($donor)
    {
        if (!$donor->last_donation_date) {
            return 1;
        }

        $monthsSinceLastDonation = Carbon::parse($donor->last_donation_date)->diffInMonths(now());
        return $monthsSinceLastDonation >= 3 ? 1 : 0;
    }

    /**
     * Calculate blood type compatibility score
     */
    private function calculateBloodCompatibilityScore($donor, $request)
    {
        $recipient = $request->recipient;
        if (!$recipient) {
            return 0;
        }

        $compatibilityMatrix = [
            'A+' => ['A+', 'A-', 'O+', 'O-'],
            'A-' => ['A-', 'O-'],
            'B+' => ['B+', 'B-', 'O+', 'O-'],
            'B-' => ['B-', 'O-'],
            'AB+' => ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'],
            'AB-' => ['A-', 'B-', 'AB-', 'O-'],
            'O+' => ['O+', 'O-'],
            'O-' => ['O-']
        ];

        return in_array($donor->blood_type, $compatibilityMatrix[$recipient->blood_group]) ? 1 : 0;
    }

    /**
     * Calculate donor regularity score based on donation history
     */
    private function calculateDonorRegularityScore($donor)
    {
        $cacheKey = "donor_regularity_{$donor->id}";
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($donor) {
            $maxDonations = 8; // Maximum expected donations in 2 years
            $donationsIn2Years = $donor->donationHistory
                ->where('created_at', '>=', now()->subYears(2))
                ->count();

            return min(1, $donationsIn2Years / $maxDonations);
        });
    }

    /**
     * Calculate availability score
     */
    private function calculateAvailabilityScore($donor)
    {
        return $donor->is_available ? 1 : 0;
    }

    /**
     * Calculate health status score
     */
    private function calculateHealthStatusScore($donor)
    {
        return $donor->health_status === 'good' ? 1 : 0;
    }

    /**
     * Calculate total weighted score
     */
    private function calculateTotalScore($scores)
    {
        return collect($scores)
            ->map(function ($score, $criterion) {
                return $score * self::WEIGHTS[$criterion];
            })
            ->sum();
    }

    /**
     * Get compatible blood types for a given blood group
     */
    private function getCompatibleBloodTypes(string $bloodGroup): array
    {
        $compatibilityMatrix = [
            'A+' => ['A+', 'A-', 'O+', 'O-'],
            'A-' => ['A-', 'O-'],
            'B+' => ['B+', 'B-', 'O+', 'O-'],
            'B-' => ['B-', 'O-'],
            'AB+' => ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'],
            'AB-' => ['A-', 'B-', 'AB-', 'O-'],
            'O+' => ['O+', 'O-'],
            'O-' => ['O-']
        ];

        return $compatibilityMatrix[$bloodGroup] ?? [];
    }

    /**
     * Check if donor blood type is compatible with recipient blood type
     */
    private function isBloodTypeCompatible(string $donorType, string $recipientType): bool
    {
        return in_array($donorType, $this->getCompatibleBloodTypes($recipientType));
    }



    /**
     * Find matching donors for a blood request
     */
    public function findMatchingDonors(BloodRequest $request): array
    {
        $donors = Donor::where('is_available', true)
            ->where('health_status', true)
            ->whereRaw('(last_donation_date IS NULL OR last_donation_date <= DATE_SUB(NOW(), INTERVAL 3 MONTH))')
            ->get();

        $matches = [];
        $maxDistance = 50; // Maximum distance in kilometers

        foreach ($donors as $donor) {
            // Skip if blood type is not compatible
            if (!$this->isBloodTypeCompatible($donor->blood_type, $request->recipient->blood_group)) {
                continue;
            }

            // Calculate weighted score
            $scores = [
                'location' => $this->calculateLocationScore($donor, $request),
                'eligibility' => $this->calculateEligibilityScore($donor),
                'blood_compatibility' => $this->calculateBloodCompatibilityScore($donor, $request),
                'donor_regularity' => $this->calculateDonorRegularityScore($donor),
                'availability' => $this->calculateAvailabilityScore($donor),
                'health_status' => $this->calculateHealthStatusScore($donor)
            ];

            $totalScore = $this->calculateTotalScore($scores);

            if ($totalScore > 0) {
                $matches[] = [
                    'donor' => $donor,
                    'score' => $totalScore,
                    'detailed_scores' => $scores
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
     * Calculate distance between two points using Haversine formula
     */
    public function calculateDistance($lat1, $lon1, $lat2, $lon2)
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
     * Validate input parameters
     */
    private function validateInput($request, $donors)
    {
        if (!$request instanceof BloodRequest) {
            throw new \InvalidArgumentException('Invalid blood request object');
        }

        if (!$donors instanceof Collection) {
            throw new \InvalidArgumentException('Donors must be a Collection');
        }

        if ($donors->isEmpty()) {
            Log::warning('Empty donors collection provided to scoring service');
        }
    }
}