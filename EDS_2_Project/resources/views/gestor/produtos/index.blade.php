@extends('layouts.app')
@section('title', 'Gestão de Produtos')
@section('content')
<div class="container">

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="mb-3">
        <a href="{{ route('gestor.produtos.map') }}" class="btn btn-info">
            <i class="fas fa-map"></i> Ver Mapa de Localizações
        </a>
    
        <a href="{{ route('gestor.produtos.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Adicionar Produto
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Tipo de Stock</th>
                        <th>Quantidade</th>
                        <th>Localização</th>
                        <th>Criado em</th>
                        <th>Atualizado em</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($produtos as $produto)  
                    <tr>
                        <td>{{ $produto->id }}</td>
                        <td>{{ $produto->nome }}</td>
                        <td>{{ $produto->tipoStock->nome }}</td>
                        <td>{{ $produto->quantidade }}</td>
                        <td>
                            @if($produto->localizacao)
                                Piso {{ $produto->localizacao->piso }},
                                {{ $produto->localizacao->corredor }},
                                {{ $produto->localizacao->prateleira }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>{{ $produto->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $produto->updated_at->format('d/m/Y H:i') }}</td>
                        <td class="text-center">
                            <a href="{{ route('gestor.produtos.edit', $produto->id) }}" 
                               class="btn btn-sm btn-warning" title="Editar">
                                <i class="bi bi-pencil">Editar</i>
                            </a>
                            <form action="{{ route('gestor.produtos.destroy', $produto->id) }}" 
                                  method="POST" 
                                  style="display:inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Tem certeza?')"
                                        title="Eliminar">
                                    <i class="bi bi-trash">Eliminar</i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">Nenhum produto encontrado.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            {{ $produtos->links() }}
        </div>
    </div>
</div>
@endsection