<?php

namespace App\Services;

use App\Models\RevenueAdjustment;
use App\Models\Invoice;
use App\Models\Refund;
use Illuminate\Support\Facades\Auth;

class RevenueAdjustmentService
{
    /**
     * Create revenue adjustment for course cancellation refund
     *
     * CRITICAL: This backdates the adjustment to the original invoice date
     * to ensure P&L reports show accurate net revenue for that period.
     *
     * @param Invoice $invoice Original invoice
     * @param Refund $refund Refund record
     * @param float $refundAmount Amount being refunded
     * @return RevenueAdjustment
     */
    public static function createRefundAdjustment(Invoice $invoice, Refund $refund, float $refundAmount): RevenueAdjustment
    {
        return RevenueAdjustment::create([
            'invoice_id' => $invoice->id,
            'refund_id' => $refund->id,
            'branch_id' => $invoice->branch_id,
            'adjustment_type' => 'refund',
            'adjustment_amount' => -abs($refundAmount), // Negative for revenue reduction
            'effective_date' => $invoice->invoice_date, // CRITICAL: Backdate to original purchase
            'adjustment_date' => today(), // Actual date of adjustment
            'description' => "Revenue adjustment for refund {$refund->refund_number}",
            'notes' => "Original invoice: {$invoice->invoice_number}. Refund amount: " . number_format($refundAmount, 2),
            'created_by' => Auth::id() ?? null,
        ]);
    }

    /**
     * Get net revenue for a date range (considering adjustments)
     *
     * This should be used in P&L reports to show accurate revenue
     *
     * @param string $startDate
     * @param string $endDate
     * @param string|null $branchId
     * @return float
     */
    public static function getNetRevenue(string $startDate, string $endDate, ?string $branchId = null): float
    {
        // Get gross revenue from invoices
        $grossRevenue = Invoice::whereBetween('invoice_date', [$startDate, $endDate])
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->sum('total_amount');

        // Get adjustments (negative values reduce revenue)
        $adjustments = RevenueAdjustment::whereBetween('effective_date', [$startDate, $endDate])
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->sum('adjustment_amount');

        return $grossRevenue + $adjustments; // adjustments are negative, so this subtracts
    }

    /**
     * Get revenue breakdown for P&L report
     *
     * @param string $startDate
     * @param string $endDate
     * @param string|null $branchId
     * @return array
     */
    public static function getRevenueBreakdown(string $startDate, string $endDate, ?string $branchId = null): array
    {
        $grossRevenue = Invoice::whereBetween('invoice_date', [$startDate, $endDate])
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->sum('total_amount');

        $refundAdjustments = RevenueAdjustment::whereBetween('effective_date', [$startDate, $endDate])
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->where('adjustment_type', 'refund')
            ->sum('adjustment_amount');

        $otherAdjustments = RevenueAdjustment::whereBetween('effective_date', [$startDate, $endDate])
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->where('adjustment_type', '!=', 'refund')
            ->sum('adjustment_amount');

        $netRevenue = $grossRevenue + $refundAdjustments + $otherAdjustments;

        return [
            'gross_revenue' => $grossRevenue,
            'refund_adjustments' => $refundAdjustments,
            'other_adjustments' => $otherAdjustments,
            'total_adjustments' => $refundAdjustments + $otherAdjustments,
            'net_revenue' => $netRevenue,
        ];
    }
}
