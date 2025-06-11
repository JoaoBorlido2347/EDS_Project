@extends('layouts.app')

@section('title', 'Gestão de Tarefas')

@section('content')
<div class="container">
    
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('gestor.tarefas.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nova Tarefa
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th>Criada por</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tarefas as $tarefa)
                        <tr>
                            <td>{{ $tarefa->titulo }}</td>
                            <td>
                                <span class="badge bg-{{ $tarefa->tipo == 'Urgente' ? 'danger' : 'info' }}">
                                    {{ $tarefa->tipo }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ 
                                    $tarefa->estado == 'Concluída' ? 'success' : 
                                    ($tarefa->estado == 'Em Progresso' ? 'warning' : 'secondary')
                                }}">
                                    {{ $tarefa->estado }}
                                </span>
                            </td>
                            <td>{{ $tarefa->gestor->name }}</td>
                            <td>
                                <button 
                                class="btn btn-sm btn-info"
                                data-bs-toggle="modal" 
                                data-bs-target="#usersModal"
                                data-tarefa-id="{{ $tarefa->id }}"
                                data-tarefa-titulo="{{ $tarefa->titulo }}"
                                >
                                    <i class="bi bi-people"></i> Ver Funcionários
                                </button>
                                <a href="{{ route('gestor.tarefas.edit', $tarefa->id) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('gestor.tarefas.destroy', $tarefa->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Nenhuma tarefa encontrada</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal for Users -->
<div class="modal fade" id="usersModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Funcionários da Tarefa: <span id="modalTarefaTitulo"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul id="funcionariosList" class="list-group">
                    
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript to handle modal content -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const usersModal = document.getElementById('usersModal');
        
        usersModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const tarefaId = button.getAttribute('data-tarefa-id');
            const tarefaTitulo = button.getAttribute('data-tarefa-titulo');
            
            document.getElementById('modalTarefaTitulo').textContent = tarefaTitulo;
            
            // Clear previous list
            const funcionariosList = document.getElementById('funcionariosList');
            funcionariosList.innerHTML = '';
            
            // Fetch funcionarios
            fetch(`/gestor/tarefas/${tarefaId}/funcionarios`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(funcionarios => {
                    if (funcionarios.length === 0) {
                        funcionariosList.innerHTML = '<li class="list-group-item">Nenhum funcionário atribuído</li>';
                    } else {
                        funcionarios.forEach(funcionario => {
                            const listItem = document.createElement('li');
                            listItem.className = 'list-group-item';
                            listItem.textContent = funcionario.name;
                            funcionariosList.appendChild(listItem);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    funcionariosList.innerHTML = '<li class="list-group-item text-danger">Erro ao carregar funcionários</li>';
                });
        });
    });
</script>
@endsection