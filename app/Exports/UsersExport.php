<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class UsersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    public function collection()
    {
        return User::with(['contracts' => function($query) {
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
            $user->contract_count = $user->contracts->count();
            $user->average_commission = $user->contracts->avg('commission_amount') ?? 0;
            $user->highest_commission = $user->contracts->max('commission_amount') ?? 0;
            $user->lowest_commission = $user->contracts->min('commission_amount') ?? 0;
            $user->first_contract_date = $user->contracts->min('contract_date');
            $user->last_contract_date = $user->contracts->max('contract_date');
            return $user;
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Email',
            'Admin Status',
            'Registrierungsdatum',
            'Anzahl Verträge',
            'Gesamtprovision (€)',
            'Monatliche Provision (€)',
            'Jährliche Provision (€)',
            'Durchschnittliche Provision (€)',
            'Höchste Provision (€)',
            'Niedrigste Provision (€)',
            'Erster Vertrag',
            'Letzter Vertrag',
            'Letzte Anmeldung'
        ];
    }

    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->is_admin ? 'Ja' : 'Nein',
            $user->created_at ? Carbon::parse($user->created_at)->format('d.m.Y H:i') : '',
            $user->contract_count,
            number_format($user->total_commission, 2),
            number_format($user->monthly_commission, 2),
            number_format($user->yearly_commission, 2),
            number_format($user->average_commission, 2),
            number_format($user->highest_commission, 2),
            number_format($user->lowest_commission, 2),
            $user->first_contract_date ? Carbon::parse($user->first_contract_date)->format('d.m.Y') : 'Keine',
            $user->last_contract_date ? Carbon::parse($user->last_contract_date)->format('d.m.Y') : 'Keine',
            $user->last_login_at ? Carbon::parse($user->last_login_at)->format('d.m.Y H:i') : 'Nie'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => 'E5E7EB'
                    ]
                ]
            ]
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,   // ID
            'B' => 20,  // Name
            'C' => 30,  // Email
            'D' => 15,  // Admin Status
            'E' => 20,  // Registrierungsdatum
            'F' => 15,  // Anzahl Verträge
            'G' => 20,  // Gesamtprovision
            'H' => 20,  // Monatliche Provision
            'I' => 20,  // Jährliche Provision
            'J' => 25,  // Durchschnittliche Provision
            'K' => 20,  // Höchste Provision
            'L' => 20,  // Niedrigste Provision
            'M' => 15,  // Erster Vertrag
            'N' => 15,  // Letzter Vertrag
            'O' => 20,  // Letzte Anmeldung
        ];
    }
}
