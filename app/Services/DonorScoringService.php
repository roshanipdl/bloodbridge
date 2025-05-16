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
        ->filter()
        ->filter(function ($result) {
            return $result['detailed_scores']['blood_compatibility'] > 0 
                && $result['detailed_scores']['eligibility'] > 0;
        })
        ->sortByDesc('score')
        ->values();
    }

    /**
     * Calculate location-based score using cached distance calculations
     */
    private function calculateLocationScore($donor, $request)
    {
        $cacheKey = "distance_{$donor->id}_{$request->id}";
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($donor, $request) {
            $distance = $this->calculateDistance(
                $donor->latitude,
                $donor->longitude,
                $request->latitude,
                $request->longitude
            );

            return max(0, 1 - ($distance / self::MAX_DISTANCE));
        });
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

        return in_array($donor->blood_type, $compatibilityMatrix[$request->blood_type]) ? 1 : 0;
    }

    /**
     * Calculate donor regularity score based on donation history
     */
    private function calculateDonorRegularityScore($donor)
    {
        $cacheKey = "donor_regularity_{$donor->id}";
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($donor) {
            $maxDonations = 8; // Maximum expected donations in 2 years
            $donationsIn2Years = $donor->donations()
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
     * Calculate distance between two points using Haversine formula
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
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