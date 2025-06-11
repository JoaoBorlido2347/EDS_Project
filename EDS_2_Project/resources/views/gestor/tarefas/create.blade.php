@extends('layouts.app')

@section('title', 'Criar Nova Tarefa')

@section('content')
<div class="container">
    
    <form action="{{ route('gestor.tarefas.store') }}" method="POST">
        @csrf
        
        <div class="mb-3">
            <label for="titulo" class="form-label">Título *</label>
            <input type="text" class="form-control" id="titulo" name="titulo" required>
        </div>
        
        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição *</label>
            <textarea class="form-control" id="descricao" name="descricao" rows="3" required></textarea>
        </div>
        
            
            <div class="col-md-6 mb-3">
                <label for="tipo" class="form-label">Tipo *</label>
                <select class="form-select" id="tipo" name="tipo" required>
                    <option value="Receber">Receber</option>
                    <option value="Mover">Mover</option>
                    <option value="Enviar">Enviar</option>
                    <option value="Armazenar">Armazenar</option>
                </select>
            </div>
        </div>
        
        <div class="mb-3">
    <div class="card">
    <label class="form-label">Atribuir a Funcionários</label>
    <div class="border p-2 rounded">
        @foreach($funcionarios as $funcionario)
            <div class="form-check">
                <input class="form-check-input" type="checkbox" 
                       name="funcionarios[]" 
                       value="{{ $funcionario->id }}"
                       id="func{{ $funcionario->id }}">
                <label class="form-check-label" for="func{{ $funcionario->id }}">
                    {{ $funcionario->name }}
                </label>
            </div>
        @endforeach
    </div>
    </div>
</div>
        
        <button type="submit" class="btn btn-primary">Criar Tarefa</button>
        <a href="{{ route('gestor.tarefas.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection