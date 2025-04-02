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
            ->with(['category', 'subcategory'])
            ->orderBy('contract_date', 'desc');

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

        // Sıralama
        switch ($request->sort) {
            case 'date_asc':
                $query->orderBy('contract_date', 'asc');
                break;
            case 'date_desc':
                $query->orderBy('contract_date', 'desc');
                break;
            case 'commission_asc':
                $query->orderBy('commission_amount', 'asc');
                break;
            case 'commission_desc':
                $query->orderBy('commission_amount', 'desc');
                break;
            default:
                $query->latest('contract_date');
        }

        $contracts = $query->paginate(10);

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
        try {
            // Ana kategorileri ve sözleşme sayılarını al
            $categories = Category::withCount('contracts')
                ->whereNull('parent_id')
                ->get();

            // Toplam komisyon
            $totalCommission = Contract::sum('commission_amount') ?? 0;

            // Bu ayki komisyon
            $monthlyCommission = Contract::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->sum('commission_amount') ?? 0;

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
        } catch (\Exception $e) {
            return view('dashboard')->with('error', 'Daten konnten nicht geladen werden.');
        }
    }

    // Alt kategorileri AJAX ile getirmek için
    public function getSubcategories(Category $category)
    {
        $subcategories = Category::where('parent_id', $category->id)
            ->select('id', 'name', 'base_commission', 'commission_rate')
            ->get();

        return response()->json($subcategories);
    }

    public function getCommission(Category $category)
    {
        return response()->json([
            'base_commission' => $category->base_commission,
            'commission_rate' => $category->commission_rate
        ]);
    }
} 