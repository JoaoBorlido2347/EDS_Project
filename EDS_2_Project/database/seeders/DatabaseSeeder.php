<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Fornecedor;
use App\Models\Localizacao;
use App\Models\TipoStock;
use App\Models\Produto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Stock Types
        $tipoStockMP = TipoStock::create(['nome' => 'Matéria-Prima']);
        $tipoStockPA = TipoStock::create(['nome' => 'Produto Acabado']);
        $tipoStockEM = TipoStock::create(['nome' => 'Embalagem']);
        $fornecedores = [
            [
                'nome' => 'Fornecedor A',
                'email' => 'fornecedorA@example.com',
                'contacto' => '912345678',
                'endereco' => 'Rua do Fornecedor, 123',
                'tipo_parceria' => 'FORNECEDOR',
                'observacoes' => 'Fornecedor principal',
                'tipos_stock' => [$tipoStockMP->id, $tipoStockPA->id],
            ],
            [
                'nome' => 'Fornecedor B',
                'email' => 'fornecedorB@example.com',
                'contacto' => '923456789',
                'endereco' => 'Avenida dos Fornecedores, 456',
                'tipo_parceria' => 'PARCEIRO',
                'observacoes' => 'Fornecedor secundário',
                'tipos_stock' => [$tipoStockEM->id],
            ],
            [
                'nome' => 'Fornecedor C',
                'email' => 'fornecedorC@example.com',
                'contacto' => '933333333',
                'endereco' => 'Travessa dos Insumos, 789',
                'tipo_parceria' => 'FORNECEDOR',
                'observacoes' => 'Especializado em matéria-prima',
                'tipos_stock' => [$tipoStockMP->id],
            ],
            [
                'nome' => 'Fornecedor D',
                'email' => 'fornecedorD@example.com',
                'contacto' => '944444444',
                'endereco' => 'Alameda das Embalagens, 321',
                'tipo_parceria' => 'PARCEIRO',
                'observacoes' => 'Parceiro estratégico para embalagens',
                'tipos_stock' => [$tipoStockEM->id, $tipoStockPA->id],
            ],
        ];

        foreach ($fornecedores as $dados) {
            $fornecedor = Fornecedor::create([
                'nome' => $dados['nome'],
                'email' => $dados['email'],
                'contacto' => $dados['contacto'],
                'endereco' => $dados['endereco'],
                'tipo_parceria' => $dados['tipo_parceria'],
                'observacoes' => $dados['observacoes'],
            ]);

            $fornecedor->tiposStock()->attach($dados['tipos_stock']);
        }

        // Create all possible locations
        foreach (Localizacao::$allowedPisos as $piso) {
            foreach (Localizacao::$allowedCorredores as $corredor) {
                foreach (Localizacao::$allowedPrateleiras as $prateleira) {
                    Localizacao::create([
                        'piso' => $piso,
                        'corredor' => $corredor,
                        'prateleira' => $prateleira,
                        'is_empty' => true
                    ]);
                }
            }
        }

        // Create sample products
        $localizacao = Localizacao::first();
        Produto::create([
            'nome' => 'Produto Alpha',
            'tipo_stock_id' => $tipoStockPA->id,
            'quantidade' => 100,
            'localizacao_id' => $localizacao->id,
            'esgotado' => false
        ]);

        Produto::create([
            'nome' => 'Material Beta',
            'tipo_stock_id' => $tipoStockMP->id,
            'quantidade' => 50,
            'localizacao_id' => $localizacao->id,
            'esgotado' => false
        ]);
            Produto::create([
            'nome' => 'Embalagem Gamma',
            'tipo_stock_id' => $tipoStockMP->id,
            'quantidade' => 0,
            'localizacao_id' => $localizacao->id,
            'esgotado' => true
        ]);


        // Create users with proper telefone handling
        User::create([
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'password' =>('password123'),
            'telefone' => '123456789',
            'role' => 'administrador',
        ]);

        User::create([
            'name' => 'Test Manager',
            'email' => 'gestor@example.com',
            'password' => ('password123'),
            'telefone' => '987654321',
            'role' => 'gestor',
        ]);

        User::create([
            'name' => 'Test Employee',
            'email' => 'funcionario@example.com',
            'password' => ('password123'),
            'telefone' => '555555555', // Will be stored as-is
            'role' => 'funcionario',
        ]);

        // Create user with empty phone number
        User::create([
            'name' => 'No Phone User',
            'email' => 'nophone@example.com',
            'password' => ('password123'),
            'telefone' => '', // Will be stored as #Null#
            'role' => 'funcionario',
        ]);
    }
}