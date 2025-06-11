<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarefa extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descricao',
        'estado',
        'tipo',
        'gestor_id'
    ];

    public function gestor()
    {
        return $this->belongsTo(User::class, 'gestor_id');
    }

    public function funcionarios()
    {
        return $this->belongsToMany(User::class, 'tarefa_funcionario', 'tarefa_id', 'funcionario_id');
    }

    public function produtos()
    {
        return $this->belongsToMany(Produto::class, 'tarefa_produto')
                    ->withPivot('quantidade')
                    ->withTimestamps();
    }

    public function atualizarEstado($novoEstado)
    {
        $this->estado = $novoEstado;
        return $this->save();
    }

    public function adicionarFuncionario($funcionario)
    {
        return $this->funcionarios()->attach($funcionario->id);
    }

    public function removerFuncionario($funcionario)
    {
        return $this->funcionarios()->detach($funcionario->id);
    }
   
    public function getEstadoFormatadoAttribute()
    {
        return $this->estado == 'Concluida' ? 'Concluída' : 'Em Progresso';
    }

 
    protected $appends = ['estado_formatado'];

}