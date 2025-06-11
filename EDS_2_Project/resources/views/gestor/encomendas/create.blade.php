<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Nova Encomenda</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
      body {
        background: #0055e8;
        background: linear-gradient(180deg, rgba(0, 85, 232, 1) 0%, rgba(67, 224, 133, 1) 50%, rgba(210, 255, 105, 1) 100%);
        background-attachment: fixed;
        }
        .container {
            max-width: 800px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            margin-top: 40px;
        }
        h2 {
            margin-bottom: 25px;
            color: #333;
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 10px;
        }
        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        .item-row {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border-left: 3px solid #0d6efd;
        }
        .remove-item {
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-box-open me-2"></i>Criar Nova Encomenda</h2>

        <div id="errorAlert" class="alert alert-danger d-none">
            <ul id="errorList"></ul>
        </div>

        <form method="POST" action="{{ route('gestor.encomendas.store') }}" id="encomendaForm">
            @csrf
            <div class="mb-4">
                <label for="data" class="form-label">Data:</label>
                <input type="date" name="data" id="data" class="form-control" value="{{ $today }}" required>
            </div>
            
            <div class="mb-4">
                <label for="fornecedor_id" class="form-label">Fornecedor:</label>
                <select name="fornecedor_id" id="fornecedorSelect" class="form-select" required>
                    <option value="">Selecione um fornecedor</option>
                    @foreach($fornecedores as $fornecedor)
                        <option value="{{ $fornecedor->id }}">{{ $fornecedor->nome }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-3">
                <h5>Itens da Encomenda:</h5>
                <div id="itensContainer">
                    <div class="item-row mb-3">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-5">
                                <label class="form-label">Tipo de Stock:</label>
                                <select name="itens[0][tipo_stock_id]" class="form-select tipoStockSelect" required>
                                    <option value="">Selecione um fornecedor primeiro</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">Quantidade:</label>
                                <input type="number" name="itens[0][stock_quantity]" class="form-control" min="1" required>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger mt-4 remove-item">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" id="addItem" class="btn btn-secondary">
                    <i class="fas fa-plus me-1"></i> Adicionar Item
                </button>
            </div>
            
            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('gestor.encomendas.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Voltar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check me-1"></i> Criar Encomenda
                </button>
            </div>
        </form>
    </div>

    <!-- Bootstrap & jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Error handling
            const errors = {!! json_encode($errors->all()) !!};
            const errorAlert = document.getElementById('errorAlert');
            const errorList = document.getElementById('errorList');
            
            if (errors.length > 0) {
                errorAlert.classList.remove('d-none');
                errors.forEach(error => {
                    const li = document.createElement('li');
                    li.textContent = error;
                    errorList.appendChild(li);
                });
            }

            // Fornecedor change event
            const fornecedorSelect = document.getElementById('fornecedorSelect');
            fornecedorSelect.addEventListener('change', function() {
                const fornecedorId = this.value;
                document.querySelectorAll('.tipoStockSelect').forEach(select => {
                    updateTipoStockSelect(select, fornecedorId);
                });
            });

            // Add item button
            let itemCounter = 1;
            document.getElementById('addItem').addEventListener('click', function() {
                const container = document.getElementById('itensContainer');
                const newRow = document.createElement('div');
                newRow.className = 'item-row mb-3';
                newRow.innerHTML = `
                    <div class="row g-3 align-items-center">
                        <div class="col-md-5">
                            <label class="form-label">Tipo de Stock:</label>
                            <select name="itens[${itemCounter}][tipo_stock_id]" class="form-select tipoStockSelect" required>
                                <option value="">Carregando opções...</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Quantidade:</label>
                            <input type="number" name="itens[${itemCounter}][stock_quantity]" class="form-control" min="1" required>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger mt-4 remove-item">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
                container.appendChild(newRow);
                itemCounter++;
                
                // Initialize with current fornecedor selection
                const fornecedorId = fornecedorSelect.value;
                if (fornecedorId) {
                    const newSelect = newRow.querySelector('.tipoStockSelect');
                    updateTipoStockSelect(newSelect, fornecedorId);
                }
            });

            // Remove item functionality
            document.addEventListener('click', function(e) {
                if (e.target.closest('.remove-item')) {
                    e.target.closest('.item-row').remove();
                }
            });

            // Helper function to update tipo stock select
            function updateTipoStockSelect(selectElement, fornecedorId) {
                if (!fornecedorId) {
                    selectElement.innerHTML = '<option value="">Selecione um fornecedor primeiro</option>';
                    return;
                }

                selectElement.innerHTML = '<option value="">Carregando tipos...</option>';
                selectElement.disabled = true;
                
                fetch(`/gestor/encomendas/tipos-por-fornecedor/${fornecedorId}`)
                    .then(response => {
                        if (!response.ok) throw new Error('Network error');
                        return response.json();
                    })
                    .then(data => {
                        let options = '<option value="">Selecione um tipo de stock</option>';
                        
                        data.forEach(tipo => {
                            options += `<option value="${tipo.id}">${tipo.nome}</option>`;
                        });
                        
                        selectElement.innerHTML = options;
                        selectElement.disabled = false;
                      })
                    .catch(error => {
                        console.error('Fetch error:', error);
                        selectElement.innerHTML = '<option value="">Erro: Tente recarregar</option>';
                    });
            }
            
            // Form validation
            document.getElementById('encomendaForm').addEventListener('submit', function(e) {
                let valid = true;
                
                // Check at least one item has values
                const items = document.querySelectorAll('.item-row');
                if (items.length === 0) {
                    alert('Adicione pelo menos um item à encomenda!');
                    valid = false;
                }
                
                // Check all selects have valid values
                document.querySelectorAll('.tipoStockSelect').forEach(select => {
                    if (!select.value) valid = false;
                });
                
                if (!valid) {
                    e.preventDefault();
                    alert('Preencha todos os campos obrigatórios!');
                }
            });
        });
    </script>
</body>
</html>