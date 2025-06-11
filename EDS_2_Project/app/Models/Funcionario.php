<?php

namespace App\Models;

class Funcionario extends User
{
    protected $table = 'users';

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(function ($query) {
            $query->where('tipo', 'Funcionário');
        });
    }

    public function verTarefas()
    {
        return $this->tarefasAtribuidas;
    }

    public function verificarStock($localizacao)
    {
        return $localizacao->produtos;
    }

    public function atualizarQuantidade($produto, $novaQtd)
    {
        $produto->quantidade = $novaQtd;
        $produto->esgotado = ($novaQtd <= 0);
        return $produto->save();
    }

    public function marcarProdutoEsgotado($produto)
    {
        $produto->esgotado = true;
        return $produto->save();
    }
}