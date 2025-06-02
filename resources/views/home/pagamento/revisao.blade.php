<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revisão - Cantinho da Isa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pagamento.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
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
    </style>
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
        Frete Grátis - São Paulo a partir de R$250 | Demais regiões a partir de R$399
    </div>

    <!-- Conteúdo Principal -->
    <div class="container py-4">
        <div class="row">
            <!-- Resumo do Pedido -->
            <div class="col-lg-6 mb-4">
                <div class="produto-container">
                    <h5 class="mb-3"><i class="fas fa-shopping-bag me-2"></i>Seu Pedido</h5>

                    @foreach($itens as $itemArray)
                        @php
                            $item = (object) $itemArray;
                            // Garanta que attributes também seja objeto
                            if (is_array($item->attributes)) {
                                $item->attributes = (object) $item->attributes;
                            }

                            $cartItem = \Cart::get($item->id);
                            if (!$cartItem)
                                continue;

                            $cor = isset($item->attributes->cor_id) ? App\Models\Cor::find($item->attributes->cor_id) : null;
                            $tamanho = isset($item->attributes->tamanho_id) ? App\Models\Tamanho::find($item->attributes->tamanho_id) : null;
                            $subtotal = $item->price * $item->quantity;
                        @endphp

                        <div class="produto-item p-3 mb-3 rounded">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="d-flex">
                                    @if(isset($item->attributes->image))
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

                    <!-- Resumo Financeiro -->
                    <div class="resumo-financeiro mt-4 p-3 bg-light rounded">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>R$ {{ number_format($total, 2, ',', '.') }}</span>
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
                            <div class="d-flex justify-content-between mb-2 text-success">
                                <span>Desconto PIX (5%):</span>
                                <span>- R$ {{ number_format($total * 0.05, 2, ',', '.') }}</span>
                            </div>
                        @endif

                        <hr>

                        <div class="d-flex justify-content-between fw-bold fs-5">
                            <span>Total:</span>
                            <span>R$ {{ number_format($totalComFrete, 2, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revisão do Pedido -->
            <div class="col-lg-6">
                <div class="form-container">
                    <h5 class="form-title"><i class="fas fa-clipboard-check me-2"></i>Revisão do Pedido</h5>

                    <!-- Seção de Endereço -->
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

                            @if($frete == 0)
                                <p class="mt-2 mb-0 frete-gratis">
                                    <i class="fas fa-check-circle"></i>
                                    @if($endereco['estado'] == 'SP')
                                        Frete grátis para São Paulo (pedido acima de R$250)
                                    @else
                                        Frete grátis (pedido acima de R$399)
                                    @endif
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Seção de Pagamento -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6><i class="fas fa-credit-card me-2"></i>Forma de Pagamento</h6>
                            <a href="{{ route('pagamento.forma-pagamento') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-edit me-1"></i>Alterar
                            </a>
                        </div>
                        <div class="card card-body bg-light">
                            @if($formaPagamento == 'pix')
                                <p class="mb-0">
                                    <strong>Pix</strong>
                                    <span class="desconto-badge">5% OFF</span>
                                    <br>
                                    <small class="text-muted">Pagamento instantâneo com 5% de desconto</small>
                                </p>
                            @elseif($formaPagamento == 'cartao')
                                <p class="mb-0">
                                    <strong>Cartão de Crédito</strong>
                                    <br>
                                    <small class="text-muted">Parcele em até 6x sem juros</small>
                                </p>

                                <!-- Seletor de Parcelas -->
                                <div class="mt-3">
                                    <label class="form-label">Parcelamento:</label>
                                    <select name="parcelas" class="form-select">
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
                                    <small class="text-muted">Pagamento em 1 parcela</small>
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Botão de Confirmação -->
                    <form id="formFinalizar">
                        @csrf
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        @if($formaPagamento == 'cartao')
                            <input type="hidden" name="parcelas" id="inputParcelas" value="1">
                        @endif

                        <button type="button" id="btnConfirmar" class="btn btn-primary w-100 py-2">
                            <i class="fas fa-check-circle me-2"></i>Confirmar Pedido
                            <span id="spinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById('btnConfirmar').addEventListener('click', function (e) {
            e.preventDefault();

            const btn = this;
            const spinner = document.getElementById('spinner');

            // Mostrar loading
            btn.disabled = true;
            spinner.classList.remove('d-none');

            fetch('{{ route("pagamento.finalizar") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    parcelas: document.getElementById('inputParcelas')?.value || 1
                })
            })
                .then(response => {
                    // Verificar se a resposta é JSON
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        return response.text().then(text => {
                            throw new Error('Resposta do servidor inválida');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        window.location.href = data.redirect;
                    } else {
                        alert(data.message || 'Erro ao processar pedido');
                        if (data.message.includes('endereço')) {
                            window.location.href = '{{ route("pagamento.cep") }}';
                        } else {
                            window.location.href = '{{ route("pagamento.erro") }}';
                        }
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao processar pedido. Por favor, tente novamente.');
                    window.location.href = '{{ route("pagamento.erro") }}';
                })
                .finally(() => {
                    btn.disabled = false;
                    spinner.classList.add('d-none');
                });
        });
    </script>





    <script>
        document.getElementById('formFinalizar').addEventListener('submit', async function (e) {
            e.preventDefault();

            const form = this;
            const btn = document.getElementById('btnConfirmar');
            const spinner = document.getElementById('spinner');

            btn.disabled = true;
            spinner.classList.remove('d-none');

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: new FormData(form)
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Erro ao processar pedido');
                }

                if (data.redirect) {
                    window.location.href = data.redirect;
                }

            } catch (error) {
                console.error('Error:', error);
                alert(error.message);
                // Você pode adicionar mais tratamento de erro aqui
            } finally {
                btn.disabled = false;
                spinner.classList.add('d-none');
            }
        });
    </script>




    <script>
        $(document).ready(function () {
            // Atualiza o input hidden com o valor selecionado de parcelas
            $('select[name="parcelas"]').change(function () {
                $('#inputParcelas').val($(this).val());
            });

            // Remove item do carrinho
            $('.btn-remover').click(function (e) {
                e.preventDefault();
                const itemId = $(this).data('id');
                const itemElement = $(this).closest('.produto-item');

                if (confirm('Deseja realmente remover este item do carrinho?')) {
                    $.ajax({
                        url: '/carrinho/remover/' + itemId,
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function () {
                            itemElement.fadeOut(300, function () {
                                $(this).remove();
                                location.reload(); // Recarrega para atualizar totais
                            });
                        },
                        error: function () {
                            alert('Ocorreu um erro ao remover o item.');
                        }
                    });
                }
            });

            // Envio do formulário
            $('#formFinalizar').submit(function (e) {
                e.preventDefault();
                const btn = $('#btnConfirmar');
                const spinner = $('#spinner');

                console.log("Enviando formulário..."); // Debug

                btn.prop('disabled', true);
                spinner.removeClass('d-none');

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        console.log("Resposta recebida:", response); // Debug
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        } else {
                            console.warn("Nenhum redirecionamento recebido");
                        }
                    },
                    error: function (xhr) {
                        console.error("Erro na requisição:", xhr); // Debug
                        alert('Ocorreu um erro: ' + (xhr.responseJSON?.message || xhr.statusText));
                        btn.prop('disabled', false);
                        spinner.addClass('d-none');
                    },
                    complete: function () {
                        console.log("Requisição completa"); // Debug
                    }
                });
            });
        });
    </script>
</body>

</html>