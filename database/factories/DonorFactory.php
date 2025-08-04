<?php

namespace Database\Factories;

use App\Models\Donor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Donor>
 */
class DonorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Donor::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $bloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        $healthStatuses = ['good', 'pending_review', 'not_eligible'];
        
        // Generate coordinates within a 50km radius around Kathmandu
        $latitude = $this->faker->latitude(27.5, 27.9);
        $longitude = $this->faker->longitude(85.2, 85.6);

        return [
            'name' => $this->faker->name,
            'blood_type' => $this->faker->randomElement($bloodTypes),
            'contact' => $this->faker->phoneNumber,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'is_available' => $this->faker->boolean(80), // 80% chance of being available
            'health_status' => $this->faker->randomElement($healthStatuses),
            'last_donation_date' => $this->faker->optional()->dateTimeBetween('-1 year', 'now'),
            'user_id' => User::factory(),
        ];
    }
}
