<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fornecedor extends Model
{
    use HasFactory;
    protected $table = 'fornecedores';
   
    protected $fillable = [
        'nome',
        'contacto',
        'email',
        'endereco',
        'tipo_parceria',
        'observacoes'
    ];

    public function encomendas()
    {
        return $this->hasMany(Encomenda::class);
    }

    public function produtos()
    {
        return $this->hasMany(Produto::class);
    }

    public function editarDados($novoNome, $novoContacto, $novoEmail)
    {
        $this->nome = $novoNome;
        $this->contacto = $novoContacto;
        $this->email = $novoEmail;
        return $this->save();
    }
    public function tiposStock()
    {
        return $this->belongsToMany(TipoStock::class, 'fornecedor_tipo_stock');
    }
}