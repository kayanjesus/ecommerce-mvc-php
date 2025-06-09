<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revisão do Pedido - Cantinho da Isa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Consider compiling your CSS with Laravel Mix for better performance and cache busting --}}
    <link rel="stylesheet" href="{{ asset('css/pagamento.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        /* Estilos específicos para esta página */
        #btnConfirmar {
            position: relative;
        }

        #spinner {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            border-width: 2px;
        }

        .frete-gratis {
            color: #28a745;
            font-weight: bold;
        }

        .desconto-badge {
            background-color: #28a745;
            color: white;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 0.8rem;
            margin-left: 5px;
        }

        /* Melhorias na aparência do produto e resumo */
        .produto-container,
        .form-container {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .produto-item {
            border: 1px solid #dee2e6;
            border-radius: 5px;
            background-color: #fff;
        }

        .produto-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
        }

        .color-preview {
            display: inline-block;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            border: 1px solid #ccc;
            vertical-align: middle;
            margin-right: 5px;
        }

        .btn-remover {
            background: none;
            border: none;
            color: #dc3545;
            font-size: 1.2rem;
            transition: color 0.2s;
        }

        .btn-remover:hover {
            color: #c82333;
        }

        .etapas {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .etapa {
            width: 30px;
            height: 5px;
            background-color: #e0e0e0;
            margin: 0 5px;
            border-radius: 2px;
        }

        .etapa-ativa {
            background-color: #007bff;
            /* Cor primária do Bootstrap */
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home.index') }}">
                <img src="{{ asset('img/logo/ft_logo.png') }}" alt="Cantinho da Isa" height="40">
            </a>
            <h1 class="navbar-text ms-auto mb-0 h4 d-none d-md-block">Revisão do Pedido</h1>
        </div>
    </nav>

    <div class="etapas">
        <div class="etapa etapa-ativa"></div>
        <div class="etapa etapa-ativa"></div>
        <div class="etapa etapa-ativa"></div>
    </div>
    <div class="text-center mb-4">
        <p class="text-muted">3 de 3 - Revisão e Confirmação</p>
    </div>

    <div class="barra-info bg-info text-white text-center py-2 mb-4">
        Frete Grátis - São Paulo a partir de R$250 | Demais regiões a partir de R$399
    </div>

    <div class="container py-4">
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="produto-container">
                    <h5 class="mb-4 pb-2 border-bottom"><i class="fas fa-shopping-bag me-2"></i>Seu Pedido</h5>

                    @forelse($itens as $itemArray)
                        @php
                            $item = (object) $itemArray;
                            // Garanta que attributes também seja objeto para acesso seguro
                            if (is_array($item->attributes)) {
                                $item->attributes = (object) $item->attributes;
                            }

                            // Verifica se o item ainda existe no carrinho.
                            // Isso é redundante se $itens já vem do carrinho, mas como segurança.
                            // Para um código mais limpo, $itens deveria ser diretamente os itens do carrinho.
                            $cartItem = \Cart::get($item->id);
                            if (!$cartItem) {
                                continue; // Pula para o próximo item se não encontrar no carrinho
                            }

                            $cor = isset($item->attributes->cor_id) ? App\Models\Cor::find($item->attributes->cor_id) : null;
                            $tamanho = isset($item->attributes->tamanho_id) ? App\Models\Tamanho::find($item->attributes->tamanho_id) : null;
                            $subtotalItem = $item->price * $item->quantity; // Subtotal por item
                        @endphp

                        <div class="produto-item p-3 mb-3 rounded">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    @if(isset($item->attributes->image))
                                        <img src="{{ asset($item->attributes->image) }}" class="produto-img me-3"
                                            alt="{{ $item->name }}" loading="lazy">
                                    @else
                                        <div class="produto-img me-3 d-flex align-items-center justify-content-center bg-light text-muted">
                                            <i class="fas fa-image fa-2x"></i> {{-- Ícone maior para placeholder --}}
                                        </div>
                                    @endif

                                    <div class="produto-info flex-grow-1">
                                        <h6 class="mb-1 fw-bold">{{ $item->name }}</h6>
                                        <p class="mb-1 text-muted small">
                                            @if($cor)
                                                Cor: <span class="color-preview" style="background-color: {{ $cor->codigo_hex }}"></span> {{ $cor->nome }}
                                            @else
                                                Cor: Não especificada
                                            @endif
                                            @if($tamanho)
                                                | Tamanho: {{ $tamanho->nome }}
                                            @else
                                                | Tamanho: Não especificado
                                            @endif
                                        </p>
                                        <p class="mb-0 text-muted small">Quantidade: {{ $item->quantity }}</p>
                                    </div>
                                </div>

                                <div class="text-end">
                                    <div class="fw-bold fs-5 mb-2">R$ {{ number_format($item->price, 2, ',', '.') }}</div>
                                    <button class="btn btn-remover" data-id="{{ $item->id }}" title="Remover item">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-warning text-center" role="alert">
                            Seu carrinho está vazio. <a href="{{ route('home.index') }}">Continue comprando!</a>
                        </div>
                    @endforelse

                    <div class="resumo-financeiro mt-4 p-3 bg-light rounded">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal dos Produtos:</span>
                            <span>R$ {{ number_format($subtotal, 2, ',', '.') }}</span>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Frete:</span>
                            <span>
                                @if($frete == 0)
                                    <span class="frete-gratis">Grátis</span>
                                @else
                                    R$ {{ number_format($frete, 2, ',', '.') }}
                                @endif
                            </span>
                        </div>

                        @if($formaPagamento == 'pix')
                            <div class="d-flex justify-content-between mb-2 text-success fw-bold">
                                <span>Desconto PIX (5%):</span>
                                <span>- R$ {{ number_format($subtotal * 0.05, 2, ',', '.') }}</span>
                            </div>
                        @endif

                        <hr class="my-3">

                        <div class="d-flex justify-content-between fw-bold fs-4 text-primary">
                            <span>Total Geral:</span>
                            <span>R$ {{ number_format($totalComFrete, 2, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-container">
                    <h5 class="form-title mb-4 pb-2 border-bottom"><i class="fas fa-clipboard-check me-2"></i>Revisão e Confirmação</h5>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0"><i class="fas fa-truck me-2 text-muted"></i>Endereço de Entrega</h6>
                            <a href="{{ route('pagamento.editar-endereco') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-edit me-1"></i>Alterar
                            </a>
                        </div>
                        <div class="card card-body bg-light border-0">
                            @if(isset($endereco) && !empty($endereco))
                                <p class="mb-1"><strong>{{ $endereco['rua'] }}, {{ $endereco['numero'] }}</strong>
                                    @if(!empty($endereco['complemento']))
                                        - {{ $endereco['complemento'] }}
                                    @endif
                                </p>
                                <p class="mb-1">{{ $endereco['bairro'] }}</p>
                                <p class="mb-1">{{ $endereco['cidade'] }} - {{ $endereco['estado'] }}</p>
                                <p class="mb-0">CEP: {{ $endereco['cep'] }}</p>

                                @if($frete == 0)
                                    <p class="mt-2 mb-0 frete-gratis small">
                                        <i class="fas fa-check-circle me-1"></i>
                                        @if($endereco['estado'] == 'SP')
                                            Frete grátis para São Paulo (pedidos acima de R$250)
                                        @else
                                            Frete grátis (pedidos acima de R$399)
                                        @endif
                                    </p>
                                @endif
                            @else
                                <div class="alert alert-danger mb-0" role="alert">
                                    Nenhum endereço de entrega selecionado. Por favor, <a href="{{ route('pagamento.cep') }}">clique aqui para informar seu CEP</a>.
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0"><i class="fas fa-credit-card me-2 text-muted"></i>Forma de Pagamento</h6>
                            <a href="{{ route('pagamento.forma-pagamento') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-edit me-1"></i>Alterar
                            </a>
                        </div>
                        <div class="card card-body bg-light border-0">
                            @if($formaPagamento == 'pix')
                                <p class="mb-0">
                                    <strong>Pix</strong>
                                    <span class="desconto-badge">5% OFF</span>
                                    <br>
                                    <small class="text-muted">Pagamento instantâneo com 5% de desconto.</small>
                                </p>
                            @elseif($formaPagamento == 'cartao')
                                <p class="mb-0">
                                    <strong>Cartão de Crédito</strong>
                                    <br>
                                    <small class="text-muted">Parcele em até 6x sem juros.</small>
                                </p>

                                <div class="mt-3">
                                    <label for="parcelas" class="form-label fw-bold">Número de Parcelas:</label>
                                    <select name="parcelas" id="parcelas" class="form-select">
                                        @for($i = 1; $i <= 6; $i++)
                                            <option value="{{ $i }}">
                                                {{ $i }}x de R$ {{ number_format($totalComFrete / $i, 2, ',', '.') }}
                                                @if($i > 1) (sem juros) @endif
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            @elseif($formaPagamento == 'boleto')
                                <p class="mb-0">
                                    <strong>Boleto Bancário</strong>
                                    <br>
                                    <small class="text-muted">Pagamento em 1 parcela.</small>
                                </p>
                            @else
                                <div class="alert alert-danger mb-0" role="alert">
                                    Nenhuma forma de pagamento selecionada. Por favor, <a href="{{ route('pagamento.forma-pagamento') }}">clique aqui para escolher uma</a>.
                                </div>
                            @endif
                        </div>
                    </div>

                    <form id="formFinalizar" method="POST" action="{{ route('pagamento.finalizar') }}">
                        @csrf
                        {{-- O input hidden para parcelas é mais robusto quando fora do if, mas com valor padrão 1 --}}
                        <input type="hidden" name="parcelas" id="inputParcelas" value="1">
                        <button type="submit" id="btnConfirmar" class="btn btn-primary w-100 py-2 fs-5"
                            @if(count($itens) === 0 || !isset($endereco) || empty($endereco) || !isset($formaPagamento) || empty($formaPagamento)) disabled @endif>
                            <i class="fas fa-check-circle me-2"></i>Confirmar Pedido
                            <span id="spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        </button>
                        @if(count($itens) === 0)
                            <small class="text-danger mt-2 d-block text-center">Adicione itens ao carrinho para finalizar o pedido.</small>
                        @elseif(!isset($endereco) || empty($endereco))
                            <small class="text-danger mt-2 d-block text-center">É necessário informar o endereço de entrega.</small>
                        @elseif(!isset($formaPagamento) || empty($formaPagamento))
                            <small class="text-danger mt-2 d-block text-center">É necessário selecionar uma forma de pagamento.</small>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function () {
            // Atualiza o input hidden com o valor selecionado de parcelas
            // Certifique-se de que o seletor seja preciso para evitar conflitos
            $('#parcelas').change(function () {
                $('#inputParcelas').val($(this).val());
            });

            // Remover item do carrinho via AJAX
            $('.btn-remover').click(function (e) {
                e.preventDefault();
                const itemId = $(this).data('id');
                const itemElement = $(this).closest('.produto-item');

                if (confirm('Deseja realmente remover este item do carrinho?')) {
                    $.ajax({
                        url: '/carrinho/remover/' + itemId, // Rota DELETE para remover item
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            if (response.success) {
                                itemElement.fadeOut(300, function () {
                                    $(this).remove();
                                    // Recarrega a página para atualizar os totais e frete
                                    location.reload();
                                });
                            } else {
                                alert(response.message || 'Erro ao remover o item.');
                            }
                        },
                        error: function (xhr) {
                            console.error("Erro ao remover item:", xhr.responseText);
                            alert('Ocorreu um erro ao remover o item. Tente novamente.');
                        }
                    });
                }
            });

            // Envio do formulário de finalização do pedido via AJAX
            $('#formFinalizar').submit(function (e) {
                e.preventDefault(); // Impede o envio tradicional do formulário
                const btn = $('#btnConfirmar');
                const spinner = $('#spinner');

                btn.prop('disabled', true).addClass('d-flex align-items-center justify-content-center'); // Adiciona d-flex para alinhar spinner
                spinner.removeClass('d-none');
                btn.contents().filter(function() {
                    return this.nodeType === 3; // Seleciona apenas os nós de texto
                }).each(function() {
                    this.textContent = 'Processando...'; // Altera o texto do botão
                });


                // Coletar dados do formulário
                const formData = $(this).serialize(); // Serializa todos os campos do formulário

                $.ajax({
                    url: '{{ route("pagamento.finalizar") }}',
                    method: 'POST',
                    dataType: 'json', // Espera uma resposta JSON
                    data: formData,
                    success: function (response) {
                        if (response.redirect) {
                            window.location.href = response.redirect; // Redireciona conforme a resposta
                        } else if (response.error) {
                            alert(response.message || 'Erro ao processar pedido.');
                            // Lógica de redirecionamento mais específica em caso de erro
                            if (response.message && response.message.includes('endereço')) {
                                window.location.href = '{{ route("pagamento.cep") }}';
                            } else if (response.message && response.message.includes('pagamento')) {
                                window.location.href = '{{ route("pagamento.forma-pagamento") }}';
                            } else {
                                window.location.href = '{{ route("pagamento.erro") }}';
                            }
                        }
                    },
                    error: function (xhr) {
                        let errorMsg = 'Ocorreu um erro inesperado ao processar o pedido.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        } else if (xhr.status === 419) { // CSRF token mismatch
                            errorMsg = 'Sua sessão expirou. Por favor, recarregue a página e tente novamente.';
                        }
                        alert(errorMsg);
                        // Redireciona para a página de erro ou onde for apropriado
                        window.location.href = '{{ route("pagamento.erro") }}';
                    },
                    complete: function () {
                        btn.prop('disabled', false).removeClass('d-flex align-items-center justify-content-center'); // Remove d-flex
                        spinner.addClass('d-none');
                        btn.contents().filter(function() {
                            return this.nodeType === 3;
                        }).each(function() {
                            this.textContent = 'Confirmar Pedido'; // Restaura o texto do botão
                        });
                    }
                });
            });
        });
    </script>
</body>

</html>