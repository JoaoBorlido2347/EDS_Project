<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Mapa de Localizações - Funcionário</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <style>
        .piso-section {
            border: 2px solid #000;
            margin-bottom: 2rem;
            padding: 1rem;
        }
        .piso-header {
            font-weight: bold;
            font-size: 1.25rem;
            border-bottom: 2px solid #000;
            padding-bottom: .5rem;
            margin-bottom: 1rem;
        }
        .location-table th, .location-table td {
            text-align: center;
            vertical-align: middle;
            border: 1px solid #ccc;
            min-width: 100px;
            height: 70px;
        }
        .location-cell.empty {
            background-color: #f5f5f5;
        }
        .location-cell.occupied {
            background-color: #e0ffe0;
        }
        .update-qtd-btn {
            font-size: 0.75rem;
        }
    </style>
</head>
<body>
<div class="container">
    <h1 class="my-4">Mapa de Localizações</h1>

    <a href="{{ route('funcionario.dashboard') }}" class="btn btn-secondary mb-4">
        <i class="fas fa-arrow-left"></i> Voltar para Dashboard
    </a>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @foreach($allowedPisos as $piso)
        <div class="piso-section">
            <div class="piso-header">Piso {{ $piso }}</div>
            <table class="table table-bordered location-table">
                <thead>
                    <tr>
                        <th>Corredor / Prateleira</th>
                        @foreach($allowedPrateleiras as $prateleira)
                            <th>{{ $prateleira }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($allowedCorredores as $corredor)
                        <tr>
                            <td><strong>{{ $corredor }}</strong></td>
                            @foreach($allowedPrateleiras as $prateleira)
                                @php
                                    $location = $locationsGrid[$piso][$corredor][$prateleira] ?? null;
                                    $produto = $location ? $location->produtos->first() : null;
                                @endphp
                                <td class="location-cell {{ $produto ? 'occupied' : 'empty' }}">
                                    @if($produto)
                                        <div>
                                            <span>{{ $produto->nome }} (Qtd: {{ $produto->quantidade }})</span>
                                            @if($produto->esgotado)
                                                <span class="badge bg-danger">ESGOTADO</span>
                                            @endif
                                            <button class="btn btn-sm btn-info update-qtd-btn mt-1"
                                                    data-produto-id="{{ $produto->id }}"
                                                    data-produto-nome="{{ $produto->nome }}"
                                                    data-current-qtd="{{ $produto->quantidade }}">
                                                <i class="fas fa-boxes"></i> Atualizar Qtd
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-muted">Vazio</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach
</div>

<!-- Quantity Update Modal -->
<div class="modal fade" id="updateQtdModal" tabindex="-1" role="dialog" aria-labelledby="updateQtdModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <form id="updateQtdForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title">Atualizar Quantidade</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="produto_id" id="produtoIdQtd">
                    <div class="form-group">
                        <label>Produto</label>
                        <input type="text" class="form-control" id="produtoNomeQtd" readonly>
                    </div>
                    <div class="form-group">
                        <label for="quantidade">Nova Quantidade</label>
                        <input type="number" class="form-control" id="quantidade" name="quantidade" min="0" required>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="esgotado" name="esgotado">
                        <label class="form-check-label" for="esgotado">Marcar como esgotado</label>
                        <small class="form-text text-muted">Isso definirá a quantidade para 0.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
        // Define base URL using Laravel's route helper
    var baseUrl = "{{ route('funcionario.produtos.atualizarQuantidade', ['produto' => ':id']) }}";

$(function () {
    $('.update-qtd-btn').click(function () {
         const id = $(this).data('produto-id');
        const nome = $(this).data('produto-nome');
        const currentQtd = $(this).data('current-qtd');

        $('#produtoIdQtd').val(id);
        $('#produtoNomeQtd').val(nome);
        $('#quantidade').val(currentQtd);
        $('#esgotado').prop('checked', false);
        $('#quantidade').prop('disabled', false);
        const actionUrl = baseUrl.replace(':id', id);
        $('#updateQtdForm').attr('action', actionUrl);
        $('#updateQtdModal').modal('show');
    });

    $('#esgotado').change(function () {
        if (this.checked) {
            $('#quantidade').val(0).prop('disabled', true);
        } else {
            $('#quantidade').prop('disabled', false);
        }
    });


});
</script>
</body>
</html>