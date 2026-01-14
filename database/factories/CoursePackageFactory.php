<?php

namespace Database\Factories;

use App\Models\CoursePackage;
use App\Models\Service;
use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;

class CoursePackageFactory extends Factory
{
    protected $model = CoursePackage::class;

    public function definition(): array
    {
        $paidSessions = fake()->randomElement([5, 10, 15, 20]);
        $bonusSessions = fake()->randomElement([0, 1, 2, 3]);

        return [
            'name' => fake()->words(3, true) . ' Package',
            'code' => strtoupper(fake()->unique()->lexify('PKG???')),
            'description' => fake()->sentence(),
            'price' => fake()->randomFloat(2, 5000, 50000),
            'paid_sessions' => $paidSessions,
            'bonus_sessions' => $bonusSessions,
            'total_sessions' => $paidSessions + $bonusSessions,
            'validity_days' => fake()->randomElement([90, 180, 365]),
            'is_active' => true,
            'service_id' => Service::factory(),
            'commission_rate' => fake()->randomFloat(2, 5, 15),
            'per_session_commission_rate' => fake()->randomFloat(2, 2, 8),
            'df_rate' => fake()->randomFloat(2, 5, 15),
            'df_amount' => fake()->randomFloat(2, 100, 500),
            'allow_buy_and_use' => true,
            'allow_buy_for_later' => true,
            'allow_retroactive' => false,
            'branch_id' => Branch::factory(),
        ];
    }
}
