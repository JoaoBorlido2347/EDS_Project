@extends('layouts.app')

@section('title', 'Lista de Fornecedores')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2>Lista de Fornecedores</h2>
            <a href="{{ route('admin.fornecedores.create') }}" class="btn btn-primary">Adicionar</a>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Contacto</th>
                        <th>Endereço</th>
                        <th>Tipo Parceria</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($fornecedores as $fornecedor)
                    <tr>
                        <td>{{ $fornecedor->id }}</td>
                        <td>{{ $fornecedor->nome }}</td>
                        <td>{{ $fornecedor->email }}</td>
                        <td>{{ $fornecedor->contacto }}</td>
                        <td>{{ $fornecedor->endereco }}</td>
                        <td>{{ $fornecedor->tipo_parceria }}</td>
                        <td>
                                @csrf <!-- Add this -->
    @method('PUT') <!-- For update routes -->
                            <a href="{{ route('admin.fornecedores.edit', $fornecedor->id) }}" class="btn btn-sm btn-warning">Editar</a>
                            <form action="{{ route('admin.fornecedores.destroy', $fornecedor->id) }}" method="POST" class="d-inline">
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