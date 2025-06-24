<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Pedido - Cantinho da Isa</title>
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
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0"><i class="bi bi-receipt"></i> Detalhes do Pedido #{{ $pedido->id_pedido }}</h2>
                    <div>
                        <a href="{{ route('adm.pedidos') }}" class="btn btn-outline-secondary me-2">
                            <i class="bi bi-arrow-left"></i> Voltar
                        </a>
                    </div>
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
                
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="card mb-4">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0">Informações Gerais do Pedido</h5>
                                        <span class="badge {{
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
                                                <p><strong>Comprador:</strong> {{ $pedido->usuario->name ?? 'Usuário Desconhecido' }}</p>
                                                <p><strong>Email:</strong> {{ $pedido->usuario->email ?? 'N/A' }}</p>
                                                <p><strong>Telefone:</strong> {{ $pedido->usuario->telefone ?? 'Não informado' }}</p>
                                                <p><strong>Data do Pedido:</strong> {{ $pedido->data_pedido?->format('d/m/Y H:i') ?? 'Data não informada' }}</p>
                                                <p><strong>Observações:</strong> {{ $pedido->observacoes ?? 'Nenhuma' }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Total do Pedido:</strong> <span class="text-success fs-5">R$ {{ number_format($pedido->total, 2, ',', '.') }}</span></p>
                                                @if($pedido->pagamentoCheckout)
                                                    <p><strong>Método de Pagamento:</strong> {{ ucfirst($pedido->pagamentoCheckout->metodo_pagamento) }}</p>
                                                    <p><strong>Status Pagamento:</strong> <span class="badge {{
                                                        $pedido->pagamentoCheckout->status === 'pago' ? 'bg-success' :
                                                        ($pedido->pagamentoCheckout->status === 'pendente' ? 'bg-danger' : 'bg-secondary')
                                                    }}">{{ ucfirst($pedido->pagamentoCheckout->status) }}</span></p>
                                                    <p><strong>Cód. Transação PagSeguro:</strong> {{ $pedido->pagamentoCheckout->codigo_transacao ?? 'N/A' }}</p>
                                                @else
                                                    <p><strong>Informações de Pagamento:</strong> Não disponíveis</p>
                                                @endif
                                            </div>
                                        </div>

                                        <h5 class="mt-4">Itens do Pedido</h5>
                                        <div class="items-list-detail">
                                            @forelse($pedido->itens as $item)
                                                <div class="item-detail-card">
                                                    @php
                                                        $mainImage = $item->produto && $item->produto->imagens->isNotEmpty() ? 
                                                            ($item->produto->imagens->firstWhere('principal', true) ?: $item->produto->imagens->first()) : null;
                                                    @endphp
                                                    @if($mainImage)
                                                        <img src="{{ asset($mainImage->caminho) }}" alt="{{ $item->produto->nome_produto ?? 'Produto' }}" class="img-fluid">
                                                    @else
                                                        <div class="d-flex align-items-center justify-content-center bg-light text-muted" style="width: 80px; height: 80px; border-radius: 0.25rem;">
                                                            <i class="fas fa-image fa-2x"></i>
                                                        </div>
                                                    @endif
                                                    <div class="item-info">
                                                        <div class="item-name">
                                                            {{ $item->produto->nome_produto ?? 'Produto Indisponível' }}
                                                        </div>
                                                        <div class="item-qty-price">
                                                            Qtd: {{ $item->quantidade }} | Preço Unit.: R$ {{ number_format($item->preco_unitario, 2, ',', '.') }}
                                                        </div>
                                                        @if($item->cor || $item->tamanho)
                                                            <small class="text-muted">
                                                                ({{ $item->cor->nome ?? '' }}{{ $item->cor && $item->tamanho ? ', ' : '' }}{{ $item->tamanho->nome ?? '' }})
                                                            </small>
                                                        @endif
                                                    </div>
                                                    <div class="item-total">
                                                        <strong>R$ {{ number_format($item->quantidade * $item->preco_unitario, 2, ',', '.') }}</strong>
                                                    </div>
                                                </div>
                                            @empty
                                                <p class="text-muted">Nenhum item encontrado para este pedido.</p>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="card mb-4">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0">Endereço de Entrega</h5>
                                    </div>
                                    <div class="card-body">
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

                                <div class="card mb-4">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0">Gestão de Entrega</h5>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Método de Entrega:</strong> {{ ucfirst($pedido->entrega->metodo_entrega ?? 'Não definido') }}</p>
                                        <p><strong>Valor do Frete:</strong> R$ {{ number_format($pedido->entrega->valor_entrega ?? 0, 2, ',', '.') }}</p>
                                        <p><strong>Data de Envio:</strong> {{ $pedido->entrega?->data_envio?->format('d/m/Y H:i') ?? 'Não enviado' }}</p>
                                        <p><strong>Data de Entrega:</strong> {{ $pedido->entrega?->data_entrega?->format('d/m/Y H:i') ?? 'Não entregue' }}</p>
                                        <p><strong>Código de Rastreio:</strong>
                                            @if($pedido->entrega && $pedido->entrega->rastreio)
                                                <span class="badge bg-secondary">{{ $pedido->entrega->rastreio->codigo_rastreio }}</span>
                                            @else
                                                N/A
                                            @endif
                                        </p>

                                        <hr class="my-3">

                                        <form action="{{ route('adm.atualizar_status_entrega', $pedido->id_pedido) }}" method="POST">
                                            @csrf
                                            <h5 class="mb-3">Atualizar Status da Entrega</h5>
                                            <div class="form-group mb-3">
                                                <label for="status_entrega" class="form-label">Status do Pedido:</label>
                                                <select class="form-select" id="status_entrega" name="status_entrega" required>
                                                    <option value="processando" {{ $pedido->status == 'processando' ? 'selected' : '' }}>Processando</option>
                                                    <option value="enviado" {{ $pedido->status == 'enviado' ? 'selected' : '' }}>Enviado</option>
                                                    <option value="em_transito" {{ $pedido->status == 'em_transito' ? 'selected' : '' }}>Em Trânsito</option>
                                                    <option value="saiu_para_entrega" {{ $pedido->status == 'saiu_para_entrega' ? 'selected' : '' }}>Saiu para Entrega</option>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-primary w-100">Atualizar Status</button>
                                        </form>

                                        <form action="{{ route('adm.adicionar_rastreio', $pedido->id_pedido) }}" method="POST" class="mt-3">
                                            @csrf
                                            <h5 class="mb-3">Adicionar/Atualizar Rastreio</h5>
                                            <div class="form-group mb-3">
                                                <label for="codigo_rastreio" class="form-label">Código de Rastreio:</label>
                                                <input type="text" class="form-control" id="codigo_rastreio" name="codigo_rastreio"
                                                    value="{{ $pedido->entrega->rastreio->codigo_rastreio ?? old('codigo_rastreio') }}"
                                                    placeholder="Ex: AB123456789BR" required>
                                            </div>
                                            <button type="submit" class="btn btn-success w-100">Salvar Rastreio</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/adm/pedidos.js') }}"></script>
</body>
</html>