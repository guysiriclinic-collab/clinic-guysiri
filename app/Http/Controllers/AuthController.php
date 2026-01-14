<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Branch;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Queue;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Refund;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }

        return view('auth.login');
    }

    /**
     * Process login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // Update last login
            $user = Auth::user();
            $user->update(['last_login_at' => now()]);

            // CRITICAL: Branch Logic for Admin
            // If user is Admin (username: admin), redirect to branch selector
            if ($user->username === 'admin') {
                return redirect()->route('branch.selector');
            }

            // For non-admin users, set their assigned branch
            $defaultBranchId = $user->branch_id ?? Branch::first()->id;
            $request->session()->put('selected_branch_id', $defaultBranchId);

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->onlyInput('username');
    }

    /**
     * Show branch selector (Admin only)
     */
    public function showBranchSelector()
    {
        // Only allow admin to see branch selector
        if (!Auth::check() || Auth::user()->username !== 'admin') {
            return redirect('/dashboard');
        }

        $branches = Branch::where('is_active', true)->get();
        return view('branch-selector', compact('branches'));
    }

    /**
     * Switch branch (Admin/Area Manager only)
     * Used by branch switcher on Dashboard
     */
    public function switchBranch(Request $request)
    {
        $user = Auth::user();

        // Only Admin can switch branches
        if ($user->username !== 'admin') {
            return redirect('/dashboard')->with('error', 'ไม่มีสิทธิ์เข้าถึง');
        }

        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
        ]);

        // Store selected branch in session
        $request->session()->put('selected_branch_id', $validated['branch_id']);

        // Get branch name for display
        $branch = Branch::find($validated['branch_id']);

        // Redirect to dashboard with success message
        return redirect('/dashboard')->with('success', 'เข้าสู่สาขา ' . $branch->name . ' เรียบร้อยแล้ว');
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Show dashboard with real data
     */
    public function dashboard()
    {
        $user = auth()->user();
        $isPT = $user->role && $user->role->name === 'PT';

        if ($isPT) {
            // PT Dashboard: Show only personal data
            return $this->ptDashboard($user);
        }

        // Admin/Manager Dashboard: Show clinic-wide data
        // ยอดรายได้วันนี้ (Today's Revenue) - จาก Invoices ที่จ่ายแล้ว (รวม soft deleted) หัก Refunds
        $todayInvoices = Invoice::withTrashed()
            ->whereDate('invoice_date', today())
            ->where('status', 'paid')
            ->sum('paid_amount');
        $todayRefunds = Refund::whereDate('refund_date', today())->sum('refund_amount');
        $todayRevenue = $todayInvoices - $todayRefunds;

        // ยอดรายได้เมื่อวาน สำหรับคำนวณ % change
        $yesterdayInvoices = Invoice::withTrashed()
            ->whereDate('invoice_date', today()->subDay())
            ->where('status', 'paid')
            ->sum('paid_amount');
        $yesterdayRefunds = Refund::whereDate('refund_date', today()->subDay())->sum('refund_amount');
        $yesterdayRevenue = $yesterdayInvoices - $yesterdayRefunds;

        $revenueChange = $yesterdayRevenue > 0
            ? (($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100
            : 0;

        // ผู้ป่วยวันนี้ (Today's Patients) - นับจาก Appointments ที่ไม่ใช่ cancelled
        // นับจำนวนนัดหมาย (ไม่ใช่ distinct patient เพราะคนเดียวอาจมีหลายนัด)
        $todayPatients = Appointment::whereDate('appointment_date', today())
            ->where('status', '!=', 'cancelled')
            ->count();

        // ผู้ป่วยเมื่อวาน
        $yesterdayPatients = Appointment::whereDate('appointment_date', today()->subDay())
            ->where('status', '!=', 'cancelled')
            ->count();

        $patientsChange = $yesterdayPatients > 0
            ? (($todayPatients - $yesterdayPatients) / $yesterdayPatients) * 100
            : 0;

        // คิวรอวันนี้ (Waiting Queue Today) - นับจาก Appointments ที่ยังไม่เสร็จ
        $waitingQueue = Appointment::whereDate('appointment_date', today())
            ->whereIn('status', ['scheduled', 'confirmed', 'checked_in'])
            ->count();

        // คิวเมื่อวาน
        $yesterdayQueue = Appointment::whereDate('appointment_date', today()->subDay())
            ->whereIn('status', ['scheduled', 'confirmed', 'checked_in'])
            ->count();

        $queueChange = $yesterdayQueue > 0
            ? (($waitingQueue - $yesterdayQueue) / $yesterdayQueue) * 100
            : 0;

        // Patient Classification: ลูกค้าใหม่, ลูกค้าคอร์ส, ลูกค้าเก่า
        $todayAppointmentPatientIds = Appointment::whereDate('appointment_date', today())
            ->where('status', '!=', 'cancelled')
            ->pluck('patient_id')
            ->unique();

        // ลูกค้าใหม่ = ไม่เคยมีประวัติอะไรเลย (ไม่มี OPD, Treatment, Course)
        $todayNewPatients = Patient::whereIn('id', $todayAppointmentPatientIds)
            ->whereDoesntHave('opdRecords')
            ->whereDoesntHave('treatments')
            ->whereDoesntHave('coursePurchases')
            ->count();

        // ลูกค้าคอร์ส = มีคอร์สคงเหลือ (total_sessions > used_sessions)
        $todayCoursePatients = Patient::whereIn('id', $todayAppointmentPatientIds)
            ->whereHas('coursePurchases', function($q) {
                $q->whereColumn('used_sessions', '<', 'total_sessions')
                  ->where('status', 'active');
            })
            ->count();

        // ลูกค้าเก่า = เคยมีประวัติแล้ว แต่ไม่มีคอร์สคงเหลือ
        $todayOldPatients = $todayPatients - $todayNewPatients - $todayCoursePatients;

        // Yesterday calculations (same logic)
        $yesterdayAppointmentPatientIds = Appointment::whereDate('appointment_date', today()->subDay())
            ->where('status', '!=', 'cancelled')
            ->pluck('patient_id')
            ->unique();

        $yesterdayNewPatients = Patient::whereIn('id', $yesterdayAppointmentPatientIds)
            ->whereDoesntHave('opdRecords')
            ->whereDoesntHave('treatments')
            ->whereDoesntHave('coursePurchases')
            ->count();

        $yesterdayCoursePatients = Patient::whereIn('id', $yesterdayAppointmentPatientIds)
            ->whereHas('coursePurchases', function($q) {
                $q->whereColumn('used_sessions', '<', 'total_sessions')
                  ->where('status', 'active');
            })
            ->count();

        $yesterdayOldPatients = $yesterdayPatients - $yesterdayNewPatients - $yesterdayCoursePatients;

        // Calculate changes
        $newPatientsChange = $yesterdayNewPatients > 0
            ? (($todayNewPatients - $yesterdayNewPatients) / $yesterdayNewPatients) * 100
            : 0;

        $coursePatientsChange = $yesterdayCoursePatients > 0
            ? (($todayCoursePatients - $yesterdayCoursePatients) / $yesterdayCoursePatients) * 100
            : 0;

        $oldPatientsChange = $yesterdayOldPatients > 0
            ? (($todayOldPatients - $yesterdayOldPatients) / $yesterdayOldPatients) * 100
            : 0;

        // Queue Status Breakdown
        $queueWaiting = Queue::whereDate('created_at', today())
            ->where('status', 'waiting')
            ->count();

        $queueInProgress = Queue::whereDate('created_at', today())
            ->where('status', 'in_progress')
            ->count();

        $queueCompleted = Queue::whereDate('created_at', today())
            ->where('status', 'completed')
            ->count();

        return view('dashboard', compact(
            'todayRevenue',
            'revenueChange',
            'todayPatients',
            'patientsChange',
            'waitingQueue',
            'queueChange',
            'todayNewPatients',
            'newPatientsChange',
            'todayCoursePatients',
            'coursePatientsChange',
            'todayOldPatients',
            'oldPatientsChange',
            'queueWaiting',
            'queueInProgress',
            'queueCompleted'
        ));
    }

    /**
     * PT Dashboard: Show only personal work data
     */
    private function ptDashboard($user)
    {
        // นัดหมายของ PT วันนี้
        $todayAppointments = Appointment::whereDate('appointment_date', today())
            ->where('pt_id', $user->id)
            ->where('status', '!=', 'cancelled')
            ->count();

        // นัดหมายเมื่อวาน
        $yesterdayAppointments = Appointment::whereDate('appointment_date', today()->subDay())
            ->where('pt_id', $user->id)
            ->where('status', '!=', 'cancelled')
            ->count();

        $appointmentsChange = $yesterdayAppointments > 0
            ? (($todayAppointments - $yesterdayAppointments) / $yesterdayAppointments) * 100
            : 0;

        // คิวรอของ PT วันนี้
        $waitingQueue = Queue::whereDate('created_at', today())
            ->where('pt_id', $user->id)
            ->whereIn('status', ['waiting', 'in_progress'])
            ->count();

        // คิวเสร็จแล้ววันนี้
        $completedToday = Queue::whereDate('created_at', today())
            ->where('pt_id', $user->id)
            ->where('status', 'completed')
            ->count();

        // รายได้เดือนนี้ (สำหรับแสดงแบบง่าย - ถ้าต้องการรายละเอียดให้ไปดูที่หน้า "รายได้ของฉัน")
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        // ค่ามือ (DF)
        $monthlyDF = \App\Models\DfPayment::where('pt_id', $user->id)
            ->whereBetween('payment_date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        // ค่าคอม
        $monthlyCommission = \App\Models\CoursePurchase::whereJsonContains('seller_ids', $user->id)
            ->whereBetween('purchase_date', [$startOfMonth, $endOfMonth])
            ->where('status', '!=', 'cancelled')
            ->get()
            ->sum(function($c) use ($user) {
                $price = $c->package->price ?? 0;
                $commissionRate = $c->package->commission_rate ?? 0;
                $fullCommission = $price * $commissionRate / 100;
                $sellerIds = $c->seller_ids ?? [];
                $sellerCount = count($sellerIds);

                if ($sellerCount > 0) {
                    switch ($c->purchase_pattern) {
                        case 'buy_and_use':
                        case 'buy_for_later':
                            return $fullCommission / $sellerCount;
                        case 'retroactive':
                            $totalPTs = \App\Models\User::whereHas('role', fn($q) => $q->where('name', 'PT'))->count();
                            return $totalPTs > 0 ? $fullCommission / $totalPTs : 0;
                        default:
                            return $fullCommission / $sellerCount;
                    }
                }
                return 0;
            });

        return view('dashboard-pt', compact(
            'todayAppointments',
            'appointmentsChange',
            'waitingQueue',
            'completedToday',
            'monthlyDF',
            'monthlyCommission',
            'user'
        ));
    }
}
