@extends('layouts.app')

@section('title', 'Editar Tipo de Stock')

@section('content')
<div class="card">
    <div class="card-header">
        <h2>{{ $tipoStock->nome }}</h2> {{-- Changed --}}
    </div>
    <div class="card-body">
        <form action="{{ route('gestor.tipos-stock.update', $tipoStock->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="nome" class="form-label">Nome do Tipo</label>
                <input type="text" class="form-control @error('nome') is-invalid @enderror" 
                       id="nome" name="nome" value="{{ old('nome', $tipoStock->nome) }}" required> {{-- Changed --}}
                @error('nome')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Atualizar</button>
            <a href="{{ route('gestor.tipos-stock.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsection