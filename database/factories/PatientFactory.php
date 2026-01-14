<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;

class PatientFactory extends Factory
{
    protected $model = Patient::class;

    public function definition(): array
    {
        return [
            'is_temporary' => false,
            'hn_number' => null,
            'phone' => fake()->unique()->numerify('08########'),
            'name' => fake()->name(),
            'prefix' => fake()->randomElement(['นาย', 'นาง', 'นางสาว']),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'id_card' => fake()->numerify('#############'),
            'date_of_birth' => fake()->dateTimeBetween('-70 years', '-18 years'),
            'gender' => fake()->randomElement(['male', 'female']),
            'blood_group' => fake()->randomElement(['A', 'B', 'AB', 'O']),
            'address' => fake()->address(),
            'email' => fake()->safeEmail(),
            'chronic_diseases' => null,
            'drug_allergy' => null,
            'booking_channel' => fake()->randomElement(['walk_in', 'line', 'phone']),
            'first_visit_branch_id' => Branch::factory(),
            'branch_id' => function (array $attributes) {
                return $attributes['first_visit_branch_id'];
            },
        ];
    }

    public function temporary(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_temporary' => true,
            'hn_number' => null,
        ]);
    }

    public function withHn(string $hn = null): static
    {
        return $this->state(fn (array $attributes) => [
            'is_temporary' => false,
            'hn_number' => $hn ?? 'HN' . str_pad(fake()->unique()->randomNumber(6), 6, '0', STR_PAD_LEFT),
        ]);
    }
}
