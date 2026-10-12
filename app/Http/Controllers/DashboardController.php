<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Dashboard profil: hanya menampilkan project milik user yang sedang login, terbaru lebih dulu.
     * Kueri memakai Eloquent (parameter terikat), sehingga aman dari SQL injection.
     */
    public function __invoke(Request $request): View
    {
        $projects = Project::query()
            ->whereBelongsTo($request->user())
            ->latest()
            ->get();

        return view('dashboard', compact('projects'));
    }
}
