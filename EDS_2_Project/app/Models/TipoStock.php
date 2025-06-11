<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoStock extends Model
{
    use HasFactory;

    protected $table = 'tipo_stocks';
    protected $fillable = ['nome'];

    public function produtos()
    {
        return $this->hasMany(Produto::class);
    }
    public function fornecedores()
    {
      return $this->belongsToMany(Fornecedor::class, 'fornecedor_tipo_stock');
    }

}