<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revisão do Pedido - Cantinho da Isa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/cep.css') }}">
</head>
<body>

    <!-- Logo e Navegação -->
    <nav class="navbar bg-white border-bottom">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home.index') }}">
                <img src="https://i.imgur.com/VtqzvQE.png" alt="Cantinho da Isa">
            </a>
        </div>
    </nav>

    <!-- Etapas -->
    <div class="etapas">
        <div style="opacity: 0.5;"></div>
        <div></div>
        <div style="opacity: 0.5;"></div>
    </div>

    <!-- Conteúdo Principal -->
    <div class="container py-4">
        <div class="row">
            <!-- Resumo do Pedido -->
            <div class="col-md-6 mb-4">
                <div class="border p-3 rounded shadow-sm">
                    <h5 class="mb-3">Seu Pedido</h5>
                    
                    @foreach($itens as $item)
                    <div class="d-flex mb-3">
                        <img src="{{ $item->attributes->image }}" class="produto-img me-3" alt="{{ $item->name }}">
                        <div class="flex-grow-1">
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

                    <div class="text-end total mt-3">
                        Total: R$ {{ number_format($total, 2, ',', '.') }}
                    </div>
                </div>
            </div>

            <!-- Endereço e Pagamento -->
            <div class="col-md-6">
                <div class="border p-3 rounded shadow-sm mb-4">
                    <h5 class="mb-3">Endereço de Entrega</h5>
                    <p>
                        {{ $endereco['rua'] }}, {{ $endereco['numero'] }}<br>
                        {{ $endereco['complemento'] ? $endereco['complemento'] . '<br>' : '' }}
                        {{ $endereco['bairro'] }}<br>
                        {{ $endereco['cidade'] }} - {{ $endereco['estado'] }}<br>
                        CEP: {{ $endereco['cep'] }}
                    </p>
                    <a href="{{ route('pagamento.cep') }}" class="btn btn-sm btn-outline-secondary">
                        Alterar endereço
                    </a>
                </div>

                <div class="border p-3 rounded shadow-sm">
                    <h5 class="mb-3">Método de Pagamento</h5>
                    <form action="{{ route('pagamento.finalizar') }}" method="POST" id="payment-form">
                        @csrf
                        
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="metodo_pagamento" 
                                   id="pix" value="pix" checked>
                            <label class="form-check-label" for="pix">
                                Pix (5% de desconto)
                            </label>
                        </div>
                        
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="metodo_pagamento" 
                                   id="cartao" value="cartao">
                            <label class="form-check-label" for="cartao">
                                Cartão de Crédito
                            </label>
                        </div>
                        
                        <div id="cartao-fields" style="display: none;">
                            <!-- Campos do cartão serão adicionados via JavaScript -->
                            <div class="mb-2">
                                <label for="card-number" class="form-label">Número do Cartão</label>
                                <input type="text" class="form-control" id="card-number" placeholder="1234 5678 9012 3456">
                            </div>
                            <!-- Mais campos do cartão... -->
                        </div>

                        <button type="submit" class="btn btn-continuar mt-3">
                            Finalizar Pedido
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Mostra/oculta campos do cartão
            $('input[name="metodo_pagamento"]').change(function() {
                if ($(this).val() === 'cartao') {
                    $('#cartao-fields').show();
                } else {
                    $('#cartao-fields').hide();
                }
            });
        });
    </script>
</body>
</html>