<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function index()
    {
        $mainCategories = Category::whereNull('parent_id')
            ->with('subcategories')
            ->get();
            
        return view('admin.categories.index', compact('mainCategories'));
    }

    public function update(Request $request, Category $category)
    {
        try {
            Log::info('Update request received', [
                'category_id' => $category->id,
                'request_data' => $request->all()
            ]);

            $validated = $request->validate([
                'base_commission' => 'required|numeric|min:0',
                'commission_rate' => 'required|numeric|between:0,100'
            ]);

            $category->update([
                'base_commission' => $validated['base_commission'],
                'commission_rate' => $validated['commission_rate']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Provision wurde erfolgreich aktualisiert'
            ]);

        } catch (\Exception $e) {
            Log::error('Category update failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Fehler beim Aktualisieren: ' . $e->getMessage()
            ], 500);
        }
    }
} 