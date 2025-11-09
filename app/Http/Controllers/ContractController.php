<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\AppSetting;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ContractController extends Controller
{
    public function index(Request $request)
    {
        // Normal kullanıcılar sadece kendi contractlarını görebilir
        // Admin kullanıcılar da sadece kendi contractlarını görebilir (admin paneli ayrı)
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

        $contracts = $query->paginate(10)->withQueryString();

        return view('contracts.index', compact('contracts'));
    }

    public function create()
    {
        // Ana kategorileri al (parent_id = null olanlar) - sadece aktif olanlar
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
            $subcategory = Category::withTrashed()->with('parent')->findOrFail($request->subcategory_id);
            // Alt kategorinin base_commission'ını üst kategorinin commission_rate'i ile çarp
            $commission = ($subcategory->base_commission * $subcategory->parent->commission_rate / 100);
            
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
                ->with('success', 'Vertrag wurde erfolgreich erstellt.')
                ->with('show_confetti', true);
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
        
        // Ana kategoriler - sadece aktif olanlar (yeni seçim için)
        $mainCategories = Category::whereNull('parent_id')->get();
        
        // Alt kategoriler - mevcut contract'ın kategorisi için (silinmiş olsa bile)
        $subcategories = Category::withTrashed()->where('parent_id', $contract->category_id)->get();
        
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
            $subcategory = Category::withTrashed()->with('parent')->findOrFail($request->subcategory_id);
        $validated['commission_amount'] = ($subcategory->base_commission * $subcategory->parent->commission_rate / 100);

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
            // Ana kategorileri ve sözleşme sayılarını al - sadece aktif olanlar veya contract'ı olan silinenler
            $categories = Category::withTrashed()->withCount(['contracts' => function($query) {
                $query->where('user_id', auth()->id());
            }])
            ->whereNull('parent_id')
            ->get()
            ->filter(function($category) {
                // Aktif kategorileri veya contract'ı olan silinen kategorileri göster
                return $category->deleted_at === null || $category->contracts_count > 0;
            });

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

            // Overall statistics for all users (including admin)
            $overallStats = [
                'total_contracts' => Contract::count(),
                'total_commission' => Contract::sum('commission_amount') ?? 0,
                'daily_commission' => Contract::whereDate('contract_date', Carbon::today())->sum('commission_amount') ?? 0,
                'weekly_commission' => Contract::whereBetween('contract_date', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ])->sum('commission_amount') ?? 0,
                'monthly_commission' => Contract::whereMonth('contract_date', Carbon::now()->month)
                    ->whereYear('contract_date', Carbon::now()->year)
                    ->sum('commission_amount') ?? 0,
                'yearly_commission' => Contract::whereYear('contract_date', Carbon::now()->year)
                    ->sum('commission_amount') ?? 0,
            ];

            // Overall monthly stats for all users
            $overallMonthlyStats = Contract::select(
                DB::raw('DATE_FORMAT(contract_date, "%Y-%m") as month'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(commission_amount) as commission')
            )
                ->where('contract_date', '>=', Carbon::now()->subMonths(12))
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            $overallMonthlyData = $overallMonthlyStats->pluck('total')->toArray();
            $overallMonthlyLabels = $overallMonthlyStats->pluck('month')->map(function($month) {
                return Carbon::createFromFormat('Y-m', $month)->format('M Y');
            })->toArray();

            // Ranking settings and data (guard table missing)
            try {
                $settings = AppSetting::first();
            } catch (\Throwable $e) {
                $settings = null;
            }
            $ranking = collect();
            if ($settings && $settings->show_ranking) {
                $rankingQuery = User::query()
                    ->select('id', 'name')
                    ->where('is_active', true)
                    ->withCount(['contracts as total_contracts'])
                    ->withSum('contracts as total_commission', 'commission_amount')
                    ->withSum(['contracts as monthly_commission' => function ($q) {
                        $q->whereMonth('contract_date', now()->month)
                          ->whereYear('contract_date', now()->year);
                    }], 'commission_amount')
                    ->orderByDesc('total_commission');

                if (!$settings->include_admins_in_ranking) {
                    $rankingQuery->where('is_admin', false);
                }

                $ranking = $rankingQuery->limit(10)->get();
            }

            // Admin earnings per contract (latest) when enabled
            $adminEarningsContracts = collect();
            if (auth()->user()->is_admin && $settings && ($settings->show_admin_earnings ?? true)) {
                $levelsEnabled = (bool) ($settings->enable_category_levels ?? false);
                $adminEarningsContracts = Contract::with(['category.parent', 'subcategory.parent'])
                    ->orderByDesc('contract_date')
                    ->limit(10)
                    ->get()
                    ->map(function ($c) use ($levelsEnabled) {
                        $base = 0.0;
                        if ($c->subcategory) {
                            $base = (float) $c->subcategory->base_commission;
                        } elseif ($c->category) {
                            $base = (float) $c->category->base_commission;
                        }

                        // Determine main category via subcategory's parent when present
                        $mainCategory = $c->subcategory && $c->subcategory->parent ? $c->subcategory->parent : ($c->category && $c->category->parent ? $c->category->parent : $c->category);
                        $bonus = 0.0;
                        if ($levelsEnabled && $mainCategory && ($mainCategory->level ?? 1) > 1) {
                            // Determine effective level by contract date using history if exists
                            $effective = $mainCategory->levels()
                                ->where('activated_at', '<=', Carbon::parse($c->contract_date)->endOfDay())
                                ->orderByDesc('activated_at')
                                ->first();
                            if ($effective && ($effective->level ?? 1) > 1) {
                                $bonus = $base * (max(0.0, (float) ($effective->bonus_percent ?? 0)) / 100.0);
                                $c->owner_level = (int) $effective->level;
                            } else {
                                $activatedAt = $mainCategory->level_activated_at ? Carbon::parse($mainCategory->level_activated_at) : null;
                                if (!$activatedAt || Carbon::parse($c->contract_date)->greaterThanOrEqualTo($activatedAt->startOfDay())) {
                                    $bonus = $base * (max(0.0, (float) ($mainCategory->level_bonus_percent ?? 0)) / 100.0);
                                }
                                $c->owner_level = (int) ($mainCategory->level ?? 1);
                            }
                        }

                        // expose computed owner amount and bonus alongside original
                        $c->owner_amount = $base + $bonus;
                        $c->owner_bonus = $bonus;
                        $c->owner_level = (int) ($c->owner_level ?? ($mainCategory->level ?? 1));
                        return $c;
                    });
            }

            // Admin monthly earnings grouped by category using base commission (Grundprovision) + optional level bonus in EUR
            $adminCategoryEarnings = collect();
            if (auth()->user()->is_admin && $settings && ($settings->show_admin_category_earnings ?? true)) {
                $monthsWindow = max(1, (int) ($settings->admin_earnings_months_window ?? 12));
                $startDate = Carbon::now()->startOfMonth()->subMonths($monthsWindow - 1);

                $contracts = Contract::with(['category.parent', 'subcategory.parent'])
                    ->whereDate('contract_date', '>=', $startDate)
                    ->orderBy('contract_date', 'desc')
                    ->get();

                $adminCategoryEarnings = $contracts->groupBy(function ($c) {
                    return Carbon::parse($c->contract_date)->format('Y-m');
                })->map(function ($monthContracts) use ($settings) {
                    $showSubs = (bool) ($settings->admin_earnings_show_subcategories ?? true);
                    $levelsEnabled = (bool) ($settings->enable_category_levels ?? false);

                    $categories = [];
                    $monthContractsCount = 0;
                    $monthBaseTotal = 0.0;
                    $monthBonusTotal = 0.0;
                    foreach ($monthContracts as $c) {
                        $monthContractsCount++;
                        // Determine main category via subcategory's parent when present
                        $mainCategory = $c->subcategory && $c->subcategory->parent ? $c->subcategory->parent : ($c->category && $c->category->parent ? $c->category->parent : $c->category);
                        $mainName = $mainCategory ? $mainCategory->name : 'Unbekannt';

                        $base = 0.0;
                        if ($c->subcategory) {
                            $base = (float) $c->subcategory->base_commission; // EUR
                        } elseif ($c->category) {
                            $base = (float) $c->category->base_commission; // EUR
                        }

                        // Compute level bonus for owner if enabled and active for this contract date
                        $bonus = 0.0;
                        if ($levelsEnabled && $mainCategory) {
                            // Determine effective level by contract date using history if exists
                            $effective = $mainCategory->levels()
                                ->where('activated_at', '<=', Carbon::parse($c->contract_date)->endOfDay())
                                ->orderByDesc('activated_at')
                                ->first();
                            if ($effective && ($effective->level ?? 1) > 1) {
                                $bonus = $base * (max(0.0, (float) ($effective->bonus_percent ?? 0)) / 100.0);
                            } else if (($mainCategory->level ?? 1) > 1) {
                                $activatedAt = $mainCategory->level_activated_at ? Carbon::parse($mainCategory->level_activated_at) : null;
                                if (!$activatedAt || Carbon::parse($c->contract_date)->greaterThanOrEqualTo($activatedAt->startOfDay())) {
                                    $bonus = $base * (max(0.0, (float) ($mainCategory->level_bonus_percent ?? 0)) / 100.0);
                                }
                            }
                        }

                        if (!isset($categories[$mainName])) {
                            $categories[$mainName] = [
                                'total' => 0.0,
                                'count' => 0,
                                'bonus_total' => 0.0,
                                'level' => (int) ($mainCategory->level ?? 1),
                                'level_count' => 0,
                                'subcategories' => [],
                            ];
                        }

                        $categories[$mainName]['total'] += ($base + $bonus);
                        $categories[$mainName]['count'] += 1;
                        $monthBaseTotal += $base;
                        $categories[$mainName]['bonus_total'] += $bonus;
                        $monthBonusTotal += $bonus;
                        if ($bonus > 0) {
                            $categories[$mainName]['level_count'] += 1;
                        }

                        if ($showSubs) {
                            $subName = $c->subcategory ? $c->subcategory->name : ($c->category ? $c->category->name : '—');
                            if (!isset($categories[$mainName]['subcategories'][$subName])) {
                                $categories[$mainName]['subcategories'][$subName] = [
                                    'total' => 0.0,
                                    'count' => 0,
                                    'bonus_total' => 0.0,
                                    'level_count' => 0,
                                ];
                            }
                            $categories[$mainName]['subcategories'][$subName]['total'] += ($base + $bonus);
                            $categories[$mainName]['subcategories'][$subName]['count'] += 1;
                            $categories[$mainName]['subcategories'][$subName]['bonus_total'] += $bonus;
                            if ($bonus > 0) {
                                $categories[$mainName]['subcategories'][$subName]['level_count'] += 1;
                            }
                        }
                    }

                    ksort($categories);
                    if ($showSubs) {
                        foreach ($categories as &$cat) {
                            ksort($cat['subcategories']);
                        }
                    }

                    return [
                        'total' => (float) ($monthBaseTotal + $monthBonusTotal),
                        'bonus_total' => (float) $monthBonusTotal,
                        'count' => (int) $monthContractsCount,
                        'categories' => $categories,
                    ];
                })->sortKeysDesc();
            }

            // Compute overall Grundprovision total across all contracts in EUR
            $overallBaseTotal = Contract::with(['subcategory', 'category'])->get()->sum(function ($c) {
                if ($c->subcategory) {
                    return (float) $c->subcategory->base_commission;
                }
                if ($c->category) {
                    return (float) $c->category->base_commission;
                }
                return 0.0;
            });

            // Get announcements and check which are unseen
            $announcements = [];
            $unseenAnnouncements = [];
            try {
                if (Storage::exists('announcements.json')) {
                    $announcements = json_decode(Storage::get('announcements.json'), true) ?? [];
                    $seenIds = auth()->user()->seen_announcements ?? [];
                    $unseenAnnouncements = array_filter($announcements, fn($a) => !in_array($a['id'], $seenIds));
                }
            } catch (\Throwable $e) {
                // Ignore
            }

            $dashboardLayout = auth()->user()->dashboard_layout ?? [];
            $dashboardCollapsed = auth()->user()->dashboard_collapsed ?? [];

            return view('dashboard', compact(
                'categories',
                'totalCommission',
                'dailyCommission',
                'weeklyCommission',
                'monthlyCommission',
                'yearlyCommission',
                'monthlyData',
                'monthlyLabels',
                'overallStats',
                'overallMonthlyData',
                'overallMonthlyLabels',
                'settings',
                'ranking',
                'adminEarningsContracts',
                'adminCategoryEarnings',
                'announcements',
                'unseenAnnouncements',
                'dashboardLayout',
                'dashboardCollapsed',
                'overallBaseTotal'
            ));
        } catch (\Exception $e) {
            return view('dashboard')->with('error', 'Daten konnten nicht geladen werden.');
        }
    }

    // Alt kategorileri AJAX ile getirmek için
    public function getSubcategories(Category $category)
    {
        $subcategories = Category::where('parent_id', $category->id)
            ->withCount('contracts')
            ->get()
            ->map(function ($subcategory) use ($category) {
                return [
                    'id' => $subcategory->id,
                    'name' => $subcategory->name,
                    'base_commission' => $subcategory->base_commission,
                    'commission_rate' => $category->commission_rate, // Üst kategorinin commission_rate'ini döndür
                    'usage_count' => $subcategory->contracts_count
                ];
            });

        return response()->json($subcategories);
    }

    public function getCommission(Category $category)
    {
        // Eğer bu bir alt kategori ise, parent'ın commission_rate'ini döndür
        if ($category->parent_id) {
            return response()->json([
                'base_commission' => $category->base_commission,
                'commission_rate' => $category->parent->commission_rate
            ]);
        }
        
        // Ana kategori ise kendi commission_rate'ini döndür
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
            ->map(function ($subcategory) use ($category) {
                return [
                    'id' => $subcategory->id,
                    'name' => $subcategory->name,
                    'base_commission' => $subcategory->base_commission,
                    'commission_rate' => $category->commission_rate, // Üst kategorinin commission_rate'ini döndür
                    'usage_count' => $subcategory->contracts_count
                ];
            });

        return response()->json($subcategories);
    }

    // Normal kullanıcılar için export metodu
    public function export()
    {
        $user = auth()->user();
        
        // Get all contracts with related data
        $contracts = $user->contracts()
            ->with(['category', 'subcategory'])
            ->orderBy('contract_date', 'desc')
            ->get();

        // Calculate statistics
        $stats = [
            'total_contracts' => $contracts->count(),
            'total_commission' => $contracts->sum('commission_amount'),
            'monthly_commission' => $contracts->where('contract_date', '>=', now()->startOfMonth())->sum('commission_amount'),
            'yearly_commission' => $contracts->where('contract_date', '>=', now()->startOfYear())->sum('commission_amount'),
            'average_commission' => $contracts->avg('commission_amount'),
            'highest_commission' => $contracts->max('commission_amount'),
            'lowest_commission' => $contracts->min('commission_amount'),
        ];

        // Get monthly breakdown
        $monthlyBreakdown = $contracts->groupBy(function($contract) {
            return Carbon::parse($contract->contract_date)->format('Y-m');
        })->map(function($monthContracts) {
            return [
                'count' => $monthContracts->count(),
                'total' => $monthContracts->sum('commission_amount'),
                'average' => $monthContracts->avg('commission_amount'),
            ];
        });

        $filename = 'meine_vertraege_' . str_replace(' ', '_', $user->name) . '_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download(new \App\Exports\SingleUserExport($user, $contracts, $stats, $monthlyBreakdown), $filename);
    }
} 