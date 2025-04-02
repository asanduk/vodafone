<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ContractController extends Controller
{
    public function index(Request $request)
    {
        $query = Contract::query()
            ->where('user_id', auth()->id())
            ->with(['category', 'subcategory']);

        // Tarih aralığı filtresi
        switch ($request->date_range) {
            case 'today':
                $query->whereDate('contract_date', Carbon::today());
                break;
            case 'week':
                $query->whereBetween('contract_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('contract_date', Carbon::now()->month)
                      ->whereYear('contract_date', Carbon::now()->year);
                break;
            case 'year':
                $query->whereYear('contract_date', Carbon::now()->year);
                break;
        }

        // Arama
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('contract_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        // Sıralama - Düzeltilmiş versiyon
        switch ($request->sort) {
            case 'date_asc':
                $query->orderBy('contract_date', 'asc')
                      ->orderBy('created_at', 'asc');
                break;
            case 'date_desc':
                $query->orderBy('contract_date', 'desc')
                      ->orderBy('created_at', 'desc');
                break;
            case 'commission_asc':
                $query->orderBy('commission_amount', 'asc')
                      ->orderBy('contract_date', 'desc');
                break;
            case 'commission_desc':
                $query->orderBy('commission_amount', 'desc')
                      ->orderBy('contract_date', 'desc');
                break;
            case 'customer_asc':
                $query->orderBy('customer_name', 'asc')
                      ->orderBy('contract_date', 'desc');
                break;
            case 'customer_desc':
                $query->orderBy('customer_name', 'desc')
                      ->orderBy('contract_date', 'desc');
                break;
            default:
                $query->orderBy('contract_date', 'desc')
                      ->orderBy('created_at', 'desc');
        }

        // Debug için
        \Log::info('SQL Query:', [
            'sql' => $query->toSql(),
            'bindings' => $query->getBindings()
        ]);

        $contracts = $query->paginate(10)->withQueryString();

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
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:categories,id',
            'contract_date' => 'required|date',
            'contract_number' => 'nullable|unique:contracts,contract_number',
            'customer_name' => 'nullable|string|max:255',
            'commission_amount' => 'required|numeric|min:0'
        ]);

        $category = Category::findOrFail($request->category_id);
        $subcategory = Category::findOrFail($request->subcategory_id);

        // Komisyon hesaplama
        $commissionAmount = $request->commission_amount;
        $commissionRate = $category->commission_rate;

        $contract = new Contract();
        $contract->category_id = $request->category_id;
        $contract->subcategory_id = $request->subcategory_id;
        $contract->contract_date = $request->contract_date;
        $contract->contract_number = $request->contract_number;
        $contract->customer_name = $request->customer_name;
        $contract->commission_amount = $commissionAmount;
        $contract->commission_rate = $commissionRate;
        $contract->save();

        return redirect()->route('contracts.index')
            ->with('success', 'Vertrag erfolgreich erstellt.');
    }

    public function show(Contract $contract)
    {
        $this->authorize('view', $contract);
        return view('contracts.show', compact('contract'));
    }

    public function edit(Contract $contract)
    {
        $this->authorize('update', $contract);
        $mainCategories = Category::whereNull('parent_id')->get();
        $subcategories = Category::where('parent_id', $contract->category_id)->get();
        
        return view('contracts.edit', compact('contract', 'mainCategories', 'subcategories'));
    }

    public function update(Request $request, Contract $contract)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:categories,id',
            'contract_date' => 'required|date',
            'contract_number' => 'nullable|unique:contracts,contract_number,' . $contract->id,
            'customer_name' => 'nullable|string|max:255',
            'commission_amount' => 'required|numeric|min:0'
        ]);

        $category = Category::findOrFail($request->category_id);
        $subcategory = Category::findOrFail($request->subcategory_id);

        // Komisyon hesaplama
        $commissionAmount = $request->commission_amount;
        $commissionRate = $category->commission_rate;

        $contract->category_id = $request->category_id;
        $contract->subcategory_id = $request->subcategory_id;
        $contract->contract_date = $request->contract_date;
        $contract->contract_number = $request->contract_number;
        $contract->customer_name = $request->customer_name;
        $contract->commission_amount = $commissionAmount;
        $contract->commission_rate = $commissionRate;
        $contract->save();

        return redirect()->route('contracts.index')
            ->with('success', 'Vertrag erfolgreich aktualisiert.');
    }

    public function destroy(Contract $contract)
    {
        $this->authorize('delete', $contract);
        $contract->delete();

        return redirect()->route('contracts.index')
            ->with('success', 'Vertrag wurde erfolgreich gelöscht.');
    }

    public function dashboard()
    {
        try {
            // Ana kategorileri ve sözleşme sayılarını al
            $categories = Category::withCount(['contracts' => function($query) {
                $query->where('user_id', auth()->id());
            }])
            ->whereNull('parent_id')
            ->get();

            // Toplam komisyon
            $totalCommission = Contract::where('user_id', auth()->id())->sum('commission_amount') ?? 0;

            // Bugünkü komisyon
            $dailyCommission = Contract::where('user_id', auth()->id())
                ->whereDate('contract_date', Carbon::today())
                ->sum('commission_amount') ?? 0;

            // Bu haftaki komisyon
            $weeklyCommission = Contract::where('user_id', auth()->id())
                ->whereBetween('contract_date', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ])->sum('commission_amount') ?? 0;

            // Bu ayki komisyon
            $monthlyCommission = Contract::where('user_id', auth()->id())
                ->whereMonth('contract_date', Carbon::now()->month)
                ->whereYear('contract_date', Carbon::now()->year)
                ->sum('commission_amount') ?? 0;

            // Bu yıldaki komisyon
            $yearlyCommission = Contract::where('user_id', auth()->id())
                ->whereYear('contract_date', Carbon::now()->year)
                ->sum('commission_amount') ?? 0;

            // Son 12 ayın aylık verilerini al
            $monthlyStats = Contract::select(
                DB::raw('DATE_FORMAT(contract_date, "%Y-%m") as month'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(commission_amount) as commission')
            )
                ->where('user_id', auth()->id())
                ->where('contract_date', '>=', Carbon::now()->subMonths(12))
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
                'dailyCommission',
                'weeklyCommission',
                'monthlyCommission',
                'yearlyCommission',
                'monthlyData',
                'monthlyLabels'
            ));
        } catch (\Exception $e) {
            return view('dashboard')->with('error', 'Daten konnten nicht geladen werden.');
        }
    }

    // Alt kategorileri AJAX ile getirmek için
    public function getSubcategories(Category $category)
    {
        $subcategories = $category->subcategories()
            ->withCount('contracts')
            ->get()
            ->map(function ($subcategory) use ($category) {
                return [
                    'id' => $subcategory->id,
                    'name' => $subcategory->name,
                    'commission_rate' => $category->commission_rate,
                    'usage_count' => $subcategory->contracts_count
                ];
            });

        return response()->json($subcategories);
    }

    public function getCommission(Category $category)
    {
        return response()->json([
            'base_commission' => $category->base_commission,
            'commission_rate' => $category->commission_rate
        ]);
    }

    public function searchSubcategories(Request $request, Category $category)
    {
        $search = $request->input('search');
        
        $subcategories = $category->subcategories()
            ->where('name', 'like', "%{$search}%")
            ->withCount('contracts')
            ->orderBy('usage_count', 'desc')
            ->get()
            ->map(function ($subcategory) {
                return [
                    'id' => $subcategory->id,
                    'name' => $subcategory->name,
                    'base_commission' => $subcategory->base_commission,
                    'commission_rate' => $subcategory->commission_rate,
                    'usage_count' => $subcategory->contracts_count
                ];
            });

        return response()->json($subcategories);
    }
} 