<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'telefone',
        #'ativo'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    # protected $attributes = [
   #     'ativo' => false, // Default value for new users
  #   ];
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
     #       'ativo' => 'boolean',
        ];
    }


    public function autenticar($email, $password)
    {
        $user = $this->where('email', $email)->first();
        return $user && Hash::check($password, $user->password);
    }
    public function alterarDados($novoNome, $novoEmail, $novapassword, $novoRole)
    {
        $this->nome = $novoNome;
        $this->email = $novoEmail;
        $this->password = $novapassword;
        $this->role = $novoRole;
        $this->save();
    }

    public function tarefasCriadas()
    {
        return $this->hasMany(Tarefa::class, 'user_id');
    }

    public function tarefasAtribuidas()
    {
        return $this->belongsToMany(Tarefa::class, 'tarefa_funcionario', 'funcionario_id', 'tarefa_id');
    }

    public function encomendas()
    {
        return $this->hasMany(Encomenda::class);
    }

       public function setTelefoneAttribute($value)
    {
        $trimmed = trim($value ?? '');
        $this->attributes['telefone'] = ($trimmed === '') ? '#Null#' : $trimmed;
    }
    
}
