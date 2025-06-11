<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Localizacao extends Model
{
    use HasFactory;
    
    protected $table = 'localizacoes';
    
    protected $fillable = [
        'piso',
        'corredor',
        'prateleira',
        'is_empty'
    ];

    public function produtos()
    {
        return $this->hasMany(Produto::class);
    }

    public function getNomeCompletoAttribute()
    {
        return "Piso {$this->piso}, Corredor {$this->corredor}, Prateleira {$this->prateleira}";
    }
    
    public static $allowedPisos = ['1', '2', '3', '4', '5'];
    public static $allowedCorredores = ['+', '-'];
    public static $allowedPrateleiras = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];


}