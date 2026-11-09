<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class ProjectController extends Controller implements HasMiddleware
{
    /**
     * Pengaman tingkat controller (pengganti constructor middleware di Laravel 11 ke atas).
     * Tetap berlaku walaupun seseorang mengetik URL langsung tanpa lewat tombol.
     *
     * @return array<int, Middleware>
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:lihat-data', only: ['index']),
            new Middleware('permission:hapus-data', only: ['destroy']),
        ];
    }

    /**
     * Tabel project dengan pencarian, filter tema, dan pagination yang membawa parameter filter.
     */
    public function index(Request $request): View
    {
        $projects = Project::query()
            ->with('user')
            ->when($request->search, function ($query, $search) {
                return $query->where(function ($sub) use ($search) {
                    $sub->where('judul', 'like', "%{$search}%")
                        ->orWhere('deskripsi', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($user) use ($search) {
                            $user->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($request->tema_agent, function ($query, $tema) {
                return $query->where('tema_agent', $tema);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString(); // kunci parameter search dan tema_agent di semua tautan halaman

        $daftarTema = Project::query()
            ->select('tema_agent')
            ->distinct()
            ->orderBy('tema_agent')
            ->pluck('tema_agent');

        return view('admin.projects.index', compact('projects', 'daftarTema'));
    }

    /**
     * Hapus data: hanya untuk peran Admin murni.
     */
    public function destroy(Request $request, Project $project): RedirectResponse
    {
        // Lapis kedua di sisi server, selain middleware permission di atas
        abort_unless($request->user()->hasRole('Admin'), 403);

        $project->delete();

        return back()->with('status', "Project \"{$project->judul}\" berhasil dihapus.");
    }
}
