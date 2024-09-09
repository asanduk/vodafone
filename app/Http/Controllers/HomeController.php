<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    // Dashboard ana sayfası
    public function index()
    {
        // Kullanıcıya ait başvuruların durumlarına göre sayımlar
        $pendingCount = Auth::user()->jobApplications()->where('status', 'pending')->count();
        $interviewCount = Auth::user()->jobApplications()->where('status', 'interview')->count();
        $rejectedCount = Auth::user()->jobApplications()->where('status', 'rejected')->count();
        $offeredCount = Auth::user()->jobApplications()->where('status', 'offered')->count();

        // Dashboard görünümüne verileri gönderiyoruz
        return view('dashboard', compact('pendingCount', 'interviewCount', 'rejectedCount', 'offeredCount'));
    }
}
