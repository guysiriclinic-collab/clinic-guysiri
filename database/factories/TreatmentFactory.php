<?php

namespace Database\Factories;

use App\Models\Treatment;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Queue;
use App\Models\Branch;
use App\Models\User;
use App\Models\Service;
use App\Models\OpdRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

class TreatmentFactory extends Factory
{
    protected $model = Treatment::class;

    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'branch_id' => Branch::factory(),
            'pt_id' => User::factory(),
            'opd_id' => OpdRecord::factory(), // Reference to OPD record
            'service_id' => Service::factory(),
            'chief_complaint' => fake()->sentence(),
            'vital_signs' => [
                'blood_pressure' => fake()->randomNumber(3) . '/' . fake()->randomNumber(2),
                'pulse' => fake()->numberBetween(60, 100),
                'temperature' => fake()->randomFloat(1, 36, 38),
            ],
            'assessment' => fake()->paragraph(),
            'diagnosis' => fake()->sentence(),
            'treatment_plan' => fake()->paragraph(),
            'treatment_notes' => fake()->optional()->paragraph(),
            'started_at' => now(),
            'billing_status' => 'pending',
            'df_amount' => fake()->randomFloat(2, 100, 500),
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'started_at' => now()->subHour(),
            'completed_at' => now(),
            'duration_minutes' => 60,
        ]);
    }

    public function billed(): static
    {
        return $this->state(fn (array $attributes) => [
            'billing_status' => 'billed',
        ]);
    }
}
