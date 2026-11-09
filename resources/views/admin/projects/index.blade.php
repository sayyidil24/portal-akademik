@extends('adminlte::page')

@section('title', 'Data Project')

@section('content_header')
    <h1>Data Project</h1>
@stop

@section('content')
    @if (session('status'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ session('status') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            {{-- Form pencarian dan filter (GET) agar hasilnya bisa dibagikan lewat URL --}}
            <form method="GET" action="{{ route('admin.projects.index') }}" class="form-inline">
                <input type="text" name="search" value="{{ request('search') }}"
                    class="form-control mr-2 mb-1" placeholder="Cari judul, deskripsi, atau pemilik">

                <select name="tema_agent" class="form-control mr-2 mb-1">
                    <option value="">Semua tema agent</option>
                    @foreach ($daftarTema as $tema)
                        <option value="{{ $tema }}" @selected(request('tema_agent') === $tema)>{{ $tema }}</option>
                    @endforeach
                </select>

                <button type="submit" class="btn btn-primary mr-2 mb-1">
                    <i class="fas fa-search"></i> Cari
                </button>

                @if (request()->hasAny(['search', 'tema_agent']))
                    <a href="{{ route('admin.projects.index') }}" class="btn btn-default mb-1">Reset</a>
                @endif
            </form>
        </div>

        <div class="card-body table-responsive p-0">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th style="width: 50px">No</th>
                        <th>Judul</th>
                        <th>Pemilik</th>
                        <th>Tema Agent</th>
                        <th>Dibuat</th>
                        @role('Admin')
                            <th style="width: 110px">Aksi</th>
                        @endrole
                    </tr>
                </thead>
                <tbody>
                    @forelse ($projects as $project)
                        <tr>
                            <td>{{ $projects->firstItem() + $loop->index }}</td>
                            <td>{{ $project->judul }}</td>
                            <td>{{ $project->user->name }}</td>
                            <td><span class="badge badge-info">{{ $project->tema_agent }}</span></td>
                            <td>{{ $project->created_at->format('d M Y') }}</td>

                            {{-- Tombol hapus hanya dirender untuk peran Admin murni --}}
                            @role('Admin')
                                <td>
                                    <form method="POST" action="{{ route('admin.projects.destroy', $project) }}"
                                        onsubmit="return confirm('Hapus project ini? Tindakan tidak dapat dibatalkan.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-xs btn-danger">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            @endrole
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Tidak ada data yang cocok.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer clearfix">
            <span class="text-muted">
                Menampilkan {{ $projects->firstItem() ?? 0 }} sampai {{ $projects->lastItem() ?? 0 }}
                dari {{ $projects->total() }} data
            </span>
            <div class="float-right">
                {{-- Tema pagination Bootstrap 4 agar cocok dengan AdminLTE --}}
                {{ $projects->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
@stop
