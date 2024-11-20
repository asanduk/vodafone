<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\JobApplicationsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class JobApplicationController extends Controller
{
    // Başvuruları listeleme
    public function index(Request $request)
    {
        $status = $request->get('status');
        $search = $request->get('search');
    
        $applications = Auth::user()->jobApplications()
                        ->when($status, function ($query, $status) {
                            return $query->where('status', $status);
                        })
                        ->when($search, function ($query, $search) {
                            return $query->where('position', 'like', "%$search%")
                                         ->orWhere('company_name', 'like', "%$search%");
                        })
                        ->orderBy('applied_at', 'desc')  // Add this line
                        ->paginate(10);
        
        // Aylara göre başvuru sayısını çekme
        $applicationsByMonth = JobApplication::select(DB::raw('MONTH(created_at) as month, COUNT(*) as count'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month');

        return view('job-applications.index', compact('applications', 'status', 'search', 'applicationsByMonth'));
    }

    // Yeni başvuru formu
    public function create()
    {
        return view('job-applications.create');
    }

    // Başvuru kaydetme
    public function store(Request $request)
    {
        $request->validate([
            'position' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'applied_at' => 'required|date',
            'status' => 'required|in:pending,interview,rejected,offered',
            'job_listing_url' => 'nullable|url',
            'company_website_url' => 'nullable|url',
        ]);
    
        Auth::user()->jobApplications()->create($request->only([
            'position',
            'company_name',
            'applied_at',
            'status',
            'notes',
            'job_listing_url',
            'company_website_url',
        ]));
    
        return redirect()->route('job-applications.index')->with('success', 'Başvuru başarıyla eklendi!');
    }
    

    // Başvuru detaylarını gösterme
    public function show(JobApplication $jobApplication)
    {
        // Başvuru detayları sayfasını görüntüleme
        return view('job-applications.show', compact('jobApplication'));
    }

    // Başvuru düzenleme formu
    public function edit(JobApplication $jobApplication)
    {
        // Başvuru düzenleme formunu görüntüleme
        return view('job-applications.edit', compact('jobApplication'));
    }

    // Başvuru güncelleme
    public function update(Request $request, JobApplication $jobApplication)
{
    $request->validate([
        'position' => 'required|string|max:255',
        'company_name' => 'required|string|max:255',
        'applied_at' => 'required|date',
        'status' => 'required|in:pending,interview,rejected,offered',
        'job_listing_url' => 'nullable|url',
        'company_website_url' => 'nullable|url',
    ]);

    $jobApplication->update($request->only([
        'position',
        'company_name',
        'applied_at',
        'status',
        'notes',
        'job_listing_url',
        'company_website_url',
    ]));

    return redirect()->route('job-applications.index')->with('success', 'Başvuru başarıyla güncellendi!');
}


    // Başvuru silme
    public function destroy(JobApplication $jobApplication)
    {
        // Başvuruyu silme işlemi
        $jobApplication->delete();
        return redirect()->route('job-applications.index')->with('success', 'Başvuru silindi.');
    }

    public function export()
    {
        return Excel::download(new JobApplicationsExport, 'job-applications.xlsx');
    }
    
}
