@extends('adminlte::page')

@section('title', 'Dashboard Admin')

@section('content_header')
    <h1>Dashboard Admin</h1>
@stop

@section('content')
    @if (session('status'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ session('status') }}
        </div>
    @endif

    <p class="text-muted">
        Login sebagai <strong>{{ auth()->user()->name }}</strong>
        (peran: <strong>{{ auth()->user()->getRoleNames()->implode(', ') }}</strong>)
    </p>

    {{-- Widget statistik dinamis: angka berasal dari kueri count() pada model --}}
    <div class="row">
        <div class="col-lg-3 col-6">
            <x-adminlte-small-box :title="(string) $statistik['totalUser']" text="Total User"
                icon="fas fa-users" theme="info"/>
        </div>
        <div class="col-lg-3 col-6">
            <x-adminlte-small-box :title="(string) $statistik['totalProject']" text="Total Project"
                icon="fas fa-folder-open" theme="success"
                :url="route('admin.projects.index')" url-text="Lihat data"/>
        </div>
        <div class="col-lg-3 col-6">
            <x-adminlte-small-box :title="(string) $statistik['totalPengelola']" text="Pengelola Panel"
                icon="fas fa-user-shield" theme="warning"/>
        </div>
        <div class="col-lg-3 col-6">
            <x-adminlte-small-box :title="(string) $statistik['projectBaru']" text="Project 7 Hari Terakhir"
                icon="fas fa-clock" theme="danger"/>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Project Terbaru</h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Pemilik</th>
                        <th>Tema Agent</th>
                        <th>Dibuat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($projectTerbaru as $project)
                        <tr>
                            <td>{{ $project->judul }}</td>
                            <td>{{ $project->user->name }}</td>
                            <td><span class="badge badge-info">{{ $project->tema_agent }}</span></td>
                            <td>{{ $project->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Belum ada project.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop
