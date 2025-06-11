<?php

namespace App\Models;

class Administrador extends User
{
    protected $table = 'users';

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(function ($query) {
            $query->where('tipo', 'Administrador');
        });
    }

    public function criarUtilizador($utilizador)
    {
        return User::create($utilizador);
    }

    public function alterarUtilizador($utilizador)
    {
        $user = User::find($utilizador['id']);
        if ($user) {
            $user->update($utilizador);
            return $user;
        }
        return null;
    }

    public function removerUtilizador($id)
    {
        return User::destroy($id);
    }

    public function criarFornecedor($fornecedor)
    {
        return Fornecedor::create($fornecedor);
    }

    public function alterarFornecedor($fornecedor)
    {
        $forn = Fornecedor::find($fornecedor['id']);
        if ($forn) {
            $forn->update($fornecedor);
            return $forn;
        }
        return null;
    }

    public function removerFornecedor($id)
    {
        return Fornecedor::destroy($id);
    }
}