@extends('layouts.app')

@section('title', 'Gestão de Tipos de Stock')
@section('content')
<div class="container">
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-3">
        <a href="{{ route('gestor.tipos-stock.create') }}" class="btn btn-primary"> {{-- Fixed route --}}
            <i class="bi bi-plus-circle"></i> Adicionar Tipo de Stock {{-- Changed text --}}
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tiposStock as $tipo) {{-- Correct loop --}}
                    <tr>
                        <td>{{ $tipo->id }}</td>
                        <td>{{ $tipo->nome }}</td>
                        <td>
                            <a href="{{ route('gestor.tipos-stock.edit', $tipo->id) }}"
                               class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('gestor.tipos-stock.destroy', $tipo) }}" 
                                  method="POST" 
                                  style="display:inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Tem certeza?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            {{ $tiposStock->links() }}
        </div>
    </div>
</div>
@endsection