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
        // Return a view with a form to create a new user
        return view('admin.users.create');
    }


    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

// Add this method to handle the update request
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

        // Update password only if provided
        if ($request->filled('password')) {
            $data['password'] = $request->password; // Will be hashed by mutator
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

    /**
     * Handle the POST request to store a new user.
     */
    public function store(Request $request)
    {
        // Validate input, including password confirmation:
        $validated = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            'telefone'              => ['nullable', 'string', 'max:20'],
            'role'                  => ['required', Rule::in(['administrador','gestor','funcionario'])],
           # 'ativo'                 => ['required', 'boolean'],
        ]);

        // Laravel’s “confirmed” rule expects a matching field named "password_confirmation".
        // When you call User::create(), the User model’s mutator will hash the password.

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => $validated['password'], // Will be hashed by setPasswordAttribute()
            'telefone' => $validated['telefone'] ?? null,
            'role'     => $validated['role'],
          #  'ativo'    => $validated['ativo'],
        ]);

        // After creating, redirect back to the user list (or wherever you like)
        return redirect()->route('admin.users.index')
                         ->with('success', 'Utilizador criado com sucesso.');
    }
    
}
