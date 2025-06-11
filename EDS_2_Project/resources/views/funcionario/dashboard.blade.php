@extends('layouts.app')

<header>
    <div>
        <h1>@yield('title', 'Dashboard')</h1>
        @auth
            <div class="user-info">
                <span>{{ Auth::user()->name }}</span>
                <span>({{ ucfirst(Auth::user()->role) }})</span>
            </div>
        @endauth
    </div>
    <a href="{{ route('funcionario.mapa') }}" class="btn btn-info">
        <i class="fas fa-map"></i> Ver Mapa de Localizações
    </a>
    <div class="col-md-4 mb-4">
    <div class="card h-100 border-primary">
        <div class="card-body text-center">
            <i class="bi bi-list-task display-4 text-primary mb-3"></i>
            <h5 class="card-title">Minhas Tarefas</h5>
            <a href="{{ route('funcionario.tarefas') }}" class="btn btn-primary btn-lg w-100">
                Ver Minhas Tarefas
            </a>
        </div>
    </div>
</div>
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>
</header>