<?php

namespace Database\Factories;

use App\Models\CourseUsageLog;
use App\Models\CoursePurchase;
use App\Models\Treatment;
use App\Models\Patient;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseUsageLogFactory extends Factory
{
    protected $model = CourseUsageLog::class;

    public function definition(): array
    {
        return [
            'course_purchase_id' => CoursePurchase::factory(),
            'treatment_id' => Treatment::factory(),
            'patient_id' => Patient::factory(),
            'branch_id' => Branch::factory(),
            'pt_id' => User::factory(),
            'sessions_used' => 1,
            'usage_date' => now()->format('Y-m-d'),
            'status' => 'completed',
            'is_cross_branch' => false,
        ];
    }

    public function crossBranch(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_cross_branch' => true,
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => fake()->sentence(),
        ]);
    }
}
