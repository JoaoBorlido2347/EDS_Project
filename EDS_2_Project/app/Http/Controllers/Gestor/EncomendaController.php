<?php
namespace App\Http\Controllers\Gestor;

use App\Http\Controllers\Controller;
use App\Models\Encomenda;
use App\Models\Produto;
use App\Models\TipoStock;
use App\Models\Localizacao;
use App\Models\Fornecedor;
use App\Models\Tarefa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class EncomendaController extends Controller
{

    public function index()
    {
        $encomendas = Encomenda::with('fornecedor')
            ->where('gestor_id', Auth::id())
            ->orderBy('data', 'desc')
            ->get();

        return view('gestor.encomendas.index', compact('encomendas'));
    }


    public function create()
    {
        $fornecedores = Fornecedor::all();
        $tiposStock = TipoStock::all();
        $today = now()->format('Y-m-d'); 
        
        return view('gestor.encomendas.create', compact('fornecedores', 'tiposStock', 'today'));
    }

    public function tiposPorFornecedor($fornecedorId)
    {
        $fornecedor = Fornecedor::with('tiposStock')->findOrFail($fornecedorId);
        return response()->json($fornecedor->tiposStock);
    }

    public function store(Request $request)
    {
        $request->validate([
            'data' => 'required|date',
            'fornecedor_id' => 'required|exists:fornecedores,id',
            'itens' => 'required|array|min:1',
            'itens.*.tipo_stock_id' => 'required|exists:tipo_stocks,id',
            'itens.*.stock_quantity' => 'required|integer|min:1',
        ]);

        $encomenda = Encomenda::create([
            'data' => $request->data,
            'estado' => 'Pendente',
            'fornecedor_id' => $request->fornecedor_id,
            'gestor_id' => auth()->id()
        ]);

        foreach ($request->itens as $item) {
            $encomenda->tipoStocks()->attach($item['tipo_stock_id'], [
                'stock_quantity' => $item['stock_quantity']  
            ]);
        }

        $stockDetails = '';
            foreach ($request->itens as $item) {
                $encomenda->tipoStocks()->attach($item['tipo_stock_id'], [
                    'stock_quantity' => $item['stock_quantity']
                ]);
                
                $tipoStock = TipoStock::find($item['tipo_stock_id']);
                $stockDetails .= "Tipo: " . $tipoStock->nome . ", Quantidade: " . $item['stock_quantity'] . "\n";
            }

            $fornecedor = Fornecedor::find($request->fornecedor_id);
            
            Tarefa::create([
                'titulo' => 'Encomenda  - ID: ' . $encomenda->id,
                'descricao' => "Detalhes da Encomenda:\n" .
                            "ID: " . $encomenda->id . "\n" .
                            "Data: " . $encomenda->data . "\n" .
                            "Fornecedor: " . $fornecedor->nome . " (ID: " . $fornecedor->nome . ")\n" .
                            "Gestor Responsável: " . Auth::user()->name . "\n" .
                            "Itens Encomendados:\n" . $stockDetails . "\n" .
                            "Estado: Pendente",
                'estado' => 'Em_Progresso',
                'tipo' => 'Receber',
                'gestor_id' => Auth::id()
            ]);
                
        return redirect()->route('gestor.encomendas.index')
            ->with('success', 'Encomenda criada com sucesso!');
    }

  public function updateEstado(Request $request, $id)
    {
        $encomenda = Encomenda::findOrFail($id);

        
        if ($encomenda->gestor_id !== Auth::id()) {
            abort(403, 'Você não tem permissão para atualizar esta encomenda.');
        }

   
        $request->validate(['estado' => 'required|in:Recebida,Cancelada']);

        if (!$encomenda->podeSerAlterada()) {
            return back()->withErrors(['error' => 'Encomenda não pode ser alterada']);
        }

        $encomenda->update(['estado' => $request->estado]);


        if ($request->estado === 'Recebida') {
            foreach ($encomenda->tipoStocks as $item) {
                $tipoStockId = $item->id;
                $quantityToAdd = $item->pivot->stock_quantity;

   
                $produto = Produto::where('tipo_stock_id', $tipoStockId)->first();

                if ($produto) {
                
                    $produto->quantidade += $quantityToAdd;
                    $produto->save();
                } else {
                    $emptyLocation = Localizacao::where('is_empty', true)->first();

                    if ($emptyLocation) {
                        
                        $tipoStock = TipoStock::find($tipoStockId);

                        
                        $newProduto = Produto::create([
                            'nome' => $tipoStock->nome ?? 'Produto ' . $tipoStockId, 
                            'tipo_stock_id' => $tipoStockId,
                            'quantidade' => $quantityToAdd,
                            'localizacao_id' => $emptyLocation->id,
                        ]);

               
                        $emptyLocation->update(['is_empty' => false]);
                    } else {

                        \Log::warning("No empty location available for tipo_stock_id: {$tipoStockId}");
                    }
                }
            }
        }

        return redirect()->route('gestor.encomendas.index')
                         ->with('success', 'Estado atualizado com sucesso!');
    }

}