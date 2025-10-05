<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class SingleUserExport implements WithMultipleSheets
{
    protected $user;
    protected $contracts;
    protected $stats;
    protected $monthlyBreakdown;

    public function __construct(User $user, $contracts, $stats, $monthlyBreakdown)
    {
        $this->user = $user;
        $this->contracts = $contracts;
        $this->stats = $stats;
        $this->monthlyBreakdown = $monthlyBreakdown;
    }

    public function sheets(): array
    {
        return [
            'Benutzer Info' => new UserInfoSheet($this->user, $this->stats),
            'Verträge' => new UserContractsSheet($this->contracts),
            'Monatliche Übersicht' => new UserMonthlySheet($this->monthlyBreakdown),
        ];
    }
}

class UserInfoSheet implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $user;
    protected $stats;

    public function __construct(User $user, $stats)
    {
        $this->user = $user;
        $this->stats = $stats;
    }

    public function collection()
    {
        return collect([
            ['Name', $this->user->name],
            ['Email', $this->user->email],
            ['Admin Status', $this->user->is_admin ? 'Ja' : 'Nein'],
            ['Registriert am', $this->user->created_at ? Carbon::parse($this->user->created_at)->format('d.m.Y H:i') : ''],
            ['Letzte Anmeldung', $this->user->last_login_at ? Carbon::parse($this->user->last_login_at)->format('d.m.Y H:i') : 'Nie'],
            ['', ''],
            ['STATISTIKEN', ''],
            ['Gesamtverträge', $this->stats['total_contracts']],
            ['Gesamtprovision', number_format($this->stats['total_commission'], 2) . '€'],
            ['Monatliche Provision', number_format($this->stats['monthly_commission'], 2) . '€'],
            ['Jährliche Provision', number_format($this->stats['yearly_commission'], 2) . '€'],
            ['Durchschnittliche Provision', number_format($this->stats['average_commission'], 2) . '€'],
            ['Höchste Provision', number_format($this->stats['highest_commission'], 2) . '€'],
            ['Niedrigste Provision', number_format($this->stats['lowest_commission'], 2) . '€'],
        ]);
    }

    public function headings(): array
    {
        return ['Eigenschaft', 'Wert'];
    }

    public function map($row): array
    {
        return $row;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E5E7EB']
                ]
            ],
            7 => [
                'font' => ['bold' => true, 'size' => 14],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'D1D5DB']
                ]
            ]
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 30,
        ];
    }
}

class UserContractsSheet implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $contracts;

    public function __construct($contracts)
    {
        $this->contracts = $contracts;
    }

    public function collection()
    {
        return $this->contracts;
    }

    public function headings(): array
    {
        return [
            'Datum',
            'Vertragsnummer',
            'Kunde',
            'Kategorie',
            'Produktname',
            'Provision (€)'
        ];
    }

    public function map($contract): array
    {
        return [
            $contract->contract_date ? Carbon::parse($contract->contract_date)->format('d.m.Y') : '',
            $contract->contract_number ?? '',
            $contract->customer_name ?? '',
            $contract->category ? $contract->category->name : '',
            $contract->subcategory ? $contract->subcategory->name : '',
            number_format($contract->commission_amount, 2)
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E5E7EB']
                ]
            ]
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 20,
            'C' => 25,
            'D' => 20,
            'E' => 25,
            'F' => 15,
        ];
    }
}

class UserMonthlySheet implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $monthlyBreakdown;

    public function __construct($monthlyBreakdown)
    {
        $this->monthlyBreakdown = $monthlyBreakdown;
    }

    public function collection()
    {
        return $this->monthlyBreakdown->map(function($data, $month) {
            return (object) [
                'month' => $month,
                'count' => $data['count'],
                'total' => $data['total'],
                'average' => $data['average']
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Monat',
            'Anzahl Verträge',
            'Gesamtprovision (€)',
            'Durchschnittliche Provision (€)'
        ];
    }

    public function map($data): array
    {
        return [
            Carbon::createFromFormat('Y-m', $data->month)->format('F Y'),
            $data->count,
            number_format($data->total, 2),
            number_format($data->average, 2)
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E5E7EB']
                ]
            ]
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 18,
            'C' => 20,
            'D' => 25,
        ];
    }
}
