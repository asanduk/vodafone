<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ContractController extends Controller
{
    public function index()
    {
        $contracts = Contract::with(['category', 'subcategory'])->latest()->paginate(10);
        return view('contracts.index', compact('contracts'));
    }

    public function create()
    {
        // Ana kategorileri al (parent_id = null olanlar)
        $mainCategories = Category::whereNull('parent_id')->get();
        
        return view('contracts.create', compact('mainCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:categories,id',
            'contract_number' => 'required|unique:contracts',
            'customer_name' => 'required',
            'commission_amount' => 'required|numeric|min:0'
        ]);

        Contract::create($validated);

        return redirect()->route('contracts.index')
            ->with('success', 'Vertrag wurde erfolgreich erstellt.');
    }

    public function show(Contract $contract)
    {
        return view('contracts.show', compact('contract'));
    }

    public function edit(Contract $contract)
    {
        $mainCategories = Category::whereNull('parent_id')->get();
        $subcategories = Category::where('parent_id', $contract->category_id)->get();
        
        return view('contracts.edit', compact('contract', 'mainCategories', 'subcategories'));
    }

    public function update(Request $request, Contract $contract)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:categories,id',
            'contract_number' => 'required|unique:contracts,contract_number,' . $contract->id,
            'customer_name' => 'required',
            'commission_amount' => 'required|numeric|min:0'
        ]);

        $contract->update($validated);

        return redirect()->route('contracts.index')
            ->with('success', 'Vertrag wurde erfolgreich aktualisiert.');
    }

    public function destroy(Contract $contract)
    {
        $contract->delete();

        return redirect()->route('contracts.index')
            ->with('success', 'Vertrag wurde erfolgreich gelöscht.');
    }

    public function dashboard()
    {
        // Ana kategorileri ve sözleşme sayılarını al
        $categories = Category::withCount('contracts')->whereNull('parent_id')->get();

        // Toplam komisyon
        $totalCommission = Contract::sum('commission_amount');

        // Bu ayki komisyon
        $monthlyCommission = Contract::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('commission_amount');

        // Son 12 ayın aylık verilerini al
        $monthlyStats = Contract::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(commission_amount) as commission')
        )
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Grafik için veri hazırla
        $monthlyData = $monthlyStats->pluck('total')->toArray();
        $monthlyLabels = $monthlyStats->pluck('month')->map(function($month) {
            return Carbon::createFromFormat('Y-m', $month)->format('M Y');
        })->toArray();

        return view('dashboard', compact(
            'categories',
            'totalCommission',
            'monthlyCommission',
            'monthlyData',
            'monthlyLabels'
        ));
    }

    // Alt kategorileri AJAX ile getirmek için
    public function getSubcategories(Category $category)
    {
        try {
            $subcategories = Category::where('parent_id', $category->id)->get();
            return response()->json($subcategories);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getCommission(Category $category)
    {
        return response()->json([
            'base_commission' => $category->base_commission,
            'commission_rate' => $category->commission_rate
        ]);
    }
} 