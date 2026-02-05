<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido #{{ $pedido->id_pedido }} - Cantinho da Isa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/adm/pedidosdetalhe.css') }}">

</head>

<body>
    <header class="main-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col ps-4">
                    <h1 class="store-title">CANTINHO DA ISA</h1>
                </div>
            </div>
        </div>
    </header>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-2 d-flex flex-column h-100">
                    <div class="admin-container ps-2 pe-2 mb-2">
                        <a href="#" class="admin-button">
                            <i class="bi bi-person-circle"></i> {{ Auth::user()->email }}
                        </a>
                    </div>

                    <ul class="nav flex-column sidebar-menu flex-grow-1">
                        <li class="nav-item">
                            <a class="nav-link menu-button" href="{{ route('adm.dashboard') }}">
                                <i class="bi bi-house"></i> Início
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link menu-button active" href="{{ route('adm.pedidos') }}">
                                <i class="bi bi-receipt"></i> Pedidos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link menu-button" href="{{ route('adm.pdtestoque') }}">
                                <i class="bi bi-box-seam"></i> Produtos e estoque
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link menu-button" href="{{ route('adm.cdtproduto') }}">
                                <i class="bi bi-plus-circle"></i> Cadastro de produtos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link menu-button" href="{{ route('adm.usercadastrado') }}">
                                <i class="bi bi-people"></i> Usuários cadastrados
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link menu-button" href="{{ route('adm.vendas') }}">
                                <i class="bi bi-graph-up-arrow"></i> Vendas
                            </a>
                        </li>
                    </ul>

                    <div class="mt-auto p-3">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="logout-button">
                                <i class="bi bi-box-arrow-right"></i> Sair
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Conteúdo Principal -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <!-- Cabeçalho -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="mb-1"><i class="bi bi-receipt text-primary"></i> Pedido #{{ $pedido->id_pedido }}
                        </h2>
                        <p class="text-muted mb-0">
                            {{ $pedido->data_pedido?->format('d/m/Y H:i') ?? 'Data não informada' }}
                        </p>
                    </div>
                    <div class="action-buttons">
                        <a href="{{ route('adm.pedidos') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Voltar
                        </a>
                        <button class="btn btn-outline-primary" onclick="window.print()">
                            <i class="bi bi-printer"></i> Imprimir
                        </button>
                    </div>
                </div>

                <!-- Alertas -->
                @if(session('sucesso'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>
                        {{ session('sucesso') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session('erro'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        {{ session('erro') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="row">
                    <!-- Coluna Esquerda - Informações do Pedido -->
                    <div class="col-lg-8 mb-4">
                        <!-- Status do Pedido -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div>
                                        <h5 class="card-title mb-2">Status do Pedido</h5>
                                        <span class="order-status-badge {{
    $pedido->status === 'pago' ? 'bg-success' :
    ($pedido->status === 'processando' ? 'bg-warning text-dark' :
        ($pedido->status === 'enviado' ? 'bg-info' :
            ($pedido->status === 'entregue' ? 'bg-primary' : 'bg-secondary')))
                                        }}">
                                            <i class="bi bi-circle-fill me-1"></i>
                                            {{ ucfirst($pedido->status) }}
                                        </span>
                                    </div>
                                    <div class="text-end">
                                        <h4 class="text-success mb-0">R$
                                            {{ number_format($pedido->total, 2, ',', '.') }}
                                        </h4>
                                        <small class="text-muted">Total do pedido</small>
                                    </div>
                                </div>

                                <!-- Timeline de Status -->
                                <div class="timeline mt-4">
                                    <div
                                        class="timeline-item {{ in_array($pedido->status, ['processando', 'enviado', 'entregue']) ? 'completed' : 'pending' }}">
                                        <h6 class="mb-1">Pedido Realizado</h6>
                                        <small
                                            class="text-muted">{{ $pedido->data_pedido?->format('d/m/Y H:i') }}</small>
                                    </div>

                                    <div
                                        class="timeline-item {{ in_array($pedido->status, ['enviado', 'entregue']) ? 'completed' : ($pedido->status == 'processando' ? 'current' : 'pending') }}">
                                        <h6 class="mb-1">Processando</h6>
                                        <small class="text-muted">Preparando para envio</small>
                                    </div>

                                    <div
                                        class="timeline-item {{ $pedido->status == 'entregue' ? 'completed' : ($pedido->status == 'enviado' ? 'current' : 'pending') }}">
                                        <h6 class="mb-1">Enviado</h6>
                                        @if($pedido->entrega?->data_envio)
                                            <small
                                                class="text-muted">{{ $pedido->entrega->data_envio->format('d/m/Y H:i') }}</small>
                                        @else
                                            <small class="text-muted">Aguardando envio</small>
                                        @endif
                                    </div>

                                    <div
                                        class="timeline-item {{ $pedido->status == 'entregue' ? 'completed' : 'pending' }}">
                                        <h6 class="mb-1">Entregue</h6>
                                        @if($pedido->entrega?->data_entrega)
                                            <small
                                                class="text-muted">{{ $pedido->entrega->data_entrega->format('d/m/Y H:i') }}</small>
                                        @else
                                            <small class="text-muted">Aguardando entrega</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Itens do Pedido -->
                        <div class="card mb-4">
                            <div class="card-header bg-white border-bottom-0">
                                <h5 class="section-title mb-0">Itens do Pedido</h5>
                            </div>
                            <div class="card-body">
                                @forelse($pedido->itens as $item)
                                    <div class="item-card">
                                        <div class="d-flex align-items-center">
                                            <!-- Imagem do Produto -->
                                            <div class="me-3">
                                                @php
                                                    $mainImage = null;
                                                    if ($item->produto && $item->produto->imagens) {
                                                        $mainImage = $item->produto->imagens->where('principal', true)->first() ?? $item->produto->imagens->first();
                                                    }
                                                @endphp
                                                @if($mainImage)
                                                    <img src="{{ asset($mainImage->caminho) }}"
                                                        alt="{{ $item->produto->nome_produto }}" class="item-image"
                                                        onerror="this.src='https://via.placeholder.com/80x80?text=Produto'">
                                                @else
                                                    <div class="no-image">
                                                        <i class="bi bi-image text-muted"></i>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Informações do Produto -->
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">
                                                    {{ $item->produto->nome_produto ?? 'Produto Indisponível' }}
                                                </h6>
                                                <div class="d-flex flex-wrap gap-2 mb-2">
                                                    @if($item->cor)
                                                        <span class="badge bg-light text-dark border">
                                                            <i class="bi bi-palette me-1"></i>
                                                            {{ $item->cor->nome }}
                                                        </span>
                                                    @endif
                                                    @if($item->tamanho)
                                                        <span class="badge bg-light text-dark border">
                                                            <i class="bi bi-rulers me-1"></i>
                                                            {{ $item->tamanho->nome }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <small class="text-muted">Quantidade:
                                                            {{ $item->quantidade }}</small>
                                                        <span class="mx-2">•</span>
                                                        <small class="text-muted">Unitário: R$
                                                            {{ number_format($item->preco_unitario, 2, ',', '.') }}</small>
                                                    </div>
                                                    <strong class="text-primary">
                                                        R$
                                                        {{ number_format($item->quantidade * $item->preco_unitario, 2, ',', '.') }}
                                                    </strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-4">
                                        <i class="bi bi-cart-x text-muted" style="font-size: 3rem;"></i>
                                        <p class="text-muted mt-3">Nenhum item encontrado para este pedido.</p>
                                    </div>
                                @endforelse

                                <!-- Resumo dos Valores -->
                                <div class="order-summary-card p-4 mt-4 rounded">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-muted">Subtotal:</span>
                                                <span>R$
                                                    {{ number_format($pedido->total - ($pedido->entrega->valor_entrega ?? 0), 2, ',', '.') }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-muted">Frete:</span>
                                                <span>R$
                                                    {{ number_format($pedido->entrega->valor_entrega ?? 0, 2, ',', '.') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h5 class="mb-0">Total:</h5>
                                                <h4 class="text-success mb-0">R$
                                                    {{ number_format($pedido->total, 2, ',', '.') }}
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informações do Cliente -->
                        <div class="card">
                            <div class="card-header bg-white border-bottom-0">
                                <h5 class="section-title mb-0">Informações do Cliente</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="info-label">Nome</div>
                                            <div class="info-value">
                                                <i class="bi bi-person me-2"></i>
                                                {{ $pedido->usuario->name ?? 'Usuário Desconhecido' }}
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="info-label">Email</div>
                                            <div class="info-value">
                                                <i class="bi bi-envelope me-2"></i>
                                                {{ $pedido->usuario->email ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="info-label">Telefone</div>
                                            <div class="info-value">
                                                <i class="bi bi-telephone me-2"></i>
                                                {{ $pedido->usuario->telefone ?? 'Não informado' }}
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="info-label">Observações</div>
                                            <div class="info-value">
                                                {{ $pedido->observacoes ?? 'Nenhuma observação' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($pedido->reembolso && $pedido->reembolso->status == 'solicitado')
                        <div class="alert alert-warning">
                            <h5><i class="bi bi-exclamation-triangle"></i> Reembolso Pendente</h5>
                            <p>Este reembolso precisa ser processado manualmente.</p>

                            <form action="{{ route('adm.reembolsos.processar-manual', $pedido->reembolso->id_reembolso) }}"
                                method="POST">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-sm">
                                    <i class="bi bi-arrow-clockwise"></i> Processar Reembolso Manualmente
                                </button>
                            </form>
                        </div>
                    @endif
                    
                    <!-- Coluna Direita - Informações Adicionais -->
                    <div class="col-lg-4">
                        <!-- Endereço de Entrega -->
                        <div class="card mb-4">
                            <div class="card-header bg-white border-bottom-0">
                                <h5 class="section-title mb-0">
                                    <i class="bi bi-truck me-2"></i>
                                    Endereço de Entrega
                                </h5>
                            </div>
                            <div class="card-body">
                                @php
                                    $endereco = json_decode($pedido->endereco_entrega, true);
                                @endphp
                                @if($endereco)
                                    <div class="address-card p-3 rounded">
                                        <div class="mb-2">
                                            <i class="bi bi-geo-alt text-primary me-2"></i>
                                            <strong>{{ $endereco['rua'] }}, {{ $endereco['numero'] }}</strong>
                                            @if(isset($endereco['complemento']) && $endereco['complemento'])
                                                <br><small>{{ $endereco['complemento'] }}</small>
                                            @endif
                                        </div>
                                        <div class="mb-2">
                                            <i class="bi bi-building me-2"></i>
                                            {{ $endereco['bairro'] }}
                                        </div>
                                        <div class="mb-2">
                                            <i class="bi bi-geo me-2"></i>
                                            {{ $endereco['cidade'] }} - {{ $endereco['estado'] }}
                                        </div>
                                        <div>
                                            <i class="bi bi-postcard me-2"></i>
                                            CEP: {{ $endereco['cep'] }}
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-3">
                                        <i class="bi bi-question-circle text-muted" style="font-size: 2rem;"></i>
                                        <p class="text-muted mt-2">Endereço não disponível</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Informações de Pagamento -->
                        <div class="card mb-4">
                            <div class="card-header bg-white border-bottom-0">
                                <h5 class="section-title mb-0">
                                    <i class="bi bi-credit-card me-2"></i>
                                    Pagamento
                                </h5>
                            </div>
                            <div class="card-body">
                                @if($pedido->pagamentoCheckout)
                                                            <div class="mb-3">
                                                                <div class="info-label">Método de Pagamento</div>
                                                                <div class="info-value">
                                                                    <i
                                                                        class="bi bi-{{ $pedido->pagamentoCheckout->metodo_pagamento == 'cartao' ? 'credit-card' : 'bank' }} me-2"></i>
                                                                    {{ ucfirst($pedido->pagamentoCheckout->metodo_pagamento) }}
                                                                </div>
                                                            </div>

                                                            <div class="mb-3">
                                                                <div class="info-label">Status do Pagamento</div>
                                                                <div class="info-value">
                                                                    <span
                                                                        class="payment-status-badge {{
                                    $pedido->pagamentoCheckout->status === 'pago' ? 'bg-success' :
                                    ($pedido->pagamentoCheckout->status === 'pendente' ? 'bg-danger' : 'bg-secondary')
                                                                                                                                                                                        }}">
                                                                        {{ ucfirst($pedido->pagamentoCheckout->status) }}
                                                                    </span>
                                                                </div>
                                                            </div>

                                                            @if($pedido->pagamentoCheckout->codigo_transacao)
                                                                <div class="mb-3">
                                                                    <div class="info-label">Código da Transação</div>
                                                                    <div class="info-value">
                                                                        <code class="tracking-code">
                                                                                                                                                                                                                    {{ $pedido->pagamentoCheckout->codigo_transacao }}
                                                                                                                                                                                                                </code>
                                                                    </div>
                                                                </div>
                                                            @endif
                                @else
                                    <div class="text-center py-3">
                                        <i class="bi bi-exclamation-circle text-warning" style="font-size: 2rem;"></i>
                                        <p class="text-muted mt-2">Informações de pagamento não disponíveis</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Gestão de Entrega -->
                        <div class="card">
                            <div class="card-header bg-white border-bottom-0">
                                <h5 class="section-title mb-0">
                                    <i class="bi bi-clipboard-check me-2"></i>
                                    Gestão da Entrega
                                </h5>
                            </div>
                            <div class="card-body">
                                <!-- Informações da Entrega -->
                                <div class="mb-4">
                                    <div class="info-label">Método de Entrega</div>
                                    <div class="info-value">
                                        {{ ucfirst($pedido->entrega->metodo_entrega ?? 'Não definido') }}
                                    </div>

                                    <div class="info-label mt-3">Valor do Frete</div>
                                    <div class="info-value">
                                        R$ {{ number_format($pedido->entrega->valor_entrega ?? 0, 2, ',', '.') }}
                                    </div>
                                </div>

                                <!-- Atualizar Status -->
                                @if($pedido->status !== 'cancelado' && $pedido->podeSerAlteradoPeloAdministrador())
                                    <form action="{{ route('adm.atualizar_status_entrega', $pedido->id_pedido) }}"
                                        method="POST" class="mb-4">
                                        @csrf
                                        <div class="info-label mb-2">Atualizar Status</div>
                                        <div class="input-group mb-3">
                                            <select class="form-select" id="status_entrega" name="status_entrega" required>
                                                <option value="processando" {{ $pedido->status == 'processando' ? 'selected' : '' }}>Processando</option>
                                                <option value="enviado" {{ $pedido->status == 'enviado' ? 'selected' : '' }}>
                                                    Enviado</option>
                                                <option value="em_transito" {{ $pedido->status == 'em_transito' ? 'selected' : '' }}>Em Trânsito</option>
                                                <option value="saiu_para_entrega" {{ $pedido->status == 'saiu_para_entrega' ? 'selected' : '' }}>Saiu para Entrega</option>
                                                <option value="entregue" {{ $pedido->status == 'entregue' ? 'selected' : '' }}>Entregue</option>
                                                <option value="cancelado" {{ $pedido->status == 'cancelado' ? 'selected' : '' }}>Cancelar Pedido</option>
                                            </select>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </div>
                                    </form>
                                @elseif($pedido->status == 'cancelado')
                                    <div class="alert alert-warning mb-4">
                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                        <strong>Pedido Cancelado</strong> - O status não pode ser alterado.
                                    </div>
                                @else
                                    <div class="alert alert-info mb-4">
                                        <i class="bi bi-info-circle me-2"></i>
                                        <strong>Status Final</strong> - Este pedido está em um status final e não pode ser
                                        alterado.
                                    </div>
                                @endif

                                <!-- Rastreamento -->
                                <form action="{{ route('adm.adicionar_rastreio', $pedido->id_pedido) }}" method="POST">
                                    @csrf
                                    <div class="info-label mb-2">Código de Rastreio</div>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="codigo_rastreio"
                                            name="codigo_rastreio"
                                            value="{{ $pedido->entrega->rastreio->codigo_rastreio ?? old('codigo_rastreio') }}"
                                            placeholder="AB123456789BR" required>
                                        <button type="submit" class="btn btn-success">
                                            <i class="bi bi-save"></i>
                                        </button>
                                    </div>
                                    @if($pedido->entrega && $pedido->entrega->rastreio)
                                        <div class="alert alert-info py-2">
                                            <i class="bi bi-info-circle me-2"></i>
                                            Código salvo: <strong>{{ $pedido->entrega->rastreio->codigo_rastreio }}</strong>
                                        </div>
                                    @endif
                                </form>

                                <!-- Datas Importantes -->
                                @if($pedido->entrega)
                                    <hr class="my-3">
                                    <div class="row">
                                        <div class="col-6">
                                            <small class="text-muted d-block">Data de Envio</small>
                                            <strong>{{ $pedido->entrega?->data_envio?->format('d/m/Y') ?? '--' }}</strong>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">Data de Entrega</small>
                                            <strong>{{ $pedido->entrega?->data_entrega?->format('d/m/Y') ?? '--' }}</strong>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-expand textarea for notes
        document.addEventListener('DOMContentLoaded', function () {
            const statusSelect = document.getElementById('status_entrega');
            const trackingInput = document.getElementById('codigo_rastreio');

            // Status change animation
            if (statusSelect) {
                statusSelect.addEventListener('change', function () {
                    this.style.transform = 'scale(1.02)';
                    setTimeout(() => {
                        this.style.transform = 'scale(1)';
                    }, 200);
                });
            }

            // Tracking input validation
            if (trackingInput) {
                trackingInput.addEventListener('input', function (e) {
                    this.value = this.value.toUpperCase();
                });
            }

            // Print functionality
            document.querySelector('[onclick="window.print()"]').addEventListener('click', function () {
                setTimeout(() => {
                    window.print();
                }, 100);
            });
        });
    </script>
</body>

</html>