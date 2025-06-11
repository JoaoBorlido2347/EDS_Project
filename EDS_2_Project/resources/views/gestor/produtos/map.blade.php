<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Mapa de Localizações</title>
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

        .move-btn {
            font-size: 0.75rem;
        }
    </style>
</head>
<body>
<div class="container">
    <h1 class="my-4">Mapa de Localizações</h1>

    <a href="{{ route('gestor.produtos.index') }}" class="btn btn-secondary mb-4">
        <i class="fas fa-arrow-left"></i> Voltar para Produtos
    </a>

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
                                    $produto = $location->produtos->first() ?? null;
                                @endphp
                                <td class="location-cell {{ $produto ? 'occupied' : 'empty' }}">
                                    @if($produto)
                                        <div>
                                            <span>{{ $produto->nome }}</span>
                                            @if($produto->esgotado)
                                            <span class="badge bg-danger">ESGOTADO</span>
                                        @endif
                                            <button class="btn btn-sm btn-primary move-btn mt-1"
                                                    data-produto-id="{{ $produto->id }}"
                                                    data-produto-nome="{{ $produto->nome }}"
                                                    data-current-piso="{{ $piso }}"
                                                    data-current-corredor="{{ $corredor }}"
                                                    data-current-prateleira="{{ $prateleira }}">
                                                <i class="fas fa-edit"></i>
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

<!-- Move Modal -->
<div class="modal fade" id="moveModal" tabindex="-1" role="dialog" aria-labelledby="moveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <form id="moveForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title">Mover Produto</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="produto_id" id="produtoId">
                    <div class="form-group">
                        <label>Produto</label>
                        <input type="text" class="form-control" id="produtoNome" readonly>
                    </div>
                    <div class="form-group">
                        <label>Localização Atual</label>
                        <input type="text" class="form-control" id="currentLocation" readonly>
                    </div>
                    <div class="form-group">
                        <label for="piso">Novo Piso</label>
                        <select class="form-control" id="piso" name="piso">
                            @foreach($allowedPisos as $p)
                                <option value="{{ $p }}">{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="corredor">Novo Corredor</label>
                        <select class="form-control" id="corredor" name="corredor">
                            @foreach($allowedCorredores as $c)
                                <option value="{{ $c }}">{{ $c }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="prateleira">Nova Prateleira</label>
                        <select class="form-control" id="prateleira" name="prateleira">
                            @foreach($allowedPrateleiras as $pr)
                                <option value="{{ $pr }}">{{ $pr }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary" type="submit">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Hidden JSON Template for Popout Window -->
<script type="application/json" id="moveProdutoTemplate">
    {
        "produto_id": "",
        "produto_nome": "",
        "piso": "",
        "corredor": "",
        "prateleira": ""
    }
</script>

<script>
    $(function () {
        $('.move-btn').click(function () {
            const id = $(this).data('produto-id');
            const nome = $(this).data('produto-nome');
            const piso = $(this).data('current-piso');
            const corredor = $(this).data('current-corredor');
            const prateleira = $(this).data('current-prateleira');

            $('#produtoId').val(id);
            $('#produtoNome').val(nome);
            $('#currentLocation').val(`Piso ${piso}, ${corredor}, ${prateleira}`);
            $('#piso').val(piso);
            $('#corredor').val(corredor);
            $('#prateleira').val(prateleira);
            $('#moveForm').attr('action', `/gestor/produtos/${id}/move`);

            $('#moveModal').modal('show');
        });
    });
</script>
</body>
</html>
