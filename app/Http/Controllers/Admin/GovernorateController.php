<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Governorate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GovernorateController extends Controller
{
    /**
     * Display a listing of governorates
     * switched to direct fetching for reliability
     */
    public function index()
    {
        // Fetch all records, newest first
        $governorates = Governorate::orderBy('id', 'desc')->get();
        return view('admin.governorates.index', compact('governorates'));
    }

    /**
     * Show the form for creating a new governorate
     */
    public function create()
    {
        return view('admin.governorates.create');
    }

    /**
     * Store a newly created governorate
     */
    public function store(Request $request)
    {
        // 1. Validate
        $validated = $request->validate([
            'name_en' => 'required|string|max:255|unique:governorates,name_en',
            'name_ar' => 'required|string|max:255|unique:governorates,name_ar',
            'shipping_fee' => 'required|numeric|min:0',
            'delivery_days' => 'required|integer|min:1',
        ]);

        try {
            // 2. Prepare Data (Checkbox handling)
            $validated['active'] = $request->has('active') ? 1 : 0;

            // 3. Create
            Governorate::create($validated);

            return redirect()->route('admin.governorates.index')
                ->with('success', 'Governorate added successfully!');

        } catch (\Exception $e) {
            Log::error('Governorate Create Error: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Database Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing a governorate
     */
    public function edit($id)
    {
        $governorate = Governorate::findOrFail($id);
        return view('admin.governorates.edit', compact('governorate'));
    }

    /**
     * Update the specified governorate
     */
    public function update(Request $request, $id)
    {
        $governorate = Governorate::findOrFail($id);

        $validated = $request->validate([
            'name_en' => 'required|string|max:255|unique:governorates,name_en,' . $id,
            'name_ar' => 'required|string|max:255|unique:governorates,name_ar,' . $id,
            'shipping_fee' => 'required|numeric|min:0',
            'delivery_days' => 'required|integer|min:1',
        ]);

        try {
            $validated['active'] = $request->has('active') ? 1 : 0;
            
            $governorate->update($validated);

            return redirect()->route('admin.governorates.index')
                ->with('success', 'Governorate updated successfully!');

        } catch (\Exception $e) {
            Log::error('Governorate Update Error: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Update Failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Toggle governorate status via AJAX
     */
    public function toggleStatus($id)
    {
        try {
            $governorate = Governorate::findOrFail($id);
            $governorate->active = !$governorate->active;
            $governorate->save();

            return response()->json(['success' => true, 'message' => 'Status updated.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error updating status.']);
        }
    }

    /**
     * Remove the specified governorate
     */
    public function destroy($id)
    {
        try {
            $governorate = Governorate::findOrFail($id);
            $governorate->delete();

            return response()->json(['success' => true, 'message' => 'Deleted successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error deleting record.']);
        }
    }
}