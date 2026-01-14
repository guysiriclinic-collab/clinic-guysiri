<?php

namespace Database\Factories;

use App\Models\OpdRecord;
use App\Models\Patient;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OpdRecordFactory extends Factory
{
    protected $model = OpdRecord::class;

    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'branch_id' => Branch::factory(),
            'opd_number' => 'OPD' . now()->format('Ymd') . str_pad(fake()->unique()->randomNumber(4), 4, '0', STR_PAD_LEFT),
            'status' => 'active',
            'chief_complaint' => fake()->sentence(),
            'is_temporary' => false,
            'created_by' => User::factory(),
        ];
    }

    public function temporary(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_temporary' => true,
        ]);
    }
}
