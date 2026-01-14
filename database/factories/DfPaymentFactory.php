<?php

namespace Database\Factories;

use App\Models\DfPayment;
use App\Models\User;
use App\Models\Treatment;
use App\Models\Branch;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class DfPaymentFactory extends Factory
{
    protected $model = DfPayment::class;

    public function definition(): array
    {
        return [
            'df_number' => 'DF' . now()->format('Ymd') . str_pad(fake()->unique()->randomNumber(4), 4, '0', STR_PAD_LEFT),
            'pt_id' => User::factory(),
            'treatment_id' => Treatment::factory(),
            'service_id' => Service::factory(),
            'branch_id' => Branch::factory(),
            'payment_type' => 'treatment_df',
            'base_amount' => fake()->randomFloat(2, 1000, 5000),
            'df_rate' => fake()->randomFloat(2, 10, 50),
            'df_amount' => fake()->randomFloat(2, 100, 500),
            'amount' => fake()->randomFloat(2, 100, 500),
            'status' => 'pending',
            'df_date' => now()->format('Y-m-d'),
            'source_type' => fake()->randomElement(['per_session', 'course_usage']),
            'payment_date' => now()->format('Y-m-d'),
            'notes' => fake()->optional()->sentence(),
            'created_by' => User::factory(),
        ];
    }
}
