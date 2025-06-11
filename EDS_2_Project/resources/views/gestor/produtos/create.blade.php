@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Criar Novo Produto</h1>
    
    <form action="{{ route('gestor.produtos.store') }}" method="POST">
        @csrf
        @include('gestor.produtos._form')
        
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> Criar Produto
        </button>
    </form>
</div>
@endsection