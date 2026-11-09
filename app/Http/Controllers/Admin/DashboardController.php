<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Beranda panel admin: widget statistik dihitung langsung dari database.
     */
    public function index(): View
    {
        $statistik = [
            'totalUser' => User::count(),
            'totalProject' => Project::count(),
            'totalPengelola' => User::role(['Admin', 'Staf'])->count(),
            'projectBaru' => Project::where('created_at', '>=', now()->subDays(7))->count(),
        ];

        $projectTerbaru = Project::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact('statistik', 'projectTerbaru'));
    }
}
