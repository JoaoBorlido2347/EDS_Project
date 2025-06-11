@extends('layouts.app')

@section('title', 'Editar Tarefa')

@section('content')
<div class="container">
    <h1 class="mb-4">Editar Tarefa</h1>
    
    <form action="{{ route('gestor.tarefas.update', $tarefa->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-3">
            <label for="titulo" class="form-label">Título *</label>
            <input type="text" class="form-control" id="titulo" name="titulo" 
                   value="{{ old('titulo', $tarefa->titulo) }}" required>
        </div>
        
        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição *</label>
            <textarea class="form-control" id="descricao" name="descricao" 
                      rows="3" required>{{ old('descricao', $tarefa->descricao) }}</textarea>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="tipo" class="form-label">Tipo *</label>
                <select class="form-select" id="tipo" name="tipo" required>
                    <option value="Receber" {{ $tarefa->tipo == 'Receber' ? 'selected' : '' }}>Receber</option>
                    <option value="Mover" {{ $tarefa->tipo == 'Mover' ? 'selected' : '' }}>Mover</option>
                    <option value="Enviar" {{ $tarefa->tipo == 'Enviar' ? 'selected' : '' }}>Enviar</option>
                    <option value="Armazenar" {{ $tarefa->tipo == 'Armazenar' ? 'selected' : '' }}>Armazenar</option>
                </select>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="estado" class="form-label">Estado *</label>
                <select class="form-select" id="estado" name="estado" required>
                    <option value="Em_Progresso" {{ $tarefa->estado == 'Em_Progresso' ? 'selected' : '' }}>Em Progresso</option>
                    <option value="Concluida" {{ $tarefa->estado == 'Concluida' ? 'selected' : '' }}>Concluída</option>
                </select>
            </div>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Atribuir a Funcionários</label>
            <div class="border p-2 rounded">
                @foreach($funcionarios as $funcionario)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" 
                               name="funcionarios[]" 
                               value="{{ $funcionario->id }}"
                               id="func{{ $funcionario->id }}"
                               {{ in_array($funcionario->id, $assignedFuncionarios) ? 'checked' : '' }}>
                        <label class="form-check-label" for="func{{ $funcionario->id }}">
                            {{ $funcionario->name }}
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
        
        <button type="submit" class="btn btn-primary">Atualizar Tarefa</button>
        <a href="{{ route('gestor.tarefas.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection