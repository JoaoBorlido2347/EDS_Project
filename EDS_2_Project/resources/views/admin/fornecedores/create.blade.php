@extends('layouts.app')

@section('title', 'Criar Novo Fornecedor')

@section('content')
<div class="container">


    <div class="card shadow-sm">
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.fornecedores.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="nome" class="form-label">Nome</label>
                    <input type="text" id="nome" name="nome" 
                           class="form-control @error('nome') is-invalid @enderror" 
                           value="{{ old('nome') }}" required>
                    @error('nome')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="contacto" class="form-label">Contacto</label>
                    <input type="text" id="contacto" name="contacto" 
                           class="form-control @error('contacto') is-invalid @enderror" 
                           value="{{ old('contacto') }}" required>
                    @error('contacto')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="endereco" class="form-label">Endereço</label>
                    <input type="text" id="endereco" name="endereco" 
                           class="form-control @error('endereco') is-invalid @enderror" 
                           value="{{ old('endereco') }}" required>
                    @error('endereco')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="tipo_parceria" class="form-label">Tipo de Parceria</label>
                    <select id="tipo_parceria" name="tipo_parceria" 
                            class="form-select @error('tipo_parceria') is-invalid @enderror" required>
                        <option value="">-- Selecione o Tipo --</option>
                        <option value="FORNECEDOR" {{ old('tipo_parceria') == 'Fornecimento' ? 'selected' : '' }}>Fornecedor</option>
                        <option value="PARCEIRO" {{ old('tipo_parceria') == 'Serviços' ? 'selected' : '' }}>Parceria</option>
                    </select>
                    @error('tipo_parceria')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="observacoes" class="form-label">Observações</label>
                    <textarea id="observacoes" name="observacoes" 
                              class="form-control @error('observacoes') is-invalid @enderror" 
                              rows="3">{{ old('observacoes') }}</textarea>
                    @error('observacoes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="tipos_stock" class="form-label">Tipos de Stock Fornecidos</label>
                    <select id="tipos_stock" name="tipos_stock[]" 
                            class="form-select @error('tipos_stock') is-invalid @enderror" 
                            multiple size="5">
                        @foreach($tiposStock as $tipo)
                            <option value="{{ $tipo->id }}">{{ $tipo->nome }}</option>
                        @endforeach
                    </select>
                    @error('tipos_stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-success">Criar Fornecedor</button>
                    <a href="{{ route('admin.fornecedores.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection