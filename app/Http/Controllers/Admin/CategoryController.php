<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryLevel;
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

    public function create()
    {
        $mainCategories = Category::whereNull('parent_id')->get();
        return view('admin.categories.create', compact('mainCategories'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'parent_id' => 'nullable|exists:categories,id',
                'commission_rate' => 'nullable|numeric|between:0,100',
                'base_commission' => 'nullable|numeric|min:0'
            ]);

            $category = Category::create($validated);

            return response()->json([
                'success' => true,
                'message' => $category->parent_id ? 
                    "Unterbereich '{$category->name}' wurde erfolgreich hinzugefügt" : 
                    "Hauptbereich '{$category->name}' wurde erfolgreich hinzugefügt",
                'data' => $category
            ]);

        } catch (\Exception $e) {
            \Log::error('Category creation failed', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Fehler: ' . $e->getMessage()
            ], 500);
        }
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
                    'commission_rate' => 'required|numeric|between:0,100',
                    'level' => 'nullable|integer|min:1',
                    'level_bonus_percent' => 'nullable|numeric|min:0|max:100',
                    'level_activated_at' => 'nullable|date',
                ]);
                
                $category->commission_rate = $validated['commission_rate'];

                // Append a new history row if any level-related fields are provided
                $newLevel = $request->has('level') ? (int) $validated['level'] : (int) ($category->level ?? 1);
                $newBonus = $request->has('level_bonus_percent') ? (float) $validated['level_bonus_percent'] : (float) ($category->level_bonus_percent ?? 0);
                $newActivatedAt = $request->has('level_activated_at') ? $validated['level_activated_at'] : now();

                if ($request->has('level') || $request->has('level_bonus_percent') || $request->has('level_activated_at')) {
                    CategoryLevel::create([
                        'category_id' => $category->id,
                        'level' => max(1, $newLevel),
                        'bonus_percent' => max(0, $newBonus),
                        'activated_at' => $newActivatedAt,
                    ]);

                    // Update snapshot fields for current display
                    $category->level = max(1, $newLevel);
                    $category->level_bonus_percent = max(0, $newBonus);
                    $category->level_activated_at = $newActivatedAt;
                }
                $message = "Provisionssatz/Level für {$category->name} wurde aktualisiert";
            } else {
                $validated = $request->validate([
                    'base_commission' => 'required|numeric|min:0'
                ]);
                
                $category->base_commission = $validated['base_commission'];
                $message = "Grundprovision für {$category->name} wurde aktualisiert";
            }

            \Log::info('Validation passed', ['validated_data' => $validated]);

            $saved = $category->save();

            \Log::info('Save result', ['saved' => $saved]);

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'id' => $category->id,
                    'base_commission' => $category->base_commission,
                        'commission_rate' => $category->commission_rate,
                        'level' => $category->level,
                        'level_bonus_percent' => $category->level_bonus_percent,
                        'level_activated_at' => optional($category->level_activated_at)->toDateTimeString(),
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

    public function destroy(Category $category)
    {
        try {
            $categoryName = $category->name;
            $isMainCategory = $category->parent_id === null;
            
            // Alt kategorileri de soft delete yap (cascade soft delete)
            if ($isMainCategory) {
                $category->subcategories()->delete();
            }
            
            // Soft delete kullan
            $category->delete();

            return response()->json([
                'success' => true,
                'message' => $isMainCategory ? 
                    "Hauptbereich '{$categoryName}' und alle Unterbereiche wurden erfolgreich gelöscht (Verträge bleiben erhalten)" : 
                    "Unterbereich '{$categoryName}' wurde erfolgreich gelöscht (Verträge bleiben erhalten)"
            ]);

        } catch (\Exception $e) {
            \Log::error('Category deletion failed', [
                'error' => $e->getMessage(),
                'category_id' => $category->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Fehler beim Löschen: ' . $e->getMessage()
            ], 500);
        }
    }
} 