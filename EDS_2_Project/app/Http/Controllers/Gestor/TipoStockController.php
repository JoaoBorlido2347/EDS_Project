<?php

namespace App\Http\Controllers\Gestor;

use App\Http\Controllers\Controller;
use App\Models\TipoStock;
use Illuminate\Http\Request;

class TipoStockController extends Controller
{
    public function index()
    {
        $tiposStock = TipoStock::paginate(10);
        return view('gestor.tipos-stock.index', compact('tiposStock'));
    }

    public function create()
    {
        return view('gestor.tipos-stock.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255|unique:tipo_stocks' // Changed table name
        ]);

        TipoStock::create($request->all());
        return redirect()->route('gestor.tipos-stock.index')
                         ->with('success', 'Tipo de stock criado com sucesso!');
    }


    public function edit($id)
    {
        $tipoStock = TipoStock::findOrFail($id);
        return view('gestor.tipos-stock.edit', compact('tipoStock'));
    }

    public function update(Request $request, $tipos_stock)
    {
        $tipoStock = TipoStock::findOrFail($tipos_stock);
        $validated = $request->validate([
            'nome' => 'required|string|max:255|unique:tipo_stocks,nome,' . $tipoStock->id
        ]);

        $tipoStock->update($validated);

        return redirect()->route('gestor.tipos-stock.index')
                        ->with('success', 'Tipo de stock atualizado com sucesso!');
    }
    public function destroy($tipos_stock)
    {
        $tipoStock = TipoStock::findOrFail($tipos_stock);
        $tipoStock->delete();
        return redirect()->route('gestor.tipos-stock.index')
                         ->with('success', 'Tipo de stock removido com sucesso!');
    }
}