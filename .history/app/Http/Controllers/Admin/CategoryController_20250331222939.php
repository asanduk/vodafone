<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

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
        $validated = $request->validate([
            'base_commission' => 'required|numeric|min:0',
            'commission_rate' => 'required|numeric|between:0,100'
        ]);

        $category->update($validated);

        return back()->with('success', 'Provision wurde erfolgreich aktualisiert.');
    }
} 