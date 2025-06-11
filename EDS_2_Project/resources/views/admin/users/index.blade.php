@extends('layouts.app')

@section('title', 'Lista de Utilizadores')

@section('content')
<div class="container">
    {{-- NAVIGATION BAR --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        {{-- Back to Dashboard --}}
        <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card h-100 border-primary">
                        <div class="card-body text-center">
                            <i class="bi bi-people-fill display-4 text-primary mb-3"></i>
                            <button type="button" class="btn btn-primary btn-lg w-100" 
                                    onclick="window.location.href='{{ route('admin.dashboard') }}'">
                                Voltar ao Dashboard
                            </button>
                        </div>
                    </div>
            </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2>Lista de Utilizadores</h2>
            {{-- “Adicionar” button remains the same --}}
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Adicionar</a>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Telefone</th>
                        <th>Role</th>
                        <th>Ativo</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->telefone ?? 'N/A' }}</td>
                        <td>{{ $user->role }}</td>
                        <td>
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning">Editar</a>
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection