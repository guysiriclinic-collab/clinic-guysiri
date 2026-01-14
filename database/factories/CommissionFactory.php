<?php

namespace Database\Factories;

use App\Models\Commission;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Treatment;
use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommissionFactory extends Factory
{
    protected $model = Commission::class;

    public function definition(): array
    {
        $baseAmount = fake()->randomFloat(2, 1000, 10000);
        $rate = fake()->randomFloat(2, 5, 20);
        $commissionAmount = $baseAmount * ($rate / 100);

        return [
            'commission_number' => 'COM' . now()->format('Ymd') . str_pad(fake()->unique()->randomNumber(4), 4, '0', STR_PAD_LEFT),
            'pt_id' => User::factory(),
            'branch_id' => Branch::factory(),
            'commission_type' => fake()->randomElement(['service', 'course_sale', 'course_session']),
            'base_amount' => $baseAmount,
            'commission_rate' => $rate,
            'commission_amount' => $commissionAmount,
            'status' => 'pending',
            'commission_date' => now(),
            'is_clawback_eligible' => true,
        ];
    }

    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'paid',
            'paid_at' => now(),
        ]);
    }

    public function clawedBack(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'clawed_back',
            'clawed_back_at' => now(),
        ]);
    }
}
