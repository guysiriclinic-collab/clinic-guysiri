<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true) . ' Service',
            'code' => strtoupper(fake()->unique()->lexify('SVC???')),
            'description' => fake()->sentence(),
            'category' => fake()->randomElement(['treatment', 'therapy', 'consultation']),
            'default_price' => fake()->randomFloat(2, 500, 5000),
            'default_duration_minutes' => fake()->randomElement([30, 45, 60, 90]),
            'is_active' => true,
            'is_package' => false,
            'default_commission_rate' => fake()->randomFloat(2, 5, 20),
            'default_df_rate' => fake()->randomFloat(2, 100, 500),
            'branch_id' => Branch::factory(),
        ];
    }
}
