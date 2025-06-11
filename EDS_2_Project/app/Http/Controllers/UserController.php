<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
       
        return view('admin.users.create');
    }


    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }


    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'name'     => 'required|string|max:255',
            'email'    => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id)
            ],
            'password' => 'nullable|string|min:6|confirmed',
            'role'     => 'required|string|in:administrador,gestor,funcionario',
            'telefone' => 'nullable|string|max:20',
        ];

        $data = $request->validate($rules);


        if ($request->filled('password')) {
            $data['password'] = $request->password; 
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
                        ->with('success', 'Utilizador atualizado com sucesso!');
    }

    public function destroy($id)
    {
        User::destroy($id);
        return redirect()->route('admin.users.index')
                         ->with('success', 'Utilizador eliminado com sucesso!');
    }


    public function store(Request $request)
    {

        $validated = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            'telefone'              => ['nullable', 'string', 'max:20'],
            'role'                  => ['required', Rule::in(['administrador','gestor','funcionario'])],
        ]);


        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => $validated['password'],
            'telefone' => $validated['telefone'] ?? null,
            'role'     => $validated['role'],
  
        ]);

        return redirect()->route('admin.users.index')
                         ->with('success', 'Utilizador criado com sucesso.');
    }
    
}
