<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revisão - Cantinho da Isa</title>
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
        <div class="etapa etapa-ativa"></div>
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

            <!-- Revisão do Pedido -->
            <div class="col-lg-6">
                <div class="form-container">
                    <h5 class="form-title"><i class="fas fa-clipboard-check me-2"></i>Revisão do Pedido</h5>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6><i class="fas fa-truck me-2"></i>Endereço de Entrega</h6>
                            <a href="{{ route('pagamento.editar-endereco') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-edit me-1"></i>Alterar
                            </a>
                        </div>
                        <div class="card card-body bg-light">
                            <p class="mb-1">
                                {{ $endereco['rua'] }}, {{ $endereco['numero'] }}
                                @if($endereco['complemento'])
                                    - {{ $endereco['complemento'] }}
                                @endif
                            </p>
                            <p class="mb-1">{{ $endereco['bairro'] }}</p>
                            <p class="mb-1">{{ $endereco['cidade'] }} - {{ $endereco['estado'] }}</p>
                            <p class="mb-0">CEP: {{ $endereco['cep'] }}</p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6><i class="fas fa-credit-card me-2"></i>Forma de Pagamento</h6>
                            <a href="{{ route('pagamento.forma-pagamento') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-edit me-1"></i>Alterar
                            </a>
                        </div>
                        <div class="card card-body bg-light">
                            @if($formaPagamento == 'pix')
                                <p class="mb-0"><strong>Pix</strong> - Pagamento instantâneo (5% de desconto)</p>
                            @elseif($formaPagamento == 'cartao')
                                <p class="mb-0"><strong>Cartão de Crédito</strong> - Parcele em até 12x</p>
                            @elseif($formaPagamento == 'boleto')
                                <p class="mb-0"><strong>Boleto Bancário</strong> - Pagamento em 1 parcela</p>
                            @endif
                        </div>
                    </div>

                    <form action="{{ route('pagamento.finalizar') }}" method="POST" id="formFinalizar">
                        @csrf
                        <button type="submit" class="btn btn-primary" id="btnConfirmar">
                            <i class="fas fa-check-circle me-2"></i>Confirmar Pedido
                            <div class="spinner-border text-primary d-none" id="spinner" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById('formFinalizar').addEventListener('submit', function () {
            document.getElementById('btnConfirmar').disabled = true;
            document.getElementById('spinner').classList.remove('d-none');
        });
    </script>
</body>

</html>