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
        \Log::info('Update request received', [
            'category_id' => $category->id,
            'request_data' => $request->all()
        ]);

        try {
            // Check if this is a main category (no parent_id)
            if ($category->parent_id === null) {
                $validated = $request->validate([
                    'commission_rate' => 'required|numeric|between:0,100'
                ]);
                
                $category->commission_rate = $validated['commission_rate'];
            } else {
                $validated = $request->validate([
                    'base_commission' => 'required|numeric|min:0'
                ]);
                
                $category->base_commission = $validated['base_commission'];
            }

            \Log::info('Validation passed', ['validated_data' => $validated]);

            $saved = $category->save();

            \Log::info('Save result', ['saved' => $saved]);

            return response()->json([
                'success' => true,
                'message' => 'Erfolgreich aktualisiert',
                'data' => [
                    'id' => $category->id,
                    'base_commission' => $category->base_commission,
                    'commission_rate' => $category->commission_rate
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Update failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Fehler: ' . $e->getMessage()
            ], 500);
        }
    }
} 