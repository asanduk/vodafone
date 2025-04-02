<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'commission_rate' => 'nullable|numeric|min:0|max:100'
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->parent_id = $request->parent_id;
        
        // Sadece ana kategorilerde commission_rate kaydedilir
        if (!$request->parent_id) {
            $category->commission_rate = $request->commission_rate;
        }
        
        $category->save();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategorie erfolgreich erstellt.');
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'commission_rate' => 'nullable|numeric|min:0|max:100'
        ]);

        $category->name = $request->name;
        $category->parent_id = $request->parent_id;
        
        // Sadece ana kategorilerde commission_rate güncellenir
        if (!$request->parent_id) {
            $category->commission_rate = $request->commission_rate;
        } else {
            $category->commission_rate = null;
        }
        
        $category->save();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategorie erfolgreich aktualisiert.');
    }
} 