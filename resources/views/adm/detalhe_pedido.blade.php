<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cantinho da Isa - Detalhes do Pedido #{{ $pedido->id_pedido }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/adm/pedidos.css') }}"> {{-- Reutiliza o CSS geral da página de pedidos
    --}}
    <link rel="stylesheet" href="{{ asset('css/adm/detalhe_pedido.css') }}"> {{-- Novo CSS para detalhes específicos
    --}}
</head>

<body>
    <header class="main-header">
        <a href="{{ route('home.index') }}" class="header-brand">
            CANTINHO DA ISA
        </a>
    </header>

    <div class="admin-wrapper">
        <aside class="sidebar">
            <div class="user-info">
                <label for="profile-img" class="profile-icon">
                    <i class="fas fa-user-circle"></i>
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
        </aside>

        <main class="content-area">
            <section class="detalhe-pedido-section">
                <a href="{{ route('adm.pedidos') }}" class="btn btn-secondary btn-sm mb-4">
                    <i class="fas fa-arrow-left me-2"></i> Voltar para Pedidos
                </a>

                <h3 class="section-title mb-4">Detalhes do Pedido #{{ $pedido->id_pedido }}</h3>

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

                <div class="row">
                    {{-- Coluna de Detalhes do Pedido --}}
                    <div class="col-lg-7 col-md-12 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Informações do Pedido</h5>
                            </div>
                            <div class="card-body">
                                <p class="card-text">
                                    <strong>Status do Pagamento:</strong>
                                    <span class="badge {{
    $pedido->status === 'pago' ? 'bg-success' :
    ($pedido->status === 'processando' ? 'bg-warning text-dark' :
        ($pedido->status === 'enviado' ? 'bg-info' :
            ($pedido->status === 'entregue' ? 'bg-primary' : 'bg-secondary')))
                                    }} ms-2">
                                        {{ ucfirst($pedido->status) }}
                                    </span>
                                </p>
                                <p class="card-text">
                                    <strong>Status do Pagamento:</strong>
                                    @if($pedido->pagamentoCheckout)
                                                                    <span class="badge {{
                                        $pedido->pagamentoCheckout->status === 'pago' ? 'bg-success' :
                                        ($pedido->pagamentoCheckout->status === 'pendente' ? 'bg-danger' : 'bg-secondary')
                                                                                                        }} ms-2">
                                                                        {{ ucfirst($pedido->pagamentoCheckout->status) }}
                                                                    </span>
                                    @else
                                        <span class="badge bg-secondary ms-2">Não Informado</span>
                                    @endif
                                </p>
                                <p class="card-text">
                                    <i class="fas fa-calendar-alt me-2"></i> <strong>Data do Pedido:</strong>
                                    {{ $pedido->created_at->format('d/m/Y H:i') }}
                                </p>
                                <p class="card-text fs-4 text-success">
                                    <i class="fas fa-dollar-sign me-2"></i> <strong>Total do Pedido:</strong> R$
                                    {{ number_format($pedido->total, 2, ',', '.') }}
                                </p>

                                <hr>
                                <h6 class="mb-3">Ações do Pedido:</h6>
                                <div class="pedido-actions d-flex flex-wrap gap-2">
                                    @if($pedido->pagamentoCheckout && $pedido->pagamentoCheckout->status === 'pago')
                                        @if($pedido->status === 'pago')
                                            <form action="{{ route('adm.pedidos.alterar-status', $pedido->id_pedido) }}"
                                                method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="processando">
                                                <button type="submit" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-spinner"></i> Processando Envio
                                                </button>
                                            </form>
                                        @elseif($pedido->status === 'processando')
                                            <form action="{{ route('adm.pedidos.alterar-status', $pedido->id_pedido) }}"
                                                method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="enviado">
                                                <button type="submit" class="btn btn-info btn-sm">
                                                    <i class="fas fa-truck"></i> Marcar como Enviado
                                                </button>
                                            </form>
                                        @elseif($pedido->status === 'enviado')
                                            <form action="{{ route('adm.pedidos.alterar-status', $pedido->id_pedido) }}"
                                                method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="entregue">
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <i class="fas fa-box"></i> Marcar como Entregue
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        <span class="text-danger small">Aguardando Confirmação de Pagamento para Alterar
                                            Status</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Coluna de Informações do Cliente e Endereço --}}
                    <div class="col-lg-5 col-md-12 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0">Informações do Cliente</h5>
                            </div>
                            <div class="card-body">
                                <p class="card-text"><i class="fas fa-user-circle me-2"></i> <strong>Nome:</strong>
                                    {{ $pedido->usuario->name }}</p>
                                <p class="card-text"><i class="fas fa-envelope me-2"></i> <strong>Email:</strong>
                                    {{ $pedido->usuario->email }}</p>
                                {{-- Adicione outros dados do usuário se disponíveis no modelo User, ex: telefone --}}
                                {{-- <p class="card-text"><i class="fas fa-phone me-2"></i> <strong>Telefone:</strong>
                                    {{ $pedido->usuario->telefone ?? 'N/A' }}</p> --}}

                                <hr>
                                <h6 class="mb-3">Endereço de Entrega:</h6>
                                <div class="address-details p-3 border rounded bg-light">
                                    @php
                                        // Garante que $endereco seja um array, decodificando se for uma string JSON
                                        $endereco = is_array($pedido->endereco_entrega)
                                            ? $pedido->endereco_entrega
                                            : json_decode($pedido->endereco_entrega, true);
                                    @endphp
                                    <p class="mb-1"><i class="fas fa-road me-2"></i> <strong>Rua:</strong>
                                        {{ $endereco['rua'] }}, {{ $endereco['numero'] }}</p>
                                    @if(!empty($endereco['complemento']))
                                        <p class="mb-1"><i class="fas fa-home me-2"></i> <strong>Complemento:</strong>
                                            {{ $endereco['complemento'] }}</p>
                                    @endif
                                    <p class="mb-1"><i class="fas fa-map-marker-alt me-2"></i> <strong>Bairro:</strong>
                                        {{ $endereco['bairro'] }}</p>
                                    <p class="mb-1"><i class="fas fa-city me-2"></i> <strong>Cidade/UF:</strong>
                                        {{ $endereco['cidade'] }}/{{ $endereco['estado'] }}</p>
                                    <p class="mb-0"><i class="fas fa-mail-bulk me-2"></i> <strong>CEP:</strong>
                                        {{ $endereco['cep'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Coluna de Itens do Pedido (pode ser movida para baixo se for muito longa) --}}
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0">Itens do Pedido</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($pedido->itens as $item)
                                        <div class="col-md-6 col-lg-4 mb-4">
                                            <div class="item-card d-flex align-items-center p-3 border rounded h-100">
                                                {{-- Lógica da imagem atualizada para buscar a principal --}}
                                                @if($item->produto && $item->produto->imagens->isNotEmpty())
                                                    @php
                                                        $mainImage = $item->produto->imagens->firstWhere('principal', true) ?: $item->produto->imagens->first();
                                                    @endphp
                                                    @if($mainImage)
                                                        <img src="{{ asset($mainImage->caminho) }}" {{--
                                                            Adicionado 'storage/' aqui, se necessário --}}
                                                            alt="{{ $item->produto->nome_produto ?? 'Produto' }}"
                                                            class="img-thumbnail me-3 item-image">
                                                    @else
                                                        <div class="img-thumbnail me-3 item-image d-flex align-items-center justify-content-center bg-light text-muted"
                                                            style="width: 64px; height: 64px;">
                                                            <i class="fas fa-image fa-2x"></i> {{-- Ícone maior para placeholder
                                                            --}}
                                                        </div>
                                                    @endif
                                                @else
                                                    {{-- Fallback para foto_produto, se ainda usar, ou placeholder padrão --}}
                                                    @if($item->produto && $item->produto->foto_produto)
                                                        <img src="{{ asset('storage/' . $item->produto->foto_produto) }}"
                                                            alt="{{ $item->produto->nome_produto }}"
                                                            class="img-thumbnail me-3 item-image">
                                                    @else
                                                        <div class="img-thumbnail me-3 item-image d-flex align-items-center justify-content-center bg-light text-muted"
                                                            style="width: 64px; height: 64px;">
                                                            <i class="fas fa-image fa-2x"></i>
                                                        </div>
                                                    @endif
                                                @endif
                                                {{-- Fim da lógica da imagem --}}

                                                <div class="item-details flex-grow-1">
                                                    <h6 class="mb-1">{{ $item->produto->nome_produto }}</h6>
                                                    <p class="mb-1 small text-muted">Quantidade: {{ $item->quantidade }}</p>
                                                    <p class="mb-1 small text-muted">
                                                        @if($item->cor) Cor: {{ $item->cor }} @endif
                                                        @if($item->tamanho) Tamanho: {{ $item->tamanho }} @endif
                                                    </p>
                                                    <p class="mb-0 fw-bold text-primary">R$
                                                        {{ number_format($item->preco_unitario, 2, ',', '.') }} (unit.)
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/adm/pedidos.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>