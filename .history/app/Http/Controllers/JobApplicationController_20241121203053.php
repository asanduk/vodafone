<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\JobApplicationsExport;
use Maatwebsite\Excel\Facades\Excel;

class JobApplicationController extends Controller
{
    // Başvuruları listeleme
    public function index(Request $request)
    {
        $query = JobApplication::where('user_id', Auth::id());

        // Search functionality
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('position', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Sorting
        $sort = $request->query('sort', 'applied_at'); // Default sort by applied_at
        $direction = $request->query('direction', 'desc'); // Default direction is descending

        $query->orderBy($sort, $direction);

        $applications = $query->paginate(10);
        
        return view('job-applications.index', compact('applications'));
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
