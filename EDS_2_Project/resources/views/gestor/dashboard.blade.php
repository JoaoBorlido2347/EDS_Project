@extends('layouts.app')

@section('title', 'Painel do Gestor')

@push('styles')
<style>
    /* Hide Home button for this specific page */
    .navbar-nav .nav-link.active {
        display: none !important;
    }
</style>
@endpush

@section('content')
<div class="card mb-4">
    <div class="card-body">
        <p>Bem-vindo, <strong>{{ $user->name }}</strong>!</p>
        <p>És um: <span class="badge bg-success text-light text-uppercase">{{ $user->role }}</span></p>
    </div>
</div>

<div class="row">
   
    <div class="col-md-4 mb-4">
        <div class="card h-100 border-primary">
            <div class="card-body text-center">
                <i class="bi bi-map display-4 text-primary mb-3"></i>
                <h5 class="card-title">Produtos</h5>
                <a href="{{ route('gestor.produtos.index') }}" class="btn btn-primary btn-lg w-100">
                    Ver Produtos
                </a>
            </div>
        </div>
    </div>

 
    <div class="col-md-4 mb-4">
        <div class="card h-100 border-info">
            <div class="card-body text-center">
                <i class="bi bi-tags display-4 text-info mb-3"></i>
                <h5 class="card-title">Tipos de Stock</h5>
                <a href="{{ route('gestor.tipos-stock.index') }}" class="btn btn-info btn-lg w-100">
                    Tipos de Stock
                </a>
            </div>
        </div>
    </div>

    
    <div class="col-md-4 mb-4">
        <div class="card h-100 border-warning">
            <div class="card-body text-center">
                <i class="bi bi-box-seam display-4 text-warning mb-3"></i>
                <h5 class="card-title">Encomendas</h5>
                <a href="{{ route('gestor.encomendas.index') }}" class="btn btn-warning btn-lg w-100 text-white">
                    Gerir Encomendas
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card h-100 border-success">
            <div class="card-body text-center">
                <i class="bi bi-check2-square display-4 text-success mb-3"></i>
                <h5 class="card-title">Tarefas</h5>
                <a href="{{ route('gestor.tarefas.index') }}" class="btn btn-success btn-lg w-100">
                    Gerir Tarefas
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
