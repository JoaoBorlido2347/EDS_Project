<?php

namespace App\Http\Controllers\Gestor;

use App\Http\Controllers\Controller;
use App\Models\Tarefa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TarefaController extends Controller
{
    public function index()
    {
        $tarefas = Tarefa::with('gestor', 'funcionarios')->get();
        return view('gestor.tarefas.index', compact('tarefas'));
    }

    public function create()
    {
        $funcionarios = User::where('role', 'funcionario')->get();
        return view('gestor.tarefas.create', compact('funcionarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'tipo' => 'required|in:Receber,Mover,Enviar,Armazenar',
            'funcionarios' => 'nullable|array',
            'funcionarios.*' => 'exists:users,id'
        ]);

        $tarefa = Tarefa::create([
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'estado' => 'Em_Progresso',
            'tipo' => $request->tipo,
            'gestor_id' => auth()->id()
        ]);

        if ($request->has('funcionarios')) {
            $tarefa->funcionarios()->attach($request->funcionarios);
        }

        return redirect()->route('gestor.tarefas.index')->with('success', 'Tarefa criada com sucesso!');
    }

    public function edit(Tarefa $tarefa)
    {
        $funcionarios = User::where('role', 'funcionario')->get();
        $assignedFuncionarios = $tarefa->funcionarios->pluck('id')->toArray();
        
        return view('gestor.tarefas.edit', compact('tarefa', 'funcionarios', 'assignedFuncionarios'));
    }

    public function update(Request $request, Tarefa $tarefa)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'estado' => 'required|in:Em_Progresso, Concluida',
            'tipo' => 'required|in:Receber,Mover,Enviar,Armazenar,Verificar',
            'funcionarios' => 'nullable|array',
            'funcionarios.*' => 'exists:users,id'
        ]);

        $tarefa->update($request->only(['titulo', 'descricao', 'estado', 'tipo']));

        $tarefa->funcionarios()->sync($request->funcionarios ?? []);

        return redirect()->route('gestor.tarefas.index')->with('success', 'Tarefa atualizada com sucesso!');
    }

    public function destroy(Tarefa $tarefa)
    {
        $tarefa->funcionarios()->detach();
        $tarefa->delete();
        
        return redirect()->route('gestor.tarefas.index')->with('success', 'Tarefa eliminada com sucesso!');
    }
    public function getFuncionarios($tarefaId)
    {
        $tarefa = Tarefa::findOrFail($tarefaId);
        
        if ($tarefa->gestor_id !== Auth::id()) {
            abort(403, 'Você não tem permissão para visualizar os funcionários desta tarefa.');
        }
        
        $funcionarios = $tarefa->funcionarios()->select('users.id', 'users.name')->get();
        
        return response()->json($funcionarios);
    }
}