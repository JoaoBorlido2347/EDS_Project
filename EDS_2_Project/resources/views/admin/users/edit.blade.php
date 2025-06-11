@extends('layouts.app')

@section('title', 'Editar Utilizador')

@section('content')
<div class="container">
    {{-- NAVIGATION: Back to list + Logout --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            &larr; Voltar à Lista de Utilizadores
        </a>
        <form action="{{ route('logout') }}" method="POST" class="m-0 p-0">
            @csrf
            <button type="submit" class="btn btn-danger">Sair</button>
        </form>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Editar Utilizador: {{ $user->name }}</h3>
        </div>
        <div class="card-body">
            {{-- Show validation errors, if any --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Name --}}
                <div class="mb-3">
                    <label for="name" class="form-label">Nome</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $user->name) }}"
                        required
                        autofocus
                    >
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email', $user->email) }}"
                        required
                    >
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password (Optional) --}}
                <div class="mb-3">
                    <label for="password" class="form-label">Password <span class="text-muted">(Deixar em branco para manter atual)</span></label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control @error('password') is-invalid @enderror"
                    >
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password Confirmation --}}
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirmar Password</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="form-control @error('password_confirmation') is-invalid @enderror"
                    >
                    @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Telefone --}}
                <div class="mb-3">
                    <label for="telefone" class="form-label">Telefone</label>
                    <input
                        type="text"
                        id="telefone"
                        name="telefone"
                        class="form-control @error('telefone') is-invalid @enderror"
                        value="{{ old('telefone', $user->telefone == '#Null#' ? '' : $user->telefone) }}"
                    >
                    @error('telefone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Role --}}
                <div class="mb-3">
                    <label for="role" class="form-label">Função (Role)</label>
                    <select
                        id="role"
                        name="role"
                        class="form-select @error('role') is-invalid @enderror"
                        required
                    >
                        <option value="">-- Selecione uma Função --</option>
                        <option value="administrador" {{ (old('role', $user->role) == 'administrador' ? 'selected' : '') }}>Administrador</option>
                        <option value="gestor" {{ (old('role', $user->role) == 'gestor' ? 'selected' : '') }}>Gestor</option>
                        <option value="funcionario" {{ (old('role', $user->role) == 'funcionario' ? 'selected' : '') }}>Funcionário</option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Submit --}}
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        Atualizar Utilizador
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection