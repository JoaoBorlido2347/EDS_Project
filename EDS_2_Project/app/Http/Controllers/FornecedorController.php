<?php

namespace App\Http\Controllers;

use App\Models\Fornecedor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\TipoStock;

class FornecedorController extends Controller
{
    public function index()
    {
        $fornecedores = Fornecedor::all();
        return view('admin.fornecedores.index', compact('fornecedores'));
    }

    public function create()
    {
        $tiposStock = TipoStock::all();
        return view('admin.fornecedores.create', compact('tiposStock'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'contacto' => 'required|string|max:20',
            'email' => 'required|email|unique:fornecedores,email',
            'endereco' => 'required|string|max:255',
            'tipo_parceria' => 'required|string|max:50',
            'observacoes' => 'nullable|string',
             'tipos_stock' => 'sometimes|array',
             'tipos_stock.*' => 'exists:tipo_stocks,id'
        ]);

        $fornecedor = Fornecedor::create($data);
        $fornecedor->tiposStock()->sync($request->input('tipos_stock', []));

        return redirect()->route('admin.fornecedores.index')
                        ->with('success', 'Fornecedor criado com sucesso!');
    }

    public function edit($id)
    {
        $fornecedor = Fornecedor::with('tiposStock')->findOrFail($id);
        $tiposStock = TipoStock::all();
        $selectedTipos = $fornecedor->tiposStock->pluck('id')->toArray();
        
        return view('admin.fornecedores.edit', compact(
            'fornecedor', 
            'tiposStock', 
            'selectedTipos'
        ));
    }


    public function update(Request $request, $id)
    {
        $fornecedor = Fornecedor::findOrFail($id);

        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'contacto' => 'required|string|max:20',
            'email' => [
                'required',
                'email',
                Rule::unique('fornecedores')->ignore($fornecedor->id)
            ],
            'endereco' => 'required|string|max:255',
            'tipo_parceria' => 'required|string|max:50',
            'observacoes' => 'nullable|string',
            'tipos_stock' => 'sometimes|array',
            'tipos_stock.*' => 'exists:tipo_stocks,id'
        ]);

        $fornecedor->update($data);
        $fornecedor->tiposStock()->sync($request->input('tipos_stock', []));

        return redirect()->route('admin.fornecedores.index')
                        ->with('success', 'Fornecedor atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $fornecedor = Fornecedor::findOrFail($id);
        $fornecedor->delete();

        return redirect()->route('admin.fornecedores.index')
                         ->with('success', 'Fornecedor eliminado com sucesso!');
    }
}