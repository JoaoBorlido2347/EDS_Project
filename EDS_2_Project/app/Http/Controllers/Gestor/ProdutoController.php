<?php

namespace App\Http\Controllers\Gestor;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use App\Models\Localizacao;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
   public function index()
    {
        $produtos = Produto::with(['tipoStock', 'localizacao'])->paginate(10);
        return view('gestor.produtos.index', compact('produtos'));
    }
    public function create()
    {
        return view('gestor.produtos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'tipo_stock_id' => 'required|exists:tipo_stocks,id',
            'quantidade' => 'required|integer|min:0',
            'piso' => 'required|in:'.implode(',', Localizacao::$allowedPisos),
            'corredor' => 'required|in:'.implode(',', Localizacao::$allowedCorredores),
            'prateleira' => 'required|in:'.implode(',', Localizacao::$allowedPrateleiras),
        ]);

        // Find or create location
        $localizacao = Localizacao::firstOrCreate([
            'piso' => $request->piso,
            'corredor' => $request->corredor,
            'prateleira' => $request->prateleira,
        ]);

        // Check if location is occupied by another product
        if ($localizacao->produtos()->exists() && !$localizacao->is_empty) {
            return back()->withInput()->withErrors([
                'localizacao' => 'Esta localização já está ocupada por outro produto!'
            ]);
        }

        // Create product
        $produto = Produto::create([
            'nome' => $request->nome,
            'tipo_stock_id' => $request->tipo_stock_id,
            'quantidade' => $request->quantidade,
            'localizacao_id' => $localizacao->id,
            
        ]);

        // Update location status
        $localizacao->update(['is_empty' => false]);

        return redirect()->route('gestor.produtos.index')
                         ->with('success', 'Produto criado com sucesso!');
    }

    public function edit(Produto $produto)
    {
        return view('gestor.produtos.edit', compact('produto'));
    }

    public function update(Request $request, Produto $produto)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'tipo_stock_id' => 'required|exists:tipo_stocks,id',
            'quantidade' => 'required|integer|min:0',
            'piso' => 'required|in:'.implode(',', Localizacao::$allowedPisos),
            'corredor' => 'required|in:'.implode(',', Localizacao::$allowedCorredores),
            'prateleira' => 'required|in:'.implode(',', Localizacao::$allowedPrateleiras),
        ]);


        // Find or create new location
        $newLocation = Localizacao::firstOrCreate([
            'piso' => $request->piso,
            'corredor' => $request->corredor,
            'prateleira' => $request->prateleira,
        ]);
        $existingProduct = $newLocation->produtos()
        ->where('id', '!=', $produto->id)
        ->first();

        // Check if new location is occupied by another product
        if ($existingProduct && !$newLocation->is_empty) {
            return back()->withInput()->withErrors([
                'localizacao' => "Esta localização já está ocupada pelo produto ID: {$existingProduct->nome}"
            ]);
        }


        // Free up old location if changed
        if ($produto->localizacao_id != $newLocation->id) {
            $oldLocation = $produto->localizacao;
            if ($oldLocation) {
                $oldLocation->update(['is_empty' => true]);
            }
        }

        // Update product
        $produto->update([
            'nome' => $request->nome,
            'tipo_stock_id' => $request->tipo_stock_id,
            'quantidade' => $request->quantidade,
            'localizacao_id' => $newLocation->id,
        ]);

        // Update new location status
        $newLocation->update(['is_empty' => false]);

        return redirect()->route('gestor.produtos.index')
                         ->with('success', 'Produto atualizado com sucesso!');
    }

    public function destroy(Produto $produto)
    {
        // Free up location before deletion
        if ($produto->localizacao) {
            $produto->localizacao->update(['is_empty' => true]);
        }

        $produto->delete();

        return redirect()->route('gestor.produtos.index')
                         ->with('success', 'Produto removido com sucesso!');
    }
    public function map()
    {
        $localizacoes = Localizacao::with('produtos')->get();
        $allowedPisos = Localizacao::$allowedPisos;
        $allowedCorredores = Localizacao::$allowedCorredores;
        $allowedPrateleiras = Localizacao::$allowedPrateleiras;
        
        // Build location grid
        $locationsGrid = [];
        foreach ($localizacoes as $loc) {
            $locationsGrid[$loc->piso][$loc->corredor][$loc->prateleira] = $loc;
        }
        
        return view('gestor.produtos.map', compact(
            'localizacoes',
            'allowedPisos',
            'allowedCorredores',
            'allowedPrateleiras',
            'locationsGrid'
        ));
    }


public function move(Request $request, Produto $produto)
{
    $validated = $request->validate([
        'piso' => 'required|in:'.implode(',', Localizacao::$allowedPisos),
        'corredor' => 'required|in:'.implode(',', Localizacao::$allowedCorredores),
        'prateleira' => 'required|in:'.implode(',', Localizacao::$allowedPrateleiras),
    ]);

    // Find or create new location
    $newLocation = Localizacao::firstOrCreate([
        'piso' => $request->piso,
        'corredor' => $request->corredor,
        'prateleira' => $request->prateleira,
    ]);

    // Check if new location is occupied by another product
    $existingProduct = $newLocation->produtos()
        ->where('id', '!=', $produto->id)
        ->first();

    if ($existingProduct) {
        return back()->withInput()->withErrors([
            'localizacao' => "Esta localização já está ocupada pelo produto: {$existingProduct->nome} (ID: {$existingProduct->id})"
        ]);
    }

    // Free up old location if changed
    if ($produto->localizacao_id != $newLocation->id) {
        $oldLocation = $produto->localizacao;
        if ($oldLocation) {
            $oldLocation->update(['is_empty' => true]);
        }
    }

    // Update product location
    $produto->update([
        'localizacao_id' => $newLocation->id,
    ]);

    // Update new location status
    $newLocation->update(['is_empty' => false]);
    
        return redirect()->route('gestor.produtos.map')
            ->with('success', "Produto '{$produto->nome}' movido com sucesso para Piso {$newLocation->piso}, Corredor {$newLocation->corredor}, Prateleira {$newLocation->prateleira}.");
    }

}