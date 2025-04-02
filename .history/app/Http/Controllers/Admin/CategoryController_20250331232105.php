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

            Log::info('Validation passed', ['validated_data' => $validated]);

            $result = $category->update([
                'base_commission' => $validated['base_commission'],
                'commission_rate' => $validated['commission_rate']
            ]);

            Log::info('Update result', ['success' => $result]);

            if (!$result) {
                throw new \Exception('Datenbankaktualisierung fehlgeschlagen');
            }

            return response()->json([
                'success' => true,
                'message' => 'Provision wurde erfolgreich aktualisiert',
                'data' => [
                    'base_commission' => $category->base_commission,
                    'commission_rate' => $category->commission_rate
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Validierungsfehler',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Update failed', [
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