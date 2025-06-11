<?php

namespace App\Models;

class Gestor extends User
{
    protected $table = 'users';

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(function ($query) {
            $query->where('tipo', 'Gestor');
        });
    }

    public function verTarefas()
    {
        return Tarefa::all();
    }

    public function criarTarefa($tarefa)
    {
        return Tarefa::create($tarefa);
    }

    public function editarTarefa($tarefa)
    {
        $t = Tarefa::find($tarefa['id']);
        if ($t) {
            $t->update($tarefa);
            return $t;
        }
        return null;
    }

    public function removerTarefa($id)
    {
        return Tarefa::destroy($id);
    }

    public function criarEncomenda($encomenda)
    {
        return Encomenda::create($encomenda);
    }

    public function organizarStock($produto, $localizacao)
    {
        return $produto->localizacoes()->syncWithoutDetaching([
            $localizacao->id => ['quantidade' => $produto->quantidade]
        ]);
    }

    public function atribuirUtilizadorTarefa($tarefa, $utilizador)
    {
        $tarefa->funcionario_id = $utilizador->id;
        return $tarefa->save();
    }

    public function criarTipoProduto($produto)
    {
        return Produto::create($produto);
    }

    public function alterarTipoProduto($produto)
    {
        $p = Produto::find($produto['id']);
        if ($p) {
            $p->update($produto);
            return $p;
        }
        return null;
    }

    public function removerTipoProduto($id)
    {
        return Produto::destroy($id);
    }

    public function verFornecedores()
    {
        return Fornecedor::all();
    }
}