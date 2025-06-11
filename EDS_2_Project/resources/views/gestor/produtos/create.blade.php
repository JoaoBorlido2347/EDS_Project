@extends('layouts.app')
@section('title', 'Criar Novo Produto')
@section('content')
<div class="container">
    
    <form action="{{ route('gestor.produtos.store') }}" method="POST">
        @csrf
        @include('gestor.produtos._form')
        
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> Criar Produto
        </button>
    </form>
</div>
@endsection