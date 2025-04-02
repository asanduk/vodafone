<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Contract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Tüm sözleşmeleri al
        $contracts = Contract::where('user_id', $userId)
            ->orderBy('contract_date', 'desc')
            ->get();

        // Ana kategorileri al
        $categories = Category::whereNull('parent_id')
            ->withCount(['contracts' => function($query) use ($userId) {
                $query->where('user_id', $userId);
            }])
            ->get();

        // Son 6 ay için aylık istatistikler
        $monthlyStats = [];
        $monthlyLabels = [];
        $monthlyData = [];
        $monthlyCommissions = [];

        for ($i = 5; $i >= 0; $i--) {
            $startDate = now()->subMonths($i)->startOfMonth();
            $endDate = now()->subMonths($i)->endOfMonth();

            $monthContracts = $contracts->whereBetween('contract_date', [$startDate, $endDate]);
            
            $monthlyData[] = $monthContracts->count();
            $monthlyCommissions[] = $monthContracts->sum('commission_amount');
            
            setlocale(LC_TIME, 'de_DE.utf8', 'de_DE', 'deu_deu');
            $monthlyLabels[] = ucfirst($startDate->formatLocalized('%B %Y'));
        }

        // Debug için
        \Log::info('Monthly Statistics:', [
            'labels' => $monthlyLabels,
            'contracts' => $monthlyData,
            'commissions' => $monthlyCommissions
        ]);

        return view('dashboard', compact(
            'contracts',
            'categories',
            'monthlyData',
            'monthlyLabels',
            'monthlyCommissions'
        ));
    }
}