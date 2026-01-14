<?php

namespace Database\Factories;

use App\Models\Queue;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class QueueFactory extends Factory
{
    protected $model = Queue::class;

    public function definition(): array
    {
        return [
            'appointment_id' => Appointment::factory(),
            'patient_id' => Patient::factory(),
            'branch_id' => Branch::factory(),
            'pt_id' => User::factory(),
            'queue_number' => fake()->randomNumber(3),
            'status' => 'waiting',
            'queued_at' => now(),
            'is_overtime' => false,
        ];
    }

    public function waiting(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'waiting',
            'called_at' => null,
            'started_at' => null,
        ]);
    }

    public function called(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'called',
            'called_at' => now(),
            'started_at' => null,
        ]);
    }

    public function inTreatment(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'in_treatment',
            'called_at' => now()->subMinutes(5),
            'started_at' => now(),
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'called_at' => now()->subHour(),
            'started_at' => now()->subMinutes(50),
            'completed_at' => now(),
        ]);
    }
}
