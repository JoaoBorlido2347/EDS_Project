<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GestorController;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FornecedorController;
use App\Http\Controllers\gestor\TipoStockController;
use App\Http\Controllers\gestor\ProdutoController;
// Authentication routes
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login.show');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware(['auth', 'role:administrador'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/fornecedores', [FornecedorController::class, 'index'])->name('admin.fornecedores.index');
    Route::get('/fornecedores/create', [FornecedorController::class, 'create'])->name('admin.fornecedores.create');
    Route::post('/fornecedores', [FornecedorController::class, 'store'])->name('admin.fornecedores.store');
    Route::get('/fornecedores/{id}/edit', [FornecedorController::class, 'edit'])->name('admin.fornecedores.edit');
    Route::put('/fornecedores/{id}', [FornecedorController::class, 'update'])->name('admin.fornecedores.update');
    Route::delete('/fornecedores/{id}', [FornecedorController::class, 'destroy'])->name('admin.fornecedores.destroy');
});

Route::middleware(['auth', 'role:gestor'])->prefix('gestor')->group(function () {
    Route::get('/dashboard', [GestorController::class, 'dashboard'])->name('gestor.dashboard');
});

Route::middleware(['auth', 'role:funcionario'])->prefix('funcionario')->group(function () {
    Route::get('/dashboard', [FuncionarioController::class, 'dashboard'])->name('funcionario.dashboard');
});

// Show the form to create a new user
Route::get('/users/create', [UserController::class, 'create'])
     ->name('admin.users.create');

// Handle the POST from the create form
Route::post('/users', [UserController::class, 'store'])
     ->name('admin.users.store');

// Show the form to edit an existing user
Route::get('/users/{id}/edit', [UserController::class, 'edit'])
     ->name('admin.users.edit');

// Handle the PUT/PATCH from the edit form
Route::put('/users/{id}', [UserController::class, 'update'])
     ->name('admin.users.update');
// Handle the DELETE request to delete a user
Route::delete('/users/{id}', [UserController::class, 'destroy'])
     ->name('admin.users.destroy');


Route::middleware(['auth', 'role:gestor'])->prefix('gestor')->group(function () {
    Route::get('/dashboard', [GestorController::class, 'dashboard'])->name('gestor.dashboard');

    Route::resource('produtos', \App\Http\Controllers\Gestor\ProdutoController::class)
    ->except(['show'])
    ->names([
            'index' => 'gestor.produtos.index',
            'create' => 'gestor.produtos.create',
            'store' => 'gestor.produtos.store',
            'edit' => 'gestor.produtos.edit',
            'update' => 'gestor.produtos.update',
            'destroy' => 'gestor.produtos.destroy'
        ]);
    Route::resource('tarefas', \App\Http\Controllers\Gestor\TarefaController::class)
    ->except(['show'])
    ->names([
            'index' => 'gestor.tarefas.index',
            'create' => 'gestor.tarefas.create',
            'store' => 'gestor.tarefas.store',
            'edit' => 'gestor.tarefas.edit',
            'update' => 'gestor.tarefas.update',
            'destroy' => 'gestor.tarefas.destroy'
        ]);

    Route::resource('tipos-stock', \App\Http\Controllers\Gestor\TipoStockController::class)
        ->except(['edit'])
        ->names([
            'index' => 'gestor.tipos-stock.index',
            'create' => 'gestor.tipos-stock.create',
            'store' => 'gestor.tipos-stock.store',
            'update' => 'gestor.tipos-stock.update',
            'destroy' => 'gestor.tipos-stock.destroy'
        ]);
    Route::get('/tarefas/{tarefa}/funcionarios', [\App\Http\Controllers\Gestor\TarefaController::class, 'getFuncionarios'])
        ->name('gestor.tarefas.funcionarios');

    Route::get('tipos-stock/edit/{id}', [\App\Http\Controllers\Gestor\TipoStockController::class, 'edit'])
    ->name('gestor.tipos-stock.edit');
 
  Route::get('encomendas/tipos-por-fornecedor/{fornecedorId}', 
        [\App\Http\Controllers\Gestor\EncomendaController::class, 'tiposPorFornecedor']
    )->name('encomendas.tipos');

    Route::get('/encomendas', [\App\Http\Controllers\Gestor\EncomendaController::class, 'index'])
        ->name('gestor.encomendas.index');
        
    Route::get('/encomendas/create', [\App\Http\Controllers\Gestor\EncomendaController::class, 'create'])
        ->name('gestor.encomendas.create');
        
    Route::post('/encomendas', [\App\Http\Controllers\Gestor\EncomendaController::class, 'store'])
        ->name('gestor.encomendas.store');
    
     Route::post('/encomendas/{encomenda}/estado', [\App\Http\Controllers\Gestor\EncomendaController::class, 'updateEstado'])
    ->name('gestor.encomendas.update-estado');

    Route::get('/produtos/map', [ProdutoController::class, 'map'])->name('gestor.produtos.map');
    Route::patch('/produtos/{produto}/move', [ProdutoController::class, 'move'])->name('gestor.produtos.move');
});


    
Route::middleware(['auth', 'role:funcionario'])->prefix('funcionario')->group(function () {
    Route::get('/dashboard', [FuncionarioController::class, 'dashboard'])->name('funcionario.dashboard');
    Route::get('/mapa', [FuncionarioController::class, 'mapa'])->name('funcionario.mapa');
    Route::patch('/produtos/{produto}/quantidade', [FuncionarioController::class, 'atualizarQuantidade'])->name('funcionario.produtos.atualizarQuantidade');
    Route::get('/tarefas', [FuncionarioController::class, 'tarefas'])->name('funcionario.tarefas');
    Route::patch('/tarefas/{tarefa}/concluir', [FuncionarioController::class, 'concluirTarefa'])->name('funcionario.tarefas.concluir');
});

Route::get('/api/fornecedores/{fornecedor}/tipos-stock', 
    [App\Http\Controllers\Gestor\EncomendaController::class, 'tiposPorFornecedor'])
    ->name('api.fornecedores.tipos-stock');