<?php

namespace App\Http\Controllers\Gestor;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use App\Models\Localizacao;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Tarefa;
use Illuminate\Support\Facades\Auth;

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

       
        $localizacao = Localizacao::firstOrCreate([
            'piso' => $request->piso,
            'corredor' => $request->corredor,
            'prateleira' => $request->prateleira,
        ]);

        
        if ($localizacao->produtos()->exists() && !$localizacao->is_empty) {
            return back()->withInput()->withErrors([
                'localizacao' => 'Esta localização já está ocupada por outro produto!'
            ]);
        }

    
        $produto = Produto::create([
            'nome' => $request->nome,
            'tipo_stock_id' => $request->tipo_stock_id,
            'quantidade' => $request->quantidade,
            'localizacao_id' => $localizacao->id,
            
        ]);

        Tarefa::create([
            'titulo' => 'Novo Produto Criado - ' . $produto->nome,
            'descricao' => "Detalhes do Produto:\n" .
                        "ID: " . $produto->id . "\n" .
                        "Nome: " . $produto->nome . "\n" .
                        "Tipo de Stock: " . $tipoStock->nome . " (ID: " . $tipoStock->id . ")\n" .
                        "Quantidade: " . $produto->quantidade . "\n" .
                        "Localização: Piso " . $localizacao->piso . 
                        ", Corredor " . $localizacao->corredor . 
                        ", Prateleira " . $localizacao->prateleira . "\n" .
                        "Criado por: " . Auth::user()->name . "\n" .
                        "Data de Criação: " . now()->format('Y-m-d H:i:s'),
            'estado' => 'Em_Progresso',
            'tipo' => 'Armazenar',
            'gestor_id' => auth()->id()
        ]);

        
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


   
        $newLocation = Localizacao::firstOrCreate([
            'piso' => $request->piso,
            'corredor' => $request->corredor,
            'prateleira' => $request->prateleira,
        ]);
        $existingProduct = $newLocation->produtos()
        ->where('id', '!=', $produto->id)
        ->first();

    
        if ($existingProduct && !$newLocation->is_empty) {
            return back()->withInput()->withErrors([
                'localizacao' => "Esta localização já está ocupada pelo produto ID: {$existingProduct->nome}"
            ]);
        }



        if ($produto->localizacao_id != $newLocation->id) {
            $oldLocation = $produto->localizacao;
            if ($oldLocation) {
                $oldLocation->update(['is_empty' => true]);
            }
        }


        $produto->update([
            'nome' => $request->nome,
            'tipo_stock_id' => $request->tipo_stock_id,
            'quantidade' => $request->quantidade,
            'localizacao_id' => $newLocation->id,
        ]);

        Tarefa::create([
            'titulo' => 'Novo Produto Criado - ' . $produto->nome,
            'descricao' => "Detalhes do Produto:\n" .
                        "ID: " . $produto->id . "\n" .
                        "Nome: " . $produto->nome . "\n" .
                        "Tipo de Stock: " . $tipoStock->nome . " (ID: " . $tipoStock->id . ")\n" .
                        "Quantidade: " . $produto->quantidade . "\n" .
                        "Localização: Piso " . $localizacao->piso . 
                        ", Corredor " . $localizacao->corredor . 
                        ", Prateleira " . $localizacao->prateleira . "\n" .
                        "Criado por: " . Auth::user()->name . "\n" .
                        "Data de Criação: " . now()->format('Y-m-d H:i:s'),
            'estado' => 'Em_Progresso',
            'tipo' => 'Armazenar',
            'gestor_id' => auth()->id()
        ]);



     
        $newLocation->update(['is_empty' => false]);

        return redirect()->route('gestor.produtos.index')
                         ->with('success', 'Produto atualizado com sucesso!');
    }

    public function destroy(Produto $produto)
    {

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


    $newLocation = Localizacao::firstOrCreate([
        'piso' => $request->piso,
        'corredor' => $request->corredor,
        'prateleira' => $request->prateleira,
    ]);


    $existingProduct = $newLocation->produtos()
        ->where('id', '!=', $produto->id)
        ->first();

    if ($existingProduct) {
        return back()->withInput()->withErrors([
            'localizacao' => "Esta localização já está ocupada pelo produto: {$existingProduct->nome} (ID: {$existingProduct->id})"
        ]);
    }
    $oldLocation = $produto->localizacao;
    $oldLocationDetails = $oldLocation ? 
        "Piso {$oldLocation->piso}, Corredor {$oldLocation->corredor}, Prateleira {$oldLocation->prateleira}" : 
        "Sem localização anterior";

    if ($produto->localizacao_id != $newLocation->id) {
        $oldLocation = $produto->localizacao;
        if ($oldLocation) {
            $oldLocation->update(['is_empty' => true]);
        }
    }


    $produto->update([
        'localizacao_id' => $newLocation->id,
    ]);
    Tarefa::create([
        'titulo' => "Movimentação de Produto - {$produto->nome}",
        'descricao' => "Detalhes da Movimentação:\n" .
                      "Produto: {$produto->nome} (ID: {$produto->id})\n" .
                      "Localização Anterior: {$oldLocationDetails}\n" .
                      "Nova Localização: Piso {$newLocation->piso}, Corredor {$newLocation->corredor}, Prateleira {$newLocation->prateleira}\n" ,
        'estado' => 'Em_Progresso',
        'tipo' => 'Mover',
        'gestor_id' => auth()->id()
    ]);

    //
    $newLocation->update(['is_empty' => false]);
    
        return redirect()->route('gestor.produtos.map')
            ->with('success', "Produto '{$produto->nome}' movido com sucesso para Piso {$newLocation->piso}, Corredor {$newLocation->corredor}, Prateleira {$newLocation->prateleira}.");
    }

 

}