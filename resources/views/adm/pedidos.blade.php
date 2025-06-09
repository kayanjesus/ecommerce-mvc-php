<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cantinho da Isa - Pedidos</title>
    {{-- Seu CSS personalizado deve vir DEPOIS do Bootstrap para sobrescrever --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/adm/pedidos.css') }}">
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
        </aside>

        <main class="content-area">
            <section class="pedidos-section">
                <h3 class="section-title mb-4">Pedidos Pagos e em Andamento</h3>

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

                @if($pedidos->count() > 0)
                    <div class="pedidos-grid">
                        @foreach($pedidos as $pedido)
                                    {{-- AQUI É A MUDANÇA: O CARD INTEIRO AGORA É UM LINK --}}
                                    <a href="{{ route('adm.detalhe_pedido', $pedido->id_pedido) }}" class="pedido-card-link">
                                        <div class="pedido-card shadow-sm"> {{-- Removido 'mb-4' daqui, a margem está no link --}}
                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h5 class="mb-0">Pedido #{{ $pedido->id_pedido }}</h5>
                                                    <small class="text-muted">{{ $pedido->usuario->name }}</small>
                                                </div>
                                                <div class="status-badges">
                                                    <span class="badge {{
                            $pedido->status === 'pago' ? 'bg-success' :
                            ($pedido->status === 'processando' ? 'bg-warning text-dark' :
                                ($pedido->status === 'enviado' ? 'bg-info' :
                                    ($pedido->status === 'entregue' ? 'bg-primary' : 'bg-secondary')))
                                                            }}">
                                                        {{ ucfirst($pedido->status) }}
                                                    </span>
                                                    @if($pedido->pagamentoCheckout)
                                                                            <span class="badge {{
                                                        $pedido->pagamentoCheckout->status === 'pago' ? 'bg-success' :
                                                        ($pedido->pagamentoCheckout->status === 'pendente' ? 'bg-danger' : 'bg-secondary')
                                                                                        }} ms-2">
                                                                                Pagamento: {{ ucfirst($pedido->pagamentoCheckout->status) }}
                                                                            </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="card-body">
                                                <p class="card-text text-muted small">
                                                    <i class="fas fa-calendar-alt me-1"></i> {{ $pedido->created_at->format('d/m/Y') }}
                                                    <i class="fas fa-clock ms-3 me-1"></i> {{ $pedido->created_at->format('H:i') }}
                                                </p>

                                                <div class="items-list">
                                                    <h6 class="mb-2">Itens principais:</h6> {{-- Alterado para "principais" --}}
                                                    <ul class="list-unstyled">
                                                        {{-- Exibir apenas 2 ou 3 itens para não sobrecarregar o card na listagem --}}
                                                        @foreach($pedido->itens->take(2) as $item) {{-- Limita a 2 itens --}}
                                                            <li>
                                                                {{ $item->quantidade }}x {{ $item->produto->nome_produto }}
                                                                @if($item->cor || $item->tamanho)
                                                                    <small class="text-muted">
                                                                        ({{ $item->cor->nome ?? '' }}{{ $item->cor && $item->tamanho ? ', ' : '' }}{{ $item->tamanho->nome ?? '' }})
                                                                    </small>
                                                                @endif
                                                            </li>
                                                        @endforeach
                                                        @if($pedido->itens->count() > 2)
                                                            <li><small class="text-muted">... e mais {{ $pedido->itens->count() - 2 }}
                                                                    item(ns)</small></li>
                                                        @endif
                                                    </ul>
                                                </div>

                                                <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                                    <div class="total-pedido">
                                                        <strong>Total:</strong> <span class="text-success fs-5">R$
                                                            {{ number_format($pedido->total, 2, ',', '.') }}</span>
                                                    </div>
                                                    {{-- Botões de ação e endereço REMOVIDOS daqui --}}
                                                    <span class="text-primary small">Ver Detalhes <i
                                                            class="fas fa-chevron-right ms-1"></i></span>
                                                </div>
                                            </div>
                                        </div> {{-- Fim do pedido-card --}}
                                    </a> {{-- Fim do pedido-card-link --}}
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $pedidos->links('pagination::bootstrap-5') }}
                    </div>
                @else
                    <div class="empty-state text-center py-5">
                        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                        <p class="text-muted fs-5">Nenhum pedido pago ou em andamento encontrado.</p>
                    </div>
                @endif
            </section>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/adm/pedidos.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>