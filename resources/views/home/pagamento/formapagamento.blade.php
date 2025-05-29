<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento - Cantinho da Isa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pagamento.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <!-- Cabeçalho -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home.index') }}">
                <img src="{{ asset('img/logo/ft_logo.png') }}" alt="Cantinho da Isa">
            </a>
        </div>
    </nav>

    <!-- Progresso -->
    <div class="etapas">
        <div class="etapa etapa-ativa"></div>
        <div class="etapa etapa-ativa"></div>
        <div class="etapa"></div>
    </div>

    <!-- Barra de Informações -->
    <div class="barra-info">
        Frete Grátis - Sul e Sudeste a partir de R$250, demais regiões a partir de R$399
    </div>

    <!-- Conteúdo Principal -->
    <div class="container py-4">
        <div class="row">
            <!-- Resumo do Pedido -->
            <div class="col-lg-6 mb-4">
                <div class="produto-container">
                    @foreach($itens as $item)
                        <div class="d-flex mb-3">
                            <img src="{{ $item->attributes->image }}" class="produto-img me-3" alt="{{ $item->name }}">
                            <div class="produto-info">
                                <p class="mb-1">
                                    <strong>{{ $item->name }}</strong><br>
                                    @if($item->attributes->cor)
                                        Cor: {{ $item->attributes->cor }}<br>
                                    @endif
                                    @if($item->attributes->tamanho)
                                        Tamanho: {{ $item->attributes->tamanho }}<br>
                                    @endif
                                    Quantidade: {{ $item->quantity }}
                                </p>
                                <p class="mb-0">R$ {{ number_format($item->price, 2, ',', '.') }}</p>
                            </div>
                        </div>
                    @endforeach

                    <div class="total">Total: R$ {{ number_format($total, 2, ',', '.') }}</div>
                </div>
            </div>

            <!-- Formulário de Pagamento -->
            <div class="col-lg-6">
                <div class="form-container">
                    <h5 class="form-title"><i class="fas fa-credit-card me-2"></i>Forma de Pagamento</h5>

                    <form action="{{ route('pagamento.salvar-forma-pagamento') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <div class="list-group">
                                <label class="list-group-item list-group-item-action">
                                    <div class="d-flex align-items-center">
                                        <input class="form-check-input me-3" type="radio" name="metodo_pagamento"
                                            value="pix" required>
                                        <div>
                                            <strong>Pix</strong>
                                            <small class="d-block text-muted">Pagamento instantâneo (5% de
                                                desconto)</small>
                                        </div>
                                        <div class="ms-auto">
                                            <i class="fas fa-qrcode fa-lg text-success"></i>
                                        </div>
                                    </div>
                                </label>

                                <label class="list-group-item list-group-item-action">
                                    <div class="d-flex align-items-center">
                                        <input class="form-check-input me-3" type="radio" name="metodo_pagamento"
                                            value="cartao">
                                        <div>
                                            <strong>Cartão de Crédito</strong>
                                            <small class="d-block text-muted">Parcele em até 12x</small>
                                        </div>
                                        <div class="ms-auto">
                                            <i class="fab fa-cc-visa fa-lg text-primary"></i>
                                            <i class="fab fa-cc-mastercard fa-lg ms-2 text-warning"></i>
                                        </div>
                                    </div>
                                </label>

                                <label class="list-group-item list-group-item-action">
                                    <div class="d-flex align-items-center">
                                        <input class="form-check-input me-3" type="radio" name="metodo_pagamento"
                                            value="boleto">
                                        <div>
                                            <strong>Boleto Bancário</strong>
                                            <small class="d-block text-muted">Pagamento em 1 parcela</small>
                                        </div>
                                        <div class="ms-auto">
                                            <i class="fas fa-barcode fa-lg text-dark"></i>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">
                            Continuar <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>