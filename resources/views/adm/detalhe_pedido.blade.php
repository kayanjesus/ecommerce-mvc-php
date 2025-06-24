<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Pedido - Cantinho da Isa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/adm/pedidosdetalhe.css') }}">
    <style>
        /* Estilos adicionais para a página de detalhes */
        .card-header-expanded {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .section-title-underline {
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .status-badge-lg {
            font-size: 1.1em;
            padding: 0.5em 1em;
            border-radius: 0.5rem;
        }

        .info-block {
            background-color: #f8f9fa;
            padding: 1.5rem;
            border-radius: 0.5rem;
            border: 1px solid #e9ecef;
        }

        .info-block h6 {
            color: #495057;
            margin-bottom: 1rem;
        }

        .info-block p {
            margin-bottom: 0.5rem;
        }

        .item-detail-card {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border: 1px solid #e9ecef;
            border-radius: 0.5rem;
            background-color: #fff;
            margin-bottom: 1rem;
        }

        .item-detail-card img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 0.25rem;
        }

        .item-detail-card .item-info {
            flex-grow: 1;
        }

        .item-detail-card .item-name {
            font-weight: bold;
            color: #343a40;
        }

        .item-detail-card .item-qty-price {
            font-size: 0.9em;
            color: #6c757d;
        }

        .form-status-rastreio {
            padding: 20px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            margin-bottom: 20px;
            background-color: #fff;
        }

        .form-status-rastreio h5 {
            margin-bottom: 15px;
            color: #343a40;
        }

        .form-status-rastreio .form-group {
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <header class="main-header">
        <a href="{{ route('home.index') }}" class="header-brand">
            CANTINHO DA ISA
        </a>
    </header>

    <div class="admin-wrapper">
        <!-- <aside class="sidebar">
            <div class="user-info">
                <label for="profile-img" class="profile-icon">
                    <i class="fas fa-user-circle"></i> {{-- Ícone de usuário mais moderno --}}
                </label>
                <input type="text" id="username" value="{{ Auth::user()->email }}" readonly />
            </div>
            <nav class="menu">
                <a href="{{ route('adm.dashboard') }}">
                    <button class="menu-btn">Inicial</button>
                </a>
                <a href="{{ route('adm.pedidos') }}">
                    <button class="menu-btn active">Pedidos</button>
                </a>
                <a href="{{ route('adm.pdtestoque') }}">
                    <button class="menu-btn">Produtos e Estoque</button>
                </a>
                <a href="{{ route('adm.cdtproduto') }}">
                    <button class="menu-btn">Cadastro de Produtos</button>
                </a>
                <a href="{{ route('adm.usercadastrado') }}">
                    <button class="menu-btn">Usuários Cadastrados</button>
                </a>
                <a href="{{ route('adm.vendas') }}">
                    <button class="menu-btn">Vendas</button>
                </a>
            </nav>
            <form method="POST" class="logout-form" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">SAIR</button>
            </form>
        </aside> -->

        <main class="content-area">
            <section class="pedidos-section">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="section-title mb-0">Detalhes do Pedido #{{ $pedido->id_pedido }}</h3>
                    <a href="{{ route('adm.pedidos') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-2"></i> Voltar para Pedidos
                    </a>
                </div>

                @if(session('sucesso'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('sucesso') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session('erro'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('erro') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="row">
                    <div class="col-lg-8">
                        <div class="card mb-4 shadow-sm">
                            <div class="card-header-expanded">
                                <h5 class="mb-0">Informações Gerais do Pedido</h5>
                                <span class="badge status-badge-lg {{
    $pedido->status === 'pago' ? 'bg-success' :
    ($pedido->status === 'processando' ? 'bg-warning text-dark' :
        ($pedido->status === 'enviado' ? 'bg-info' :
            ($pedido->status === 'entregue' ? 'bg-primary' : 'bg-secondary')))
                                }}">
                                    Status: {{ ucfirst($pedido->status) }}
                                </span>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Comprador:</strong>
                                            {{ $pedido->usuario->name ?? 'Usuário Desconhecido' }}</p>
                                        <p><strong>Email:</strong> {{ $pedido->usuario->email ?? 'N/A' }}</p>

                                        {{ $pedido->usuario->telefone ?? 'Não informado' }}</p>
                                        <p><strong>Data do Pedido:</strong>
                                            {{ $pedido->data_pedido?->format('d/m/Y H:i') ?? 'Data não informada' }}</p>
                                        <p><strong>Observações:</strong> {{ $pedido->observacoes ?? 'Nenhuma' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Total do Pedido:</strong> <span class="text-success fs-5">R$
                                                {{ number_format($pedido->total, 2, ',', '.') }}</span></p>
                                        @if($pedido->pagamentoCheckout)
                                                                            <p><strong>Método de Pagamento:</strong>
                                                                                {{ ucfirst($pedido->pagamentoCheckout->metodo_pagamento) }}</p>
                                                                            <p><strong>Status Pagamento:</strong> <span
                                                                                    class="badge {{
                                            $pedido->pagamentoCheckout->status === 'pago' ? 'bg-success' :
                                            ($pedido->pagamentoCheckout->status === 'pendente' ? 'bg-danger' : 'bg-secondary')
                                                                                                                                                                                            }}">{{ ucfirst($pedido->pagamentoCheckout->status) }}</span>
                                                                            </p>
                                                                            <p><strong>Cód. Transação PagSeguro:</strong>
                                                                                {{ $pedido->pagamentoCheckout->codigo_transacao ?? 'N/A' }}</p>
                                        @else
                                            <p><strong>Informações de Pagamento:</strong> Não disponíveis</p>
                                        @endif
                                    </div>
                                </div>

                                <h5 class="mt-4 section-title-underline">Itens do Pedido</h5>
                                <div class="items-list-detail">
                                    @forelse($pedido->itens as $item)
                                        <div class="item-detail-card">
                                            @php
                                                $mainImage = $item->produto && $item->produto->imagens->isNotEmpty() ? ($item->produto->imagens->firstWhere('principal', true) ?: $item->produto->imagens->first()) : null;
                                            @endphp
                                            @if($mainImage)
                                                <img src="{{ asset($mainImage->caminho) }}"
                                                    alt="{{ $item->produto->nome_produto ?? 'Produto' }}" class="img-fluid">
                                            @else
                                                <div class="d-flex align-items-center justify-content-center bg-light text-muted"
                                                    style="width: 80px; height: 80px; border-radius: 0.25rem;">
                                                    <i class="fas fa-image fa-2x"></i>
                                                </div>
                                            @endif
                                            <div class="item-info">
                                                <div class="item-name">
                                                    {{ $item->produto->nome_produto ?? 'Produto Indisponível' }}
                                                </div>
                                                <div class="item-qty-price">
                                                    Qtd: {{ $item->quantidade }} | Preço Unit.: R$
                                                    {{ number_format($item->preco_unitario, 2, ',', '.') }}
                                                </div>
                                                @if($item->cor || $item->tamanho)
                                                    <small class="text-muted">
                                                        ({{ $item->cor->nome ?? '' }}{{ $item->cor && $item->tamanho ? ', ' : '' }}{{ $item->tamanho->nome ?? '' }})
                                                    </small>
                                                @endif
                                            </div>
                                            <div class="item-total">
                                                <strong>R$
                                                    {{ number_format($item->quantidade * $item->preco_unitario, 2, ',', '.') }}</strong>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-muted">Nenhum item encontrado para este pedido.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        {{-- ENDEREÇO DE ENTREGA --}}
                        <div class="card mb-4 shadow-sm">
                            <div class="card-header-expanded">
                                <h5 class="mb-0">Endereço de Entrega</h5>
                            </div>
                            <div class="card-body info-block">
                                @php
                                    $endereco = json_decode($pedido->endereco_entrega, true);
                                @endphp
                                @if($endereco)
                                    <p>{{ $endereco['rua'] }}, {{ $endereco['numero'] }}
                                        {{ $endereco['complemento'] ? ' - ' . $endereco['complemento'] : '' }}
                                    </p>
                                    <p>{{ $endereco['bairro'] }}</p>
                                    <p>{{ $endereco['cidade'] }} - {{ $endereco['estado'] }}, {{ $endereco['cep'] }}</p>
                                @else
                                    <p class="text-muted">Endereço não disponível.</p>
                                @endif
                            </div>
                        </div>

                        {{-- GESTÃO DE ENTREGA --}}
                        <div class="card mb-4 shadow-sm">
                            <div class="card-header-expanded">
                                <h5 class="mb-0">Gestão de Entrega</h5>
                            </div>
                            <div class="card-body">
                                <p><strong>Método de Entrega:</strong>
                                    {{ ucfirst($pedido->entrega->metodo_entrega ?? 'Não definido') }}</p>
                                <p><strong>Valor do Frete:</strong> R$
                                    {{ number_format($pedido->entrega->valor_entrega ?? 0, 2, ',', '.') }}
                                </p>
                                {{-- Linha 297: Data de Envio --}}
                                <p><strong>Data de Envio:</strong>
                                    {{ $pedido->entrega?->data_envio?->format('d/m/Y H:i') ?? 'Não enviado' }}
                                </p>

                                {{-- Linha 300: Data de Entrega --}}
                                <p><strong>Data de Entrega:</strong>
                                    {{ $pedido->entrega?->data_entrega?->format('d/m/Y H:i') ?? 'Não entregue' }}
                                </p>
                                <p><strong>Código de Rastreio:</strong>
                                    @if($pedido->entrega && $pedido->entrega->rastreio)
                                        <span
                                            class="badge bg-secondary">{{ $pedido->entrega->rastreio->codigo_rastreio }}</span>
                                    @else
                                        N/A
                                    @endif
                                </p>

                                <hr class="my-3">

                                {{-- FORMULÁRIO DE ATUALIZAÇÃO DE STATUS --}}
                                <form action="{{ route('adm.atualizar_status_entrega', $pedido->id_pedido) }}"
                                    method="POST" class="form-status-rastreio">
                                    @csrf
                                    <h5 class="mb-3">Atualizar Status da Entrega</h5>
                                    <div class="form-group mb-3">
                                        <label for="status_entrega" class="form-label">Status do Pedido:</label>
                                        <select class="form-select" id="status_entrega" name="status_entrega" required>
                                            <option value="processando" {{ $pedido->status == 'processando' ? 'selected' : '' }}>Processando</option>
                                            <option value="enviado" {{ $pedido->status == 'enviado' ? 'selected' : '' }}>
                                                Enviado</option>
                                            <option value="em_transito" {{ $pedido->status == 'em_transito' ? 'selected' : '' }}>Em Trânsito</option>
                                            <option value="saiu_para_entrega" {{ $pedido->status == 'saiu_para_entrega' ? 'selected' : '' }}>Saiu para Entrega</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Atualizar Status</button>
                                </form>

                                {{-- FORMULÁRIO DE CÓDIGO DE RASTREIO --}}
                                <form action="{{ route('adm.adicionar_rastreio', $pedido->id_pedido) }}" method="POST"
                                    class="form-status-rastreio">
                                    @csrf
                                    <h5 class="mb-3">Adicionar/Atualizar Rastreio</h5>
                                    <div class="form-group mb-3">
                                        <label for="codigo_rastreio" class="form-label">Código de Rastreio:</label>
                                        <input type="text" class="form-control" id="codigo_rastreio"
                                            name="codigo_rastreio"
                                            value="{{ $pedido->entrega->rastreio->codigo_rastreio ?? old('codigo_rastreio') }}"
                                            placeholder="Ex: AB123456789BR" required>
                                    </div>
                                    <button type="submit" class="btn btn-success w-100">Salvar Rastreio</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    {{-- Se você tiver 'pedidos.js' e 'app.js' que não conflitem, mantenha-os --}}
    <script src="{{ asset('js/adm/pedidos.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>