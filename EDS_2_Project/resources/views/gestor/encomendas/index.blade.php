@extends('layouts.app')
@section('title', 'Gestão de Encomendas')
@section('content')
<div class="container">
    <a href="{{ route('gestor.encomendas.create') }}" class="btn btn-primary mb-3">
        <i class="bi bi-plus-circle"></i> Nova Encomenda
    </a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Data</th>
                        <th>Fornecedor</th>
                        <th>Estado</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($encomendas as $encomenda)
                    <tr>
                        <td>{{ $encomenda->id }}</td>
                        <td>{{ $encomenda->data->format('d/m/Y') }}</td>
                        <td>{{ $encomenda->fornecedor->nome }}</td>
                        <td>
                            <span class="badge 
                                @if($encomenda->estado == 'Pendente') bg-warning
                                @elseif($encomenda->estado == 'Recebida') bg-success
                                @else bg-danger
                                @endif">
                                {{ $encomenda->estado }}
                            </span>
                        </td>
                        <td>
                            @if($encomenda->podeSerAlterada())
                                <form method="POST" action="{{ route('gestor.encomendas.update-estado', $encomenda->id) }}">
                                    @csrf
                                    <div class="input-group">
                                        <select name="estado" class="form-select form-select-sm" required>
                                            <option value="" selected disabled>Alterar estado</option>
                                            <option value="Recebida">Recebida</option>
                                            <option value="Cancelada">Cancelada</option>
                                        </select>
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                    </div>
                                </form>
                            @else
                                <span class="text-muted">Finalizada</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection