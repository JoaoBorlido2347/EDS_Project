<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'tipo_stock_id',
        'quantidade',
        'localizacao_id',
        'esgotado'
    ];

    protected $casts = [
    'esgotado' => 'boolean'
    ];

    protected static function booted()
    {
        static::saving(function ($produto) {
            $produto->esgotado = $produto->quantidade <= 0;
        });
    }
    
    public function localizacao()
    {
        return $this->belongsTo(Localizacao::class);
    }

    public function tarefas()
    {
        return $this->belongsToMany(Tarefa::class, 'tarefa_produto')
                    ->withPivot('quantidade')
                    ->withTimestamps();
    }

    public function encomendas()
    {
        return $this->belongsToMany(Encomenda::class, 'encomenda_produto')
                    ->withPivot('quantidade')
                    ->withTimestamps();
    }

    public function atualizarQuantidade($novaQtd)
    {
        $this->quantidade = $novaQtd;
        return $this->save();
    }

    public function definirLocalizacao($localizacao)
    {
        $this->localizacao_id = $localizacao->id;
        return $this->save();
    }

    public function tipoStock()
    {
        return $this->belongsTo(TipoStock::class, 'tipo_stock_id');
    }

}