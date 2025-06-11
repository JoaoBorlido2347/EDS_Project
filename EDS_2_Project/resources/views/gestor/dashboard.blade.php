@extends('layouts.app')

@section('title', 'Gestor Dashboard')

@section('content')
<div class="card mb-4">
    <h2>Gestor Dashboard</h2>
    <p>Welcome, <strong>{{ $user->name }}</strong>!</p>
    <p>Your role: <span class="badge bg-success">{{ $user->role }}</span></p>
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
    <!-- Botão para Tipos de Stock (opcional) -->
    <div class="col-md-6 mb-4">
        <div class="card h-100 border-info">
            <div class="card-body text-center">
                <i class="bi bi-tags display-4 text-info mb-3"></i>
                <a href="{{ route('gestor.tipos-stock.index') }}" class="btn btn-info btn-lg w-100">
                    Tipos de Stock
                </a>
            </div>
        </div>
    </div>
</div>
<div class="col-md-4 mb-4">
    <div class="card h-100 border-primary">
        <div class="card-body text-center">
            <i class="bi bi-box-seam display-4 text-primary mb-3"></i>
            <h5 class="card-title">Encomendas</h5>
            <a href="{{ route('gestor.encomendas.index') }}" class="btn btn-primary btn-lg w-100">
                Gerir Encomendas
            </a>
        </div>
    </div>
</div>
<div class="col-md-4 mb-4">
    <div class="card h-100 border-primary">
        <div class="card-body text-center">
            <i class="bi bi-box-seam display-4 text-primary mb-3"></i>
            <h5 class="card-title">tarefas</h5>
            <a href="{{ route('gestor.tarefas.index') }}" class="btn btn-primary btn-lg w-100">
                Gerir tarefas
            </a>
        </div>
    </div>
</div>
@endsection