<?php

namespace App\Http\Controllers;

use App\Models\Localizacao;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FuncionarioController extends Controller
{
    public function dashboard()
    {
        return view('funcionario.dashboard', [
            'user' => Auth::user()
        ]);
    }
        public function mapa()
    {
        $localizacoes = Localizacao::with('produtos')->get();
        $allowedPisos = Localizacao::$allowedPisos;
        $allowedCorredores = Localizacao::$allowedCorredores;
        $allowedPrateleiras = Localizacao::$allowedPrateleiras;

        $locationsGrid = [];
        foreach ($localizacoes as $loc) {
            $locationsGrid[$loc->piso][$loc->corredor][$loc->prateleira] = $loc;
        }

        return view('funcionario.mapa', compact(
            'localizacoes',
            'allowedPisos',
            'allowedCorredores',
            'allowedPrateleiras',
            'locationsGrid'
        ));
    }

    public function atualizarQuantidade(Request $request, Produto $produto)
    {
        $validated = $request->validate([
            'quantidade' => 'required|integer|min:0',
        ]);

        $produto->update([
            'quantidade' => $validated['quantidade'],
            'esgotado'   => $validated['quantidade'] <= 0,
        ]);

        return redirect()->route('funcionario.mapa')
                         ->with('success', 'Changed!');
        
    }

    public function tarefas()
    {
        $user = Auth::user();
        $tarefas = $user->tarefasAtribuidas()->with('gestor')->get();
        
        return view('funcionario.tarefas', [
            'tarefas' => $tarefas,
            'user' => $user
        ]);
    }
    public function concluirTarefa(Tarefa $tarefa)
    {
        // Verify user is assigned to this task
        if (!$tarefa->funcionarios->contains(Auth::id())) {
            abort(403, 'Unauthorized action.');
        }

        $tarefa->update(['estado' => 'Concluida']);

        return redirect()->route('funcionario.tarefas')
                        ->with('success', 'Tarefa marcada como concluída!');
    }

}