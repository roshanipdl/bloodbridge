<?php

namespace Database\Factories;

use App\Models\BloodRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class BloodRequestFactory extends Factory
{
    protected $model = BloodRequest::class;

    public function definition()
    {
        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        $urgencyLevels = ['critical', 'urgent', 'normal'];
        
        // Generate coordinates within a reasonable range (example: within a city)
        $latitude = $this->faker->latitude(37.7, 37.8);  // Example: San Francisco area
        $longitude = $this->faker->longitude(-122.5, -122.4);

        return [
            'recipient_id' => User::factory(), // Create a new user for each request
            'recipient_name' => $this->faker->name,
            'blood_group' => $this->faker->randomElement($bloodGroups),
            'units_required' => $this->faker->numberBetween(1, 4),
            'hospital_name' => $this->faker->company . ' Hospital',
            'hospital_address' => $this->faker->address,
            'request_date' => Carbon::now()->subDays($this->faker->numberBetween(0, 7)),
            'urgency_level' => $this->faker->randomElement($urgencyLevels),
            'contact_number' => $this->faker->phoneNumber,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'status' => 'pending',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    /**
     * Indicate that the request is critical.
     */
    public function critical()
    {
        return $this->state(function (array $attributes) {
            return [
                'urgency_level' => 'critical',
            ];
        });
    }

    /**
     * Indicate that the request is urgent.
     */
    public function urgent()
    {
        return $this->state(function (array $attributes) {
            return [
                'urgency_level' => 'urgent',
            ];
        });
    }

    /**
     * Indicate that the request is for a specific blood group.
     */
    public function bloodGroup(string $bloodGroup)
    {
        return $this->state(function (array $attributes) use ($bloodGroup) {
            return [
                'blood_group' => $bloodGroup,
            ];
        });
    }
} 