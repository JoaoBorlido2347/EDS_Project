@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Minhas Tarefas</h1>
    
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($tarefas->isEmpty())
        <div class="alert alert-info">
            Não há tarefas atribuídas a você.
        </div>
    @else
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Título</th>
                                <th>Descrição</th>
                                <th>Tipo</th>
                                <th>Estado</th>
                                <th>Atribuída por</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tarefas as $tarefa)
                            <tr>
                                <td>{{ $tarefa->titulo }}</td>
                                <td>{{ $tarefa->descricao }}</td>
                                <td>
                                    @switch($tarefa->tipo)
                                        @case('Receber') Recebimento @break
                                        @case('Mover') Movimentação @break
                                        @case('Enviar') Envio @break
                                        @case('Armazenar') Armazenamento @break
                                    @endswitch
                                </td>
                                <td>
                                    <span class="badge bg-{{ $tarefa->estado == 'Concluida' ? 'success' : 'warning' }}">
                                        {{ $tarefa->estado == 'Concluida' ? 'Concluída' : 'Em Progresso' }}
                                    </span>
                                </td>
                                <td>{{ $tarefa->gestor->name }}</td>
                                <td>
                                    @if ($tarefa->estado == 'Em_Progresso')
                                        <form method="POST" action="{{ route('funcionario.tarefas.concluir', $tarefa) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="bi bi-check-circle"></i> Concluir
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-success">
                                            <i class="bi bi-check2-circle"></i> Concluída
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection