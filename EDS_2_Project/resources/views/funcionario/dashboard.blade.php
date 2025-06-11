{{-- resources/views/funcionario/dashboard.blade.php --}}

@extends('layouts.app')

@section('title', 'Painel do Funcionário')

@push('styles')
<style>
    .navbar-nav .nav-link.active {
        display: none !important;
    }
</style>
@endpush

@section('content')
    <div class="card mb-4">
        <div class="card-body">
            <p> Bem-vindo <strong>{{ $user->name }}</strong>!</p>
            <p> És um: <span class="badge bg-warning">{{ $user->role }}</span></p>
        <div class="card-body">
    </div>

    <div class="row justify-content-center">
        {{-- Card: Minhas Tarefas --}}
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-primary">
                <div class="card-body text-center">
                    <i class="bi bi-list-task display-4 text-primary mb-3"></i>
                    <h5 class="card-title">Minhas Tarefas</h5>
                    <a href="{{ route('funcionario.tarefas') }}" class="btn btn-primary btn-lg w-100">
                        Ver Minhas Tarefas
                    </a>
                </div>
            </div>
        </div>

        {{-- Card: Mapa de Produtos --}}
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-info">
                <div class="card-body text-center">
                    <i class="bi bi-map display-4 text-info mb-3"></i>
                    <h5 class="card-title">Mapa de Produtos</h5>
                    <a href="{{ route('funcionario.mapa') }}" class="btn btn-info btn-lg w-100">
                        Ver Mapa de Localizações
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
