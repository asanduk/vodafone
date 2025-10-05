<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Contract;
use App\Exports\UsersExport;
use App\Exports\SingleUserExport;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function archived()
    {
        $archivedUsers = User::onlyTrashed()->with('contracts')->get();

        return view('admin.users.archived', compact('archivedUsers'));
    }

    public function index()
    {
        $users = User::with(['contracts' => function($query) {
            $query->select('user_id', 'commission_amount', 'contract_date');
        }])
        ->get()
        ->map(function($user) {
            $user->total_commission = $user->contracts->sum('commission_amount');
            $user->monthly_commission = $user->contracts
                ->where('contract_date', '>=', now()->startOfMonth())
                ->where('contract_date', '<=', now()->endOfMonth())
                ->sum('commission_amount');
            $user->yearly_commission = $user->contracts
                ->where('contract_date', '>=', now()->startOfYear())
                ->where('contract_date', '<=', now()->endOfYear())
                ->sum('commission_amount');
            return $user;
        });

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
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

        return view('admin.users.show', compact('user', 'contracts', 'stats', 'monthlyBreakdown'));
    }

    public function export()
    {
        $filename = 'benutzer_uebersicht_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download(new UsersExport, $filename);
    }

    public function exportUser(User $user)
    {
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

        $filename = 'benutzer_details_' . str_replace(' ', '_', $user->name) . '_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download(new SingleUserExport($user, $contracts, $stats, $monthlyBreakdown), $filename);
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'branch' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'is_admin' => 'boolean',
        ]);

        // Geçici şifre oluştur
        $temporaryPassword = Str::random(12);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($temporaryPassword),
            'is_admin' => $request->has('is_admin'),
            'branch' => $request->branch,
            'phone' => $request->phone,
            'address' => $request->address,
            'is_active' => true,
        ]);

        // Email gönder (şimdilik log'a yazdır)
        \Log::info('New User Created', [
            'user_id' => $user->id,
            'email' => $user->email,
            'temporary_password' => $temporaryPassword
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', "Benutzer '{$user->name}' wurde erfolgreich erstellt. Temporäres Passwort: {$temporaryPassword}");
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'branch' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'is_admin' => $request->has('is_admin'),
            'branch' => $request->branch,
            'phone' => $request->phone,
            'address' => $request->address,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', "Benutzer '{$user->name}' wurde erfolgreich aktualisiert.");
    }

    public function destroy(User $user)
    {
        // Admin kann sich nicht selbst löschen
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Sie können sich nicht selbst löschen.'
            ], 400);
        }

        $userName = $user->name;
        
        // Soft delete kullan
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => "Benutzer '{$userName}' wurde erfolgreich archiviert (Verträge bleiben erhalten)."
        ]);
    }

    public function forceDelete($id)
    {
        try {
            $user = User::onlyTrashed()->findOrFail($id);
            
            // Admin kann sich nicht selbst löschen
            if ($user->id === auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sie können sich nicht selbst löschen.'
                ], 400);
            }

            $userName = $user->name;
            
            // Alle zugehörigen Daten löschen
            $user->contracts()->delete();
            $user->forceDelete();

            return response()->json([
                'success' => true,
                'message' => "Benutzer '{$userName}' und alle Daten wurden vollständig gelöscht."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Fehler beim endgültigen Löschen des Benutzers: ' . $e->getMessage()
            ], 500);
        }
    }

    public function restore($id)
    {
        try {
            $user = User::onlyTrashed()->findOrFail($id);
            $user->restore();

            return response()->json([
                'success' => true,
                'message' => "Benutzer '{$user->name}' wurde erfolgreich wiederhergestellt."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Fehler beim Wiederherstellen des Benutzers: ' . $e->getMessage()
            ], 500);
        }
    }
} 