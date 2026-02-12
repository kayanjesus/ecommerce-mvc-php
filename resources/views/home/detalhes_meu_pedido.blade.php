<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Pedido #{{ $pedido->id_pedido }} - Cantinho da Isa</title>
    <link rel="stylesheet" href="{{ asset('css/perfil-user.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Estilos adicionais específicos para detalhes do pedido */
        .status-badge {
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .order-timeline {
            display: flex;
            justify-content: space-between;
            margin: 30px 0;
            position: relative;
        }

        .order-timeline::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--border);
            z-index: 1;
        }

        .timeline-step {
            text-align: center;
            position: relative;
            z-index: 2;
            flex: 1;
        }

        .step-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: white;
            border: 2px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            color: #999;
            font-size: 18px;
        }

        .step-active .step-icon {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
        }

        .step-label {
            font-size: 12px;
            color: #666;
            font-weight: 500;
        }

        .info-box {
            background: white;
            border-radius: 15px;
            padding: 25px;
            border: 1px solid var(--border);
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .info-box:hover {
            border-color: var(--primary);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }

        .info-box-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-box-title i {
            font-size: 20px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid var(--border);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #666;
            font-weight: 500;
        }

        .info-value {
            color: var(--dark);
            font-weight: 600;
        }

        .product-detail-row {
            display: flex;
            align-items: center;
            padding: 20px;
            background: var(--light);
            border-radius: 10px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .product-detail-row:hover {
            background: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .product-image {
            width: 80px;
            height: 80px;
            border-radius: 10px;
            object-fit: cover;
            border: 1px solid var(--border);
            margin-right: 20px;
        }

        .product-info {
            flex: 1;
        }

        .product-name {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 5px;
        }

        .product-meta {
            color: #666;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .product-price {
            font-weight: 700;
            color: var(--primary);
        }

        .rating-stars {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .rating-stars i {
            color: #ffc107;
            font-size: 16px;
        }

        .total-section {
            background: linear-gradient(135deg, var(--primary-lighter) 0%, white 100%);
            border-radius: 15px;
            padding: 30px;
            margin-top: 30px;
            border: 1px solid var(--border);
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid var(--border);
        }

        .total-row:last-child {
            border-bottom: none;
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        @media (max-width: 768px) {
            .order-timeline {
                flex-direction: column;
                gap: 20px;
            }

            .order-timeline::before {
                display: none;
            }

            .timeline-step {
                display: flex;
                align-items: center;
                gap: 15px;
                text-align: left;
            }

            .step-icon {
                margin: 0;
            }

            .product-detail-row {
                flex-direction: column;
                text-align: center;
            }

            .product-image {
                margin-right: 0;
                margin-bottom: 15px;
            }

            .action-buttons {
                flex-direction: column;
            }

            .action-buttons a,
            .action-buttons button {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <!-- Header do Dashboard -->
        <div class="dashboard-header">
            <div class="user-profile-main">
                <div class="user-avatar">
                    <i class="fas fa-box-open"></i>
                </div>
                <div class="user-info">
                    <h1>Detalhes do Pedido</h1>
                    <p><i class="fas fa-hashtag me-2"></i>Nº {{ $pedido->id_pedido }}</p>
                    <div class="user-stats">
                        <div class="stat-item">
                            <span class="stat-number">{{ $pedido->itens->count() }}</span>
                            <span class="stat-label">Itens</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">R$ {{ number_format($pedido->total, 2, ',', '.') }}</span>
                            <span class="stat-label">Valor Total</span>
                        </div>
                        <div class="stat-item">
                            <span
                                class="stat-number">{{ \Carbon\Carbon::parse($pedido->created_at)->format('d/m/Y') }}</span>
                            <span class="stat-label">Data</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Layout -->
        <div class="dashboard-main">
            <!-- Sidebar de Navegação -->
            <div class="dashboard-sidebar">
                <div class="nav-section">
                    <div class="nav-section-title">Minha Conta</div>
                    <a href="{{ route('home.dashboard', ['show' => 'pedidos']) }}" class="nav-item">
                        <i class="fas fa-arrow-left"></i>
                        Voltar aos Pedidos
                    </a>
                    <a href="{{ route('home.dashboard', ['show' => 'pedidos']) }}" class="nav-item">
                        <i class="fas fa-shopping-bag"></i>
                        Meus Pedidos
                    </a>
                    <a href="{{ route('home.dashboard', ['show' => 'favoritos']) }}" class="nav-item">
                        <i class="fas fa-heart"></i>
                        Meus Favoritos
                    </a>
                    <a href="{{ route('pagamento.cep') }}" class="nav-item">
                        <i class="fas fa-shopping-cart"></i>
                        Meu Carrinho
                    </a>
                    <a href="{{ url('/') }}" class="nav-item">
                        <i class="fas fa-home"></i>
                        Voltar à Loja
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Ações do Pedido</div>
                    @if ($pedido->podeSerCanceladoPeloCliente())
                        <form action="{{ route('cliente.pedidos.cancelar', $pedido->id_pedido) }}" method="POST"
                            onsubmit="return confirm('Tem certeza que deseja cancelar este pedido?');">
                            @csrf
                            <button type="submit" class="nav-item"
                                style="background: none; border: none; width: 100%; text-align: left; padding: 12px 15px;">
                                <i class="fas fa-times-circle"></i>
                                Cancelar Pedido
                            </button>
                        </form>
                    @endif

                    @if ($pedido->podeConfirmarEntrega())
                        <form action="{{ route('cliente.pedidos.confirmarEntrega', $pedido->id_pedido) }}" method="POST"
                            onsubmit="return confirm('Confirma o recebimento deste pedido?');">
                            @csrf
                            <button type="submit" class="nav-item"
                                style="background: none; border: none; width: 100%; text-align: left; padding: 12px 15px;">
                                <i class="fas fa-check-circle"></i>
                                Confirmar Recebimento
                            </button>
                        </form>
                    @endif

                    @if ($pedido->status === 'entregue' && $pedido->podeAvaliar())
                        <a href="{{ route('cliente.pedidos.avaliar.view', $pedido->id_pedido) }}" class="nav-item">
                            <i class="fas fa-star"></i>
                            Avaliar Pedido
                        </a>
                    @endif
                </div>

                <form method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        Sair da Conta
                    </button>
                </form>
            </div>

            <!-- Área de Conteúdo Principal -->
            <div class="dashboard-content">
                <!-- Alert Messages -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Atenção!</strong> Por favor, verifique os seguintes erros:
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Status Badge -->
                <div class="d-flex justify-content-center mb-4">
                    <span class="status-badge status-{{ str_replace('_', '-', $pedido->status) }}">
                        {{ ucfirst(str_replace('_', ' ', $pedido->status)) }}
                    </span>
                </div>

                <!-- Order Timeline -->
                <div class="order-timeline">
                    @php
                        $steps = [
                            ['icon' => 'fas fa-shopping-cart', 'label' => 'Pedido Realizado', 'status' => 'pendente'],
                            ['icon' => 'fas fa-credit-card', 'label' => 'Pagamento', 'status' => 'pago'],
                            ['icon' => 'fas fa-cogs', 'label' => 'Processando', 'status' => 'processando'],
                            ['icon' => 'fas fa-truck', 'label' => 'Enviado', 'status' => 'enviado'],
                            ['icon' => 'fas fa-check', 'label' => 'Entregue', 'status' => 'entregue']
                        ];

                        $statusOrder = ['pendente', 'pago', 'processando', 'enviado', 'entregue', 'em_transito', 'saiu_para_entrega'];
                        $currentIndex = array_search($pedido->status, $statusOrder);
                    @endphp

                    @foreach($steps as $index => $step)
                        <div class="timeline-step @if($index <= $currentIndex) step-active @endif">
                            <div class="step-icon">
                                <i class="{{ $step['icon'] }}"></i>
                            </div>
                            <div class="step-label">{{ $step['label'] }}</div>
                        </div>
                    @endforeach
                </div>

                <!-- Order Information Cards -->
                <div class="row">
                    <!-- Order Info -->
                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-box-title">
                                <i class="fas fa-info-circle"></i>
                                Informações do Pedido
                            </div>
                            <div class="info-row">
                                <span class="info-label">Número do Pedido:</span>
                                <span class="info-value">#{{ $pedido->id_pedido }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Data do Pedido:</span>
                                <span
                                    class="info-value">{{ \Carbon\Carbon::parse($pedido->created_at)->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Última Atualização:</span>
                                <span
                                    class="info-value">{{ \Carbon\Carbon::parse($pedido->updated_at)->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Confirmado pelo Cliente:</span>
                                <span
                                    class="info-value {{ $pedido->confirmado_pelo_cliente ? 'text-success' : 'text-warning' }}">
                                    {{ $pedido->confirmado_pelo_cliente ? 'Sim' : 'Não' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Info -->
                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-box-title">
                                <i class="fas fa-user-circle"></i>
                                Informações do Cliente
                            </div>
                            <div class="info-row">
                                <span class="info-label">Nome:</span>
                                <span class="info-value">{{ $pedido->usuario->name ?? 'Usuário Não Encontrado' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Email:</span>
                                <span class="info-value">{{ $pedido->usuario->email ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="info-box">
                    <div class="info-box-title">
                        <i class="fas fa-boxes"></i>
                        Itens do Pedido
                    </div>

                    @forelse($pedido->itens as $item)
                        <div class="product-detail-row">
                            @if($item->produto && $item->produto->imagens->isNotEmpty())
                                @php
                                    $mainImage = $item->produto->imagens->firstWhere('principal', true) ?: $item->produto->imagens->first();
                                @endphp
                                @if($mainImage)
                                    <img src="{{ asset($mainImage->caminho) }}" alt="{{ $item->produto->nome_produto }}"
                                        class="product-image">
                                @else
                                    <div class="product-image d-flex align-items-center justify-content-center">
                                        <i class="fas fa-image text-muted fa-2x"></i>
                                    </div>
                                @endif
                            @else
                                <div class="product-image d-flex align-items-center justify-content-center">
                                    <i class="fas fa-image text-muted fa-2x"></i>
                                </div>
                            @endif

                            <div class="product-info">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="product-name">
                                            {{ $item->produto->nome_produto ?? 'Produto Indisponível' }}
                                        </div>
                                        @if($item->cor || $item->tamanho)
                                            <div class="product-meta">
                                                {{ $item->cor->nome ?? '' }}{{ $item->cor && $item->tamanho ? ' • ' : '' }}{{ $item->tamanho->nome ?? '' }}
                                            </div>
                                        @endif
                                        <div class="product-meta">
                                            Quantidade: {{ $item->quantidade }} •
                                            Unitário: R$ {{ number_format($item->preco_unitario, 2, ',', '.') }}
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="product-price mb-2">
                                            R$ {{ number_format($item->quantidade * $item->preco_unitario, 2, ',', '.') }}
                                        </div>
                                        @if($item->avaliacao)
                                            <div class="rating-stars">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $item->avaliacao->nota)
                                                        <i class="fas fa-star"></i>
                                                    @else
                                                        <i class="far fa-star"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            @if($item->avaliacao->comentario)
                                                <small class="text-muted d-block mt-1" style="max-width: 200px;">
                                                    "{{ Str::limit($item->avaliacao->comentario, 50) }}"
                                                </small>
                                            @endif
                                        @else
                                            @if($pedido->status === 'entregue' || $pedido->status === 'pago')
                                                <a href="{{ route('cliente.pedidos.avaliar.view', $pedido->id_pedido) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-star me-1"></i>
                                                    Avaliar
                                                </a>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Nenhum item encontrado para este pedido.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Shipping, Payment, and Refund Info -->
                <div class="row">
                    <!-- Shipping Info -->
                    <div class="col-md-4">
                        <div class="info-box">
                            <div class="info-box-title">
                                <i class="fas fa-truck"></i>
                                Entrega
                            </div>
                            @if($pedido->entrega)
                                <div class="info-row">
                                    <span class="info-label">Método:</span>
                                    <span
                                        class="info-value">{{ ucfirst($pedido->entrega->metodo_entrega ?? 'Não definido') }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Valor do Frete:</span>
                                    <span class="info-value">R$
                                        {{ number_format($pedido->entrega->valor_entrega ?? 0, 2, ',', '.') }}</span>
                                </div>
                                @if($pedido->entrega->data_envio)
                                    <div class="info-row">
                                        <span class="info-label">Data de Envio:</span>
                                        <span
                                            class="info-value">{{ \Carbon\Carbon::parse($pedido->entrega->data_envio)->format('d/m/Y H:i') }}</span>
                                    </div>
                                @endif
                                @if($pedido->entrega->data_entrega)
                                    <div class="info-row">
                                        <span class="info-label">Previsão de Entrega:</span>
                                        <span
                                            class="info-value">{{ \Carbon\Carbon::parse($pedido->entrega->data_entrega)->format('d/m/Y H:i') }}</span>
                                    </div>
                                @endif
                                @if($pedido->entrega->rastreio->codigo_rastreio ?? false)
                                    <div class="info-row">
                                        <span class="info-label">Código de Rastreio:</span>
                                        <span class="info-value">
                                            <code
                                                class="bg-light px-2 py-1 rounded">{{ $pedido->entrega->rastreio->codigo_rastreio }}</code>
                                            <button
                                                onclick="navigator.clipboard.writeText('{{ $pedido->entrega->rastreio->codigo_rastreio }}')"
                                                class="btn btn-sm btn-outline-secondary ms-2">
                                                <i class="far fa-copy"></i>
                                            </button>
                                        </span>
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-3">
                                    <i class="fas fa-truck-loading fa-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">Informações de entrega não disponíveis</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Payment Info -->
                    <div class="col-md-4">
                        <div class="info-box">
                            <div class="info-box-title">
                                <i class="fas fa-credit-card"></i>
                                Pagamento
                            </div>
                            @if($pedido->pagamentoCheckout)
                                <div class="info-row">
                                    <span class="info-label">Método:</span>
                                    <span
                                        class="info-value">{{ ucfirst(str_replace('_', ' ', $pedido->pagamentoCheckout->metodo_pagamento ?? 'N/A')) }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Status:</span>
                                    <span class="info-value 
                                                @if(($pedido->pagamentoCheckout->status ?? '') === 'aprovado') text-success
                                                @elseif(($pedido->pagamentoCheckout->status ?? '') === 'pendente') text-warning
                                                @elseif(($pedido->pagamentoCheckout->status ?? '') === 'recusado') text-danger
                                                @endif">
                                        {{ ucfirst($pedido->pagamentoCheckout->status ?? 'N/A') }}
                                    </span>
                                </div>
                                @if($pedido->pagamentoCheckout->codigo_transacao)
                                    <div class="info-row">
                                        <span class="info-label">Transação:</span>
                                        <span class="info-value">
                                            <code
                                                class="bg-light px-2 py-1 rounded">{{ $pedido->pagamentoCheckout->codigo_transacao }}</code>
                                        </span>
                                    </div>
                                @endif
                                @if($pedido->pagamentoCheckout->data_pagamento)
                                    <div class="info-row">
                                        <span class="info-label">Data do Pagamento:</span>
                                        <span
                                            class="info-value">{{ \Carbon\Carbon::parse($pedido->pagamentoCheckout->data_pagamento)->format('d/m/Y H:i') }}</span>
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-3">
                                    <i class="fas fa-money-bill-wave fa-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">Informações de pagamento não disponíveis</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Informações de Reembolso -->
                    @if($pedido->reembolso)
                                    <div class="card mb-4">
                                        <div class="card-header bg-white border-bottom-0">
                                            <h5 class="section-title mb-0">
                                                <i class="bi bi-arrow-counterclockwise me-2"></i>
                                                Reembolso
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <div class="info-label">Status do Reembolso</div>
                                                        <div class="info-value">
                                                            <span class="badge {{
                        $pedido->reembolso->status == 'concluido' ? 'bg-success' :
                        ($pedido->reembolso->status == 'aprovado' ? 'bg-primary' :
                            ($pedido->reembolso->status == 'processando' ? 'bg-warning text-dark' :
                                ($pedido->reembolso->status == 'negado' ? 'bg-danger' : 'bg-secondary')))
                                        }}">
                                                                {{ ucfirst($pedido->reembolso->status) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <div class="info-label">Valor Reembolsado</div>
                                                        <div class="info-value text-danger">
                                                            R$ {{ number_format($pedido->reembolso->valor_reembolso, 2, ',', '.') }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <div class="info-label">Data da Solicitação</div>
                                                        <div class="info-value">
                                                            {{ $pedido->reembolso->data_solicitacao->format('d/m/Y H:i') }}
                                                        </div>
                                                    </div>
                                                    @if($pedido->reembolso->data_conclusao)
                                                        <div class="mb-3">
                                                            <div class="info-label">Data da Conclusão</div>
                                                            <div class="info-value">
                                                                {{ $pedido->reembolso->data_conclusao->format('d/m/Y H:i') }}
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            @if($pedido->reembolso->motivo)
                                                <div class="mt-3">
                                                    <div class="info-label">Motivo</div>
                                                    <div class="info-value">
                                                        {{ $pedido->reembolso->motivo }}
                                                    </div>
                                                </div>
                                            @endif

                                            @if($pedido->reembolso->codigo_reembolso_pagseguro)
                                                <div class="mt-3">
                                                    <div class="info-label">Código PagSeguro</div>
                                                    <div class="info-value">
                                                        <code>{{ $pedido->reembolso->codigo_reembolso_pagseguro }}</code>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                    @endif
                </div>

                <!-- Order Summary -->
                <div class="total-section">
                    <div class="total-row">
                        <span>Subtotal dos itens</span>
                        <span>R$
                            {{ number_format($pedido->itens->sum(function ($item) {
    return $item->quantidade * $item->preco_unitario; }), 2, ',', '.') }}</span>
                    </div>
                    @if($pedido->entrega)
                        <div class="total-row">
                            <span>Frete</span>
                            <span>R$ {{ number_format($pedido->entrega->valor_entrega ?? 0, 2, ',', '.') }}</span>
                        </div>
                    @endif
                    @if($pedido->desconto_valor > 0)
                        <div class="total-row">
                            <span>Descontos</span>
                            <span class="text-success">-R$ {{ number_format($pedido->desconto_valor, 2, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="total-row">
                        <span><strong>Total do Pedido</strong></span>
                        <span><strong>R$ {{ number_format($pedido->total, 2, ',', '.') }}</strong></span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="{{ route('home.dashboard', ['show' => 'pedidos']) }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Voltar aos Pedidos
                    </a>

                    @if($pedido->status === 'entregue' && $pedido->confirmado_pelo_cliente && $pedido->itens->whereNull('avaliacao')->count() > 0)
                        <a href="{{ route('cliente.pedidos.avaliar.view', $pedido->id_pedido) }}" class="btn btn-warning">
                            <i class="fas fa-star me-2"></i>
                            Avaliar Produtos ({{ $pedido->itens->whereNull('avaliacao')->count() }})
                        </a>
                    @endif

                    @if($pedido->status === 'entregue' && $pedido->podeSolicitarReembolso() && $pedido->prazoReembolsoRestante > 0)
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                            data-bs-target="#reembolsoModal-{{ $pedido->id_pedido }}">
                            <i class="fas fa-undo me-2"></i>
                            Solicitar Reembolso
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Reembolso -->
    @if($pedido->status === 'entregue' && $pedido->podeSolicitarReembolso() && $pedido->prazoReembolsoRestante > 0)
        <div class="modal fade" id="reembolsoModal-{{ $pedido->id_pedido }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content modal-custom">
                    <div class="modal-header modal-header-custom">
                        <h5 class="modal-title modal-title-custom">
                            <i class="fas fa-undo me-2"></i>
                            Solicitar Reembolso
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('cliente.pedidos.solicitarReembolso', $pedido->id_pedido) }}" method="POST">
                        @csrf
                        <div class="modal-body modal-body-custom">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Pedido #{{ $pedido->id_pedido }}</strong> -
                                Valor: <strong>R$ {{ number_format($pedido->total, 2, ',', '.') }}</strong>
                            </div>

                            @if ($pedido->prazoReembolsoRestante !== null && $pedido->prazoReembolsoRestante > 0)
                                <div class="alert alert-warning">
                                    <i class="fas fa-clock me-2"></i>
                                    Você tem {{ $pedido->prazoReembolsoRestante }} dia(s) restantes para solicitar o reembolso.
                                </div>
                            @endif

                            <div class="mb-4">
                                <label class="form-label fw-bold">Motivo do Reembolso</label>
                                <textarea class="form-control" name="motivo_reembolso" rows="4"
                                    placeholder="Descreva brevemente o motivo do reembolso..." style="resize: none;"
                                    required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer modal-footer-custom">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i> Cancelar
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check me-1"></i> Confirmar Solicitação
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Copy tracking code functionality
            document.querySelectorAll('button[onclick*="clipboard"]').forEach(button => {
                const originalHTML = button.innerHTML;
                const originalText = button.textContent;

                button.addEventListener('click', function () {
                    const code = this.closest('.info-value').querySelector('code').textContent;
                    navigator.clipboard.writeText(code).then(() => {
                        this.innerHTML = '<i class="fas fa-check"></i>';
                        this.classList.remove('btn-outline-secondary');
                        this.classList.add('btn-success');

                        setTimeout(() => {
                            this.innerHTML = originalHTML;
                            this.classList.remove('btn-success');
                            this.classList.add('btn-outline-secondary');
                        }, 2000);
                    });
                });
            });

            // Tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Animation on scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            // Observe elements for animation
            document.querySelectorAll('.info-box, .product-detail-row').forEach(element => {
                element.style.opacity = '0';
                element.style.transform = 'translateY(20px)';
                element.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                observer.observe(element);
            });
        });
    </script>
</body>

</html>