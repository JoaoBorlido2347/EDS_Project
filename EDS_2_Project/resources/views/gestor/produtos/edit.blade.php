@extends('layouts.app')
@section('title', 'Editar Produto:')
@section('content')
<div class="container">
    <h1>{{ $produto->nome }}</h1>
    
    <form action="{{ route('gestor.produtos.update', $produto->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-3">
            <label class="form-label">Nome do Produto</label>
            <input type="text" 
                   class="form-control" 
                   name="nome" 
                   value="{{ old('nome', $produto->nome) }}"
                   required>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Tipo de Stock</label>
            <select class="form-select" name="tipo_stock_id" required>
                @foreach(App\Models\TipoStock::all() as $tipo)
                <option value="{{ $tipo->id }}"
                    {{ (old('tipo_stock_id', $produto->tipo_stock_id) == $tipo->id) ? 'selected' : '' }}>
                    {{ $tipo->nome }}
                </option>
                @endforeach
            </select>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Quantidade</label>
            <input type="number" 
                   class="form-control" 
                   name="quantidade" 
                   min="0"
                   value="{{ old('quantidade', $produto->quantidade) }}"
                   required>
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <div class="form-control bg-{{ $produto->esgotado ? 'danger text-white' : 'success text-white' }}">
                {{ $produto->esgotado ? 'ESGOTADO' : 'DISPONÍVEL' }}
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Piso</label>
                    <select class="form-select" name="piso" required>
                        <option value="">Selecione o Piso</option>
                        @foreach(App\Models\Localizacao::$allowedPisos as $piso)
                        <option value="{{ $piso }}"
                            {{ old('piso', $produto->localizacao->piso ?? '') == $piso ? 'selected' : '' }}>
                            {{ $piso }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Corredor</label>
                    <select class="form-select" name="corredor" required>
                        <option value="">Selecione o Corredor</option>
                        @foreach(App\Models\Localizacao::$allowedCorredores as $corredor)
                        <option value="{{ $corredor }}"
                            {{ old('corredor', $produto->localizacao->corredor ?? '') == $corredor ? 'selected' : '' }}>
                            {{ $corredor }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Prateleira</label>
                    <select class="form-select" name="prateleira" required>
                        <option value="">Selecione a Prateleira</option>
                        @foreach(App\Models\Localizacao::$allowedPrateleiras as $prateleira)
                        <option value="{{ $prateleira }}"
                            {{ old('prateleira', $produto->localizacao->prateleira ?? '') == $prateleira ? 'selected' : '' }}>
                            {{ $prateleira }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        
        @if($errors->has('localizacao'))
            <div class="alert alert-danger mt-3">
                {{ $errors->first('localizacao') }}
            </div>
        @endif
        
        <button type="submit" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Atualizar Produto
        </button>
    </form>
</div>
@endsection