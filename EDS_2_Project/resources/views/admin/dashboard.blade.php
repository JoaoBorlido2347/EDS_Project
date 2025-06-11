@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container py-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">Admin Dashboard</h2>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-8">
                    <p class="lead mb-1">Welcome, <strong>{{ $user->name }}</strong>!</p>
                    <p class="mb-0">Your role: <span class="badge bg-info">{{ $user->role }}</span></p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card h-100 border-primary">
                        <div class="card-body text-center">
                            <i class="bi bi-people-fill display-4 text-primary mb-3"></i>
                            <h3 class="card-title">Gestão de Utilizadores</h3>
                            <p class="card-text">Visualize e gerencie todos os utilizadores do sistema</p>
                            <button type="button" class="btn btn-primary btn-lg w-100" 
                                    onclick="window.location.href='{{ route('admin.users.index') }}'">
                                Aceder à Lista de Utilizadores
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-4">
                    <div class="card h-100 border-success">
                        <div class="card-body text-center">
                            <i class="bi bi-truck display-4 text-success mb-3"></i>
                            <h3 class="card-title">Gestão de Fornecedores</h3>
                            <p class="card-text">Visualize e gerencie todos os fornecedores e parceiros</p>
                            <button type="button" class="btn btn-success btn-lg w-100" 
                                    onclick="window.location.href='{{ route('admin.fornecedores.index') }}'">
                                Aceder à Lista de Fornecedores
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection