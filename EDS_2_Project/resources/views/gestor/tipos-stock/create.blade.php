@extends('layouts.app')

@section('title', 'Criar Tipo de Stock')

@section('content')
<div class="card">
    <div class="card-header">
        <h2>Criar Novo Tipo de Stock</h2>
    </div>
    <div class="card-body">
        <form action="{{ route('gestor.tipos-stock.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="nome" class="form-label">Nome do Tipo</label>
                <input type="text" class="form-control @error('nome') is-invalid @enderror" 
                       id="nome" name="nome" required>
                @error('nome')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Criar Tipo</button>
            <a href="{{ route('gestor.tipos-stock.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsection