<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\User;

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
        try {
            $validated = $request->validate([
                'category_id' => 'required|exists:categories,id',
                'subcategory_id' => 'required|exists:categories,id',
                'contract_number' => 'nullable|unique:contracts',
                'contract_date' => 'required|date',
                'customer_name' => 'nullable',
            ]);

            // Debug için
            \Log::info('Gelen tarih:', ['date' => $request->contract_date]);

            // Kullanıcı ID'sini ekle
            $validated['user_id'] = auth()->id();

            // Komisyonu hesapla
            $subcategory = Category::findOrFail($request->subcategory_id);
            $commission = ($subcategory->base_commission * $subcategory->commission_rate / 100);
            
            // Yeni sözleşme oluştur
            $contract = new Contract();
            $contract->user_id = auth()->id();
            $contract->category_id = $validated['category_id'];
            $contract->subcategory_id = $validated['subcategory_id'];
            $contract->contract_number = $validated['contract_number'];
            $contract->contract_date = $request->contract_date; // Direkt request'ten al
            $contract->customer_name = $validated['customer_name'];
            $contract->commission_amount = $commission;
            
            // Debug için
            \Log::info('Kaydedilecek veri:', $contract->toArray());

            $contract->save();

            return redirect()->route('contracts.index')
                ->with('success', 'Vertrag wurde erfolgreich erstellt.');
        } catch (\Exception $e) {
            \Log::error('Sözleşme oluşturma hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withInput()
                ->with('error', 'Fehler beim Erstellen des Vertrags: ' . $e->getMessage());
        }
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
        $this->authorize('update', $contract);
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:categories,id',
            'contract_number' => 'nullable|unique:contracts,contract_number,' . $contract->id,
            'customer_name' => 'nullable',
            'contract_date' => 'required|date',
            'commission_amount' => 'required|numeric|min:0'
        ]);

        // Recalculate commission based on new subcategory
        $subcategory = Category::findOrFail($request->subcategory_id);
        $validated['commission_amount'] = ($subcategory->base_commission * $subcategory->commission_rate / 100);

        $contract->update($validated);

        return redirect()->route('contracts.index')
            ->with('success', 'Vertrag wurde erfolgreich aktualisiert.');
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
            $userId = auth()->id();
            $now = Carbon::now();

            // Ana kategorileri ve sözleşme sayılarını al
            $categories = Category::whereNull('parent_id')
                ->withCount(['contracts' => function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                }])
                ->get();

            // Kullanıcının sözleşmelerinden komisyon hesaplamaları
            $contracts = Contract::where('user_id', $userId);
            
            $dailyCommission = (clone $contracts)->whereDate('contract_date', Carbon::today())->sum('commission_amount');
            $weeklyCommission = (clone $contracts)->whereBetween('contract_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('commission_amount');
            $monthlyCommission = (clone $contracts)->whereYear('contract_date', $now->year)->whereMonth('contract_date', $now->month)->sum('commission_amount');
            $yearlyCommission = (clone $contracts)->whereYear('contract_date', $now->year)->sum('commission_amount');
            $totalCommission = $contracts->sum('commission_amount');

            // Aylık istatistikler için veri
            $monthlyStats = Contract::where('user_id', $userId)
                ->whereYear('contract_date', $now->year)
                ->selectRaw('MONTH(contract_date) as month, COUNT(*) as count')
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            $monthlyData = array_fill(0, 12, 0);
            foreach ($monthlyStats as $stat) {
                $monthlyData[$stat->month - 1] = $stat->count;
            }

            $monthlyLabels = ['Jan', 'Feb', 'Mär', 'Apr', 'Mai', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dez'];

            // Top Performers - Monthly
            $topPerformersMonthly = User::withCount(['contracts as monthly_contracts_count' => function ($query) {
                $query->whereYear('contract_date', Carbon::now()->year)
                    ->whereMonth('contract_date', Carbon::now()->month);
            }])
            ->orderByDesc('monthly_contracts_count')
            ->take(5)
            ->get();

            // User Stats for Achievements
            $userStats = (object) [
                'monthly_contracts_count' => (clone $contracts)->whereYear('contract_date', $now->year)
                    ->whereMonth('contract_date', $now->month)
                    ->count(),
                'commission_amount' => $monthlyCommission,
                'consecutive_days' => $this->calculateConsecutiveDays($userId),
                'category_diversity' => Contract::where('user_id', $userId)
                    ->whereYear('contract_date', $now->year)
                    ->whereMonth('contract_date', $now->month)
                    ->distinct('category_id')
                    ->count('category_id')
            ];

            return view('dashboard', compact(
                'categories',
                'dailyCommission',
                'weeklyCommission',
                'monthlyCommission',
                'yearlyCommission',
                'totalCommission',
                'monthlyData',
                'monthlyLabels',
                'topPerformersMonthly',
                'userStats'
            ));

        } catch (\Exception $e) {
            \Log::error('Dashboard Error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return view('dashboard')->with('error', 'Fehler beim Laden der Dashboard-Daten.');
        }
    }

    private function calculateConsecutiveDays($userId)
    {
        $dates = Contract::where('user_id', $userId)
            ->orderBy('contract_date', 'desc')
            ->pluck('contract_date')
            ->map(function ($date) {
                return Carbon::parse($date)->format('Y-m-d');
            })
            ->unique()
            ->values()
            ->toArray();

        if (empty($dates)) {
            return 0;
        }

        $consecutiveDays = 1;
        $today = Carbon::today()->format('Y-m-d');
        
        if ($dates[0] !== $today) {
            return 0;
        }

        for ($i = 0; $i < count($dates) - 1; $i++) {
            $current = Carbon::parse($dates[$i]);
            $next = Carbon::parse($dates[$i + 1]);
            
            if ($current->subDay()->format('Y-m-d') === $next->format('Y-m-d')) {
                $consecutiveDays++;
            } else {
                break;
            }
        }

        return $consecutiveDays;
    }

    // Alt kategorileri AJAX ile getirmek için
    public function getSubcategories(Category $category)
    {
        $subcategories = Category::where('parent_id', $category->id)
            ->withCount('contracts')
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