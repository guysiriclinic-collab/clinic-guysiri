<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 1000, 50000);
        $discount = fake()->randomFloat(2, 0, $subtotal * 0.2);
        $tax = ($subtotal - $discount) * 0.07;
        $total = $subtotal - $discount + $tax;

        return [
            'invoice_number' => 'INV' . now()->format('Ymd') . str_pad(fake()->unique()->randomNumber(4), 4, '0', STR_PAD_LEFT),
            'patient_id' => Patient::factory(),
            'branch_id' => Branch::factory(),
            'invoice_type' => 'service',
            'subtotal' => $subtotal,
            'discount_amount' => $discount,
            'tax_amount' => $tax,
            'total_amount' => $total,
            'paid_amount' => 0,
            'outstanding_amount' => $total,
            'status' => 'pending',
            'invoice_date' => now(),
            'due_date' => now()->addDays(30),
        ];
    }

    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'paid',
            'paid_amount' => $attributes['total_amount'] ?? 10000,
            'outstanding_amount' => 0,
        ]);
    }

    public function partiallyPaid(float $paidPercent = 0.5): static
    {
        return $this->state(function (array $attributes) use ($paidPercent) {
            $total = $attributes['total_amount'] ?? 10000;
            $paid = $total * $paidPercent;
            return [
                'status' => 'partially_paid',
                'paid_amount' => $paid,
                'outstanding_amount' => $total - $paid,
            ];
        });
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
        ]);
    }
}
