<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Encomenda extends Model
{
    protected $fillable = ['data', 'estado', 'fornecedor_id', 'gestor_id'];

    protected $casts = [
        'data' => 'datetime',
    ];

    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class);
    }

    public function gestor()
    {
        return $this->belongsTo(User::class, 'gestor_id');
    }

    public function produtos()
    {
        return $this->belongsToMany(Produto::class, 'encomenda_items')
                    ->withPivot(['quantidade', 'tipo_stock_id', 'stock_quantity']);
    }
        public function podeSerAlterada()
    {
        return $this->estado === 'Pendente';
    }
    const ESTADOS = ['Pendente', 'Recebida', 'Cancelada'];
    public function tipoStocks()
    {
        return $this->belongsToMany(TipoStock::class, 'encomenda_tipo_stock')
                    ->withPivot(['stock_quantity']) // keep quantidade if needed
                    ->withTimestamps();
    }
    public function updateEstado(Request $request, $id)
{
    $encomenda = Encomenda::findOrFail($id);
    
    if ($encomenda->gestor_id !== Auth::id()) {
        abort(403);
    }

    $request->validate(['estado' => 'required|in:Recebida,Cancelada']);

    if (!$encomenda->podeSerAlterada()) {
        return back()->withErrors(['error' => 'Encomenda não pode ser alterada']);
    }

    $encomenda->update(['estado' => $request->estado]);

    return redirect()->route('gestor.encomendas.index')
        ->with('success', 'Estado atualizado!');
}
}