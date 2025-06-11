<?php
namespace App\Http\Controllers\Gestor;

use App\Http\Controllers\Controller;
use App\Models\Encomenda;
use App\Models\Produto;
use App\Models\TipoStock;
use App\Models\Fornecedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class EncomendaController extends Controller
{
    // Listar encomendas
    public function index()
    {
        $encomendas = Encomenda::with('fornecedor')
            ->where('gestor_id', Auth::id())
            ->orderBy('data', 'desc')
            ->get();

        return view('gestor.encomendas.index', compact('encomendas'));
    }

    // Mostrar formulário de criação

    public function create()
    {
        $fornecedores = Fornecedor::all();
        $tiposStock = TipoStock::all();
        $today = now()->format('Y-m-d'); // Add this line
        
        return view('gestor.encomendas.create', compact('fornecedores', 'tiposStock', 'today'));
    }

    public function tiposPorFornecedor($fornecedorId)
    {
        $fornecedor = Fornecedor::with('tiposStock')->findOrFail($fornecedorId);
        return response()->json($fornecedor->tiposStock);
    }
    // Armazenar nova encomenda
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
            'gestor_id' => Auth::id()
        ]);

        foreach ($request->itens as $item) {
            $encomenda->tipoStocks()->attach($item['tipo_stock_id'], [
                'stock_quantity' => $item['stock_quantity']  // Use requested quantity
            ]);
        }

        return redirect()->route('gestor.encomendas.index')
            ->with('success', 'Encomenda criada com sucesso!');
    }
    // Atualizar estado da encomenda
// app/Http/Controllers/Gestor/EncomendaController.php
    public function updateEstado(Request $request, $id) // Use $id instead of Encomenda $encomenda
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