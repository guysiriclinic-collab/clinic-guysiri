<?php

namespace Database\Factories;

use App\Models\CoursePurchase;
use App\Models\Patient;
use App\Models\CoursePackage;
use App\Models\Invoice;
use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;

class CoursePurchaseFactory extends Factory
{
    protected $model = CoursePurchase::class;

    public function definition(): array
    {
        $totalSessions = fake()->randomElement([10, 15, 20]);

        return [
            'course_number' => 'CRS' . str_pad(fake()->unique()->randomNumber(6), 6, '0', STR_PAD_LEFT),
            'patient_id' => Patient::factory(),
            'package_id' => CoursePackage::factory(),
            'invoice_id' => Invoice::factory(),
            'purchase_branch_id' => Branch::factory(),
            'purchase_pattern' => 'full',
            'purchase_date' => now(),
            'activation_date' => now(),
            'expiry_date' => now()->addDays(180),
            'total_sessions' => $totalSessions,
            'used_sessions' => 0,
            'status' => 'active',
            'allow_branch_sharing' => false,
            'payment_type' => 'full',
            'installment_total' => 0,
            'installment_paid' => 0,
            'seller_ids' => null,
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
            'expiry_date' => now()->addDays(180),
        ]);
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'expired',
            'expiry_date' => now()->subDays(30),
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'used_sessions' => $attributes['total_sessions'] ?? 10,
        ]);
    }

    public function installment(int $total = 3, int $paid = 1): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_type' => 'installment',
            'installment_total' => $total,
            'installment_paid' => $paid,
            'installment_amount' => fake()->randomFloat(2, 3000, 10000),
        ]);
    }

    public function withSellers(array $sellerIds): static
    {
        return $this->state(fn (array $attributes) => [
            'seller_ids' => $sellerIds,
        ]);
    }
}
