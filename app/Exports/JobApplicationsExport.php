<?php

namespace App\Exports;

use App\Models\JobApplication;
use Maatwebsite\Excel\Concerns\FromCollection;

class JobApplicationsExport implements FromCollection
{
    /**
     * Export edilecek verileri döndür.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Tüm iş başvurularını getir
        return JobApplication::all();
    }
}
