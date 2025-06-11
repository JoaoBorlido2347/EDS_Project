<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <form method="POST" action="{{ route('login') }}"> {{-- Now correctly references 'login' --}}
        @csrf
        <input type="email" name="email" placeholder="Email" required autofocus>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
        
        @if($errors->any())
            <div style="color: red;">
                {{ $errors->first() }}
            </div>
        @endif
    </form>
    <p>
              DB::table('users')->insert([
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'telefone' => '123456789',
            'role' => 'administrador',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
  </p>  <p>
        DB::table('users')->insert([
            'name' => 'Test Manager',
            'email' => 'gestor@example.com',
            'password' => Hash::make('password123'),
            'telefone' => '987654321',
            'role' => 'gestor',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
  </p>  <p>
        DB::table('users')->insert([
            'name' => 'Test Employee',
            'email' => 'funcionario@example.com',
            'password' => Hash::make('password123'),
            'telefone' => '555555555',
            'role' => 'funcionario',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    </p>
</body>
</html>