<?php

namespace Database\Factories;

use App\Models\Recipient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recipient>
 */
class RecipientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Recipient::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $bloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        $urgencyLevels = ['normal', 'urgent', 'emergency'];
        
        // Generate coordinates within a reasonable range (example: within a city)
        $latitude = $this->faker->latitude(27.7, 27.8);  // Example: Kathmandu area
        $longitude = $this->faker->longitude(85.3, 85.4);

        return [
            'name' => $this->faker->name,
            'blood_type_needed' => $this->faker->randomElement($bloodTypes),
            'contact' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'user_id' => User::factory(),
            'latitude' => $latitude,
            'longitude' => $longitude,
            'medical_notes' => $this->faker->optional()->paragraph,
            'special_requirements' => $this->faker->optional()->randomElements([
                'Cross-matched blood required',
                'CMV negative blood needed',
                'Irradiated blood required',
                'Leukocyte-reduced blood needed',
                'Fresh blood within 24 hours'
            ], $this->faker->numberBetween(0, 2)),
            'urgency_level' => $this->faker->randomElement($urgencyLevels),
        ];
    }
}
