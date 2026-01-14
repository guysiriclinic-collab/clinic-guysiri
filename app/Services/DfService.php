<?php

namespace App\Services;

use App\Models\DfPayment;
use App\Models\Treatment;
use App\Models\Service;
use App\Models\CoursePurchase;
use Illuminate\Support\Facades\Log;

class DfService
{
    /**
     * Calculate and record DF for a treatment (per session payment)
     *
     * @param string $treatmentId Treatment ID
     * @return DfPayment|null
     */
    public static function recordDfForTreatment(string $treatmentId): ?DfPayment
    {
        Log::info("DfService::recordDfForTreatment - START - Treatment ID: {$treatmentId}");

        // Use withoutGlobalScopes to bypass BranchScope during transaction
        $treatment = Treatment::withoutGlobalScopes()->with(['service', 'pt'])->find($treatmentId);

        if (!$treatment) {
            Log::error("DfService: Treatment not found: {$treatmentId}");
            return null;
        }

        Log::info("DfService: Treatment found - PT: {$treatment->pt_id}, Service: {$treatment->service_id}, df_amount: {$treatment->df_amount}");

        if (!$treatment->pt_id) {
            Log::warning("DfService: No PT assigned for treatment: {$treatmentId}");
            return null;
        }

        // Calculate DF amount - fallback chain
        $treatmentDf = $treatment->df_amount;
        $serviceDf = $treatment->service ? ($treatment->service->df_amount ?? $treatment->service->default_df_rate ?? 0) : 0;

        Log::info("DfService: treatment->df_amount: {$treatmentDf}, service df: {$serviceDf}");

        $dfAmount = $treatmentDf ?? $serviceDf;

        Log::info("DfService: Final DF Amount: {$dfAmount}");

        if ($dfAmount <= 0) {
            Log::info("DfService: DF amount is 0 for treatment: {$treatmentId}");
            return null;
        }

        // Check if already recorded
        $existing = DfPayment::where('treatment_id', $treatmentId)
            ->where('source_type', 'per_session')
            ->first();

        if ($existing) {
            Log::info("DfService: DF already recorded for treatment: {$treatmentId}");
            return $existing;
        }

        // Create DF payment record
        $baseAmount = $treatment->service->default_price ?? $dfAmount;
        $dfPayment = DfPayment::create([
            'treatment_id' => $treatmentId,
            'pt_id' => $treatment->pt_id,
            'service_id' => $treatment->service_id,
            'course_purchase_id' => null,
            'branch_id' => $treatment->branch_id,
            'base_amount' => $baseAmount,
            'df_rate' => $baseAmount > 0 ? ($dfAmount / $baseAmount * 100) : 100,
            'df_amount' => $dfAmount,
            'amount' => $dfAmount,
            'source_type' => 'per_session',
            'payment_date' => now()->toDateString(),
            'notes' => 'ค่ามือรายครั้ง: ' . ($treatment->service->name ?? 'N/A'),
        ]);

        Log::info("DfService: Recorded DF payment {$dfPayment->id} for treatment {$treatmentId}, amount: {$dfAmount}");

        return $dfPayment;
    }

    /**
     * Calculate and record DF for course usage
     *
     * @param string $treatmentId Treatment ID
     * @param string $coursePurchaseId Course Purchase ID
     * @param int $sessionsUsed จำนวนครั้งที่ใช้ (default 1)
     * @return DfPayment|null
     */
    public static function recordDfForCourseUsage(string $treatmentId, string $coursePurchaseId, int $sessionsUsed = 1): ?DfPayment
    {
        // Use withoutGlobalScopes to bypass BranchScope during transaction
        $treatment = Treatment::withoutGlobalScopes()->with(['service', 'pt'])->find($treatmentId);
        $coursePurchase = CoursePurchase::withoutGlobalScopes()->with('package')->find($coursePurchaseId);

        if (!$treatment) {
            Log::error("DfService: Treatment not found: {$treatmentId}");
            return null;
        }

        if (!$coursePurchase) {
            Log::error("DfService: CoursePurchase not found: {$coursePurchaseId}");
            return null;
        }

        if (!$treatment->pt_id) {
            Log::warning("DfService: No PT assigned for treatment: {$treatmentId}");
            return null;
        }

        // Calculate DF amount - use course package df_amount or fallback to service
        $dfAmount = $treatment->df_amount
            ?? $coursePurchase->package->df_amount
            ?? $treatment->service->df_amount
            ?? $treatment->service->default_df_rate
            ?? 0;

        if ($dfAmount <= 0) {
            Log::info("DfService: DF amount is 0 for course usage treatment: {$treatmentId}");
            return null;
        }

        // Check if already recorded
        $existing = DfPayment::where('treatment_id', $treatmentId)
            ->where('source_type', 'course_usage')
            ->first();

        if ($existing) {
            Log::info("DfService: DF already recorded for course usage treatment: {$treatmentId}");
            return $existing;
        }

        // Create DF payment record
        $notes = 'ค่ามือใช้คอร์ส: ' . ($coursePurchase->course_number ?? 'N/A') . ' - ' . ($treatment->service->name ?? 'N/A');
        if ($sessionsUsed > 1) {
            $notes .= ' (ใช้ ' . $sessionsUsed . ' ครั้ง)';
        }

        $baseAmount = $coursePurchase->package->price ?? $treatment->service->default_price ?? $dfAmount;
        $dfPayment = DfPayment::create([
            'treatment_id' => $treatmentId,
            'pt_id' => $treatment->pt_id,
            'service_id' => $treatment->service_id,
            'course_purchase_id' => $coursePurchaseId,
            'branch_id' => $treatment->branch_id,
            'base_amount' => $baseAmount,
            'df_rate' => $baseAmount > 0 ? ($dfAmount / $baseAmount * 100) : 100,
            'df_amount' => $dfAmount,
            'amount' => $dfAmount,
            'source_type' => 'course_usage',
            'payment_date' => now()->toDateString(),
            'notes' => $notes,
        ]);

        Log::info("DfService: Recorded DF payment {$dfPayment->id} for course usage treatment {$treatmentId}, amount: {$dfAmount}");

        return $dfPayment;
    }

    /**
     * Record DF for course sale - REMOVED
     *
     * NOTE: DF for course is NOT recorded at sale time.
     * DF is recorded when PT actually performs the treatment (course_usage).
     * This ensures the PT who does the work gets the DF, not the seller.
     *
     * Commission goes to seller (handled separately).
     * DF goes to PT who performs treatment.
     */

    /**
     * Get total DF for a PT in a date range
     */
    public static function getTotalDfForPt(string $ptId, $startDate, $endDate): float
    {
        return DfPayment::where('pt_id', $ptId)
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->sum('amount');
    }
}
