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
                    <h5 class="mb-3"><i class="fas fa-shopping-bag me-2"></i>Seu Pedido</h5>

                    @foreach($itens as $item)
                        @php
                            // Verifica se o item está no carrinho (para evitar erros)
                            $cartItem = \Cart::get($item->id);
                            if (!$cartItem) {
                                continue; // Pula itens que não estão mais no carrinho
                            }

                            $cor = isset($item->attributes['cor_id'])
                                ? App\Models\Cor::find($item->attributes['cor_id'])
                                : null;

                            $tamanho = isset($item->attributes['tamanho_id'])
                                ? App\Models\Tamanho::find($item->attributes['tamanho_id'])
                                : null;

                            $subtotal = $item->price * $item->quantity;
                        @endphp

                        <div class="produto-item p-3 mb-3 rounded">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="d-flex">
                                    @if($item->attributes->image)
                                        <img src="{{ asset($item->attributes->image) }}" class="produto-img me-3"
                                            alt="{{ $item->name }}" loading="lazy">
                                    @else
                                        <div class="produto-img me-3 d-flex align-items-center justify-content-center bg-light">
                                            <i class="fas fa-camera text-muted"></i>
                                        </div>
                                    @endif

                                    <div class="produto-info">
                                        <h6 class="mb-1 fw-bold">{{ $item->name }}</h6>

                                        <div class="d-flex align-items-center mb-1">
                                            Cor:<span class="color-preview me-2"
                                                style="background-color: {{ $cor->codigo_hex ?? '#ccc' }}"></span>
                                            <span>{{ $cor->nome ?? 'Cor não especificada' }}</span>
                                        </div>

                                        <div class="mb-1">
                                            <span class="text-muted">Tamanho:</span>
                                            <span>{{ $tamanho->nome ?? 'Tamanho não especificado' }}</span>
                                        </div>

                                        <div>
                                            <span class="text-muted">Quantidade:</span>
                                            <span>{{ $item->quantity }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-end">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3 text-nowrap">
                                            <div class="fw-bold">R$ {{ number_format($item->price, 2, ',', '.') }}</div>

                                        </div>
                                        <button class="btn btn-remover" data-id="{{ $item->id }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="total mt-4 p-3 bg-light rounded d-flex justify-content-between align-items-center">
                        <strong>Total do Pedido</strong>
                        <strong class="fs-5">R$ {{ number_format($total, 2, ',', '.') }}</strong>
                    </div>
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
        document.getElementById('formFinalizar').addEventListener('submit', function (e) {
            e.preventDefault();

            const btn = document.getElementById('btnConfirmar');
            const spinner = document.getElementById('spinner');

            btn.disabled = true;
            spinner.classList.remove('d-none');

            // Envia o formulário via AJAX
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    metodo_pagamento: '{{ session("forma_pagamento") }}'
                })
            })
                .then(response => {
                    if (response.redirected) {
                        window.location.href = response.url;
                    } else {
                        return response.json();
                    }
                })
                .then(data => {
                    if (data && data.error) {
                        alert(data.error);
                        btn.disabled = false;
                        spinner.classList.add('d-none');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Ocorreu um erro ao processar seu pedido.');
                    btn.disabled = false;
                    spinner.classList.add('d-none');
                });
        });
    </script>


    <script>
        $(document).ready(function () {
            $('.btn-remover').click(function (e) {
                e.preventDefault();
                const itemId = $(this).data('id');
                const itemElement = $(this).closest('.produto-item');

                // Confirmação antes de remover
                if (confirm('Deseja realmente remover este item do carrinho?')) {
                    // Adicione aqui a chamada AJAX para remover o item
                    $.ajax({
                        url: '/carrinho/remover/' + itemId,
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            itemElement.fadeOut(300, function () {
                                $(this).remove();
                                // Atualizar o total aqui se necessário
                            });
                        },
                        error: function (xhr) {
                            alert('Ocorreu um erro ao remover o item.');
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>