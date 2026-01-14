<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::with('serviceCategory')->orderBy('name')->paginate(15);
        $categories = ServiceCategory::active()->ordered()->get();
        return view('services.index', compact('services', 'categories'));
    }

    public function create()
    {
        return view('services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:service_categories,id',
            'default_price' => 'required|numeric|min:0',
            'default_duration_minutes' => 'nullable|integer|min:1',
            'is_active' => 'nullable',
            'is_package' => 'nullable',
            'package_sessions' => 'nullable|integer|min:1',
            'package_validity_days' => 'nullable|integer|min:1',
            'default_commission_rate' => 'nullable|numeric|min:0|max:100',
            'default_df_rate' => 'nullable|numeric|min:0',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['is_package'] = $request->has('is_package');
        $validated['created_by'] = Auth::id();

        // Convert empty category_id to null
        if (empty($validated['category_id'])) {
            $validated['category_id'] = null;
            $validated['category'] = '';
        } else {
            // Get category name from service_categories table
            $category = ServiceCategory::find($validated['category_id']);
            $validated['category'] = $category ? $category->name : '';
        }

        Service::create($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'เพิ่มบริการเรียบร้อยแล้ว']);
        }

        return redirect()->route('services.index')->with('success', 'เพิ่มบริการเรียบร้อยแล้ว');
    }

    public function show($id)
    {
        $service = Service::findOrFail($id);
        return response()->json($service);
    }

    public function edit($id)
    {
        $service = Service::findOrFail($id);

        if (request()->ajax()) {
            return response()->json($service);
        }

        return view('services.edit', compact('service'));
    }

    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:service_categories,id',
            'default_price' => 'required|numeric|min:0',
            'default_duration_minutes' => 'nullable|integer|min:1',
            'is_active' => 'nullable',
            'is_package' => 'nullable',
            'package_sessions' => 'nullable|integer|min:1',
            'package_validity_days' => 'nullable|integer|min:1',
            'default_commission_rate' => 'nullable|numeric|min:0|max:100',
            'default_df_rate' => 'nullable|numeric|min:0',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['is_package'] = $request->has('is_package');

        // Convert empty category_id to null
        if (empty($validated['category_id'])) {
            $validated['category_id'] = null;
            $validated['category'] = '';
        } else {
            // Get category name from service_categories table
            $category = ServiceCategory::find($validated['category_id']);
            $validated['category'] = $category ? $category->name : '';
        }

        $service->update($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'แก้ไขบริการเรียบร้อยแล้ว']);
        }

        return redirect()->route('services.index')->with('success', 'แก้ไขบริการเรียบร้อยแล้ว');
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'ลบบริการเรียบร้อยแล้ว']);
        }

        return redirect()->route('services.index')->with('success', 'ลบบริการเรียบร้อยแล้ว');
    }
}
