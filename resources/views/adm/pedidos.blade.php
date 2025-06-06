<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cantinho da Isa - Pedidos</title>
    <link rel="stylesheet" href="{{ asset('css/adm/pedidos.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <header>
        <a href="{{ route('home.index') }}" class="botao-link">
            CANTINHO DA ISA
        </a>
    </header>

    <div class="container">
        <aside class="sidebar">
            <div class="user-info">
                <label for="profile-img" class="profile-icon">
                    <i class="fas fa-user"></i>
                </label>
                <input type="text" id="profile-img" accept="image/*" style="display:none">
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
                    <button class="menu-btn">Produtos e estoque</button>
                </a>
                <a href="{{ route('adm.cdtproduto') }}">
                    <button class="menu-btn">Cadastro de produtos</button>
                </a>
                <a href="{{ route('adm.usercadastrado') }}">
                    <button class="menu-btn">Usuários cadastrados</button>
                </a>
                <a href="{{ route('adm.vendas') }}">
                    <button class="menu-btn">Vendas</button>
                </a>
            </nav>
            <form method="POST" class="logout" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout">SAIR</button>
            </form>
        </aside>

        {{-- resources/views/admin/pedidos.blade.php --}}

        <main class="conteudo">
            <section class="admin-section">
                <h3 class="mb-4">Pedidos Pagos e em Andamento</h3>
                @if(session('sucesso'))
                    <div class="alert alert-success">{{ session('sucesso') }}</div>
                @endif
                @if(session('erro'))
                    <div class="alert alert-danger">{{ session('erro') }}</div>
                @endif

                @if($pedidos->count() > 0)
                    @foreach($pedidos as $pedido)
                        <div class="pedido-card">
                            <div class="pedido-header">
                                <div>
                                    <span class="pedido-id">#{{ $pedido->id_pedido }}</span>
                                    <h5 class="cliente-nome">{{ $pedido->usuario->name }}</h5>
                                </div>
                                <span class="badge status-{{ $pedido->status }}">
                                    {{ ucfirst($pedido->status) }}
                                </span>
                                @if($pedido->pagamento)
                                    <span class="badge bg-info ms-2">
                                        Pagamento: {{ ucfirst($pedido->pagamento->status) }}
                                    </span>
                                @endif
                            </div>

                            <div class="pedido-info">
                                <p class="data-pedido">
                                    <i class="fas fa-calendar-alt"></i> {{ $pedido->created_at->format('d/m/Y') }}
                                    <span class="hora-pedido"><i class="fas fa-clock"></i>
                                        {{ $pedido->created_at->format('H:i') }}</span>
                                </p>

                                <div class="itens-pedido">
                                    <ul>
                                        @foreach($pedido->itens as $item)
                                            <li>
                                                {{ $item->produto->nome_produto }} -
                                                {{ $item->quantidade }} × R$ {{ number_format($item->preco_unitario, 2, ',', '.') }}
                                                @if($item->cor || $item->tamanho)
                                                    <span class="variacoes">
                                                        ({{ $item->cor }}{{ $item->cor && $item->tamanho ? ', ' : '' }}{{ $item->tamanho }})
                                                    </span>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                <div class="pedido-footer">
                                    <div class="total-pedido">
                                        <strong>Total:</strong> R$ {{ number_format($pedido->total, 2, ',', '.') }}
                                    </div>

                                    <div class="pedido-actions">
                                        {{-- Apenas mostrar opções de envio se o pagamento estiver 'pago' --}}
                                        @if($pedido->pagamento && $pedido->pagamento->status === 'pago')
                                            @if($pedido->status === 'pago' || $pedido->status === 'processando')
                                                <form action="{{ route('adm.pedidos.alterar-status', $pedido->id_pedido) }}"
                                                    method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="processando">
                                                    <button type="submit" class="btn btn-warning btn-sm">
                                                        <i class="fas fa-spinner"></i> Processando Envio
                                                    </button>
                                                </form>
                                            @endif
                                            @if($pedido->status === 'processando' || $pedido->status === 'pago')
                                                <form action="{{ route('adm.pedidos.alterar-status', $pedido->id_pedido) }}"
                                                    method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="enviado">
                                                    <button type="submit" class="btn btn-info btn-sm">
                                                        <i class="fas fa-truck"></i> Marcar como Enviado
                                                    </button>
                                                </form>
                                            @endif
                                            @if($pedido->status === 'enviado')
                                                <form action="{{ route('adm.pedidos.alterar-status', $pedido->id_pedido) }}"
                                                    method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="entregue">
                                                    <button type="submit" class="btn btn-success btn-sm">
                                                        <i class="fas fa-box"></i> Marcar como Entregue
                                                    </button>
                                                </form>
                                            @endif
                                        @else
                                            <span class="text-danger small">Aguardando Confirmação de Pagamento</span>
                                        @endif

                                        {{-- Botão de endereço sempre visível --}}
                                        <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#endereco{{ $pedido->id_pedido }}">
                                            <i class="fas fa-map-marker-alt"></i> Endereço
                                        </button>
                                    </div>
                                </div>

                                <div class="collapse mt-2" id="endereco{{ $pedido->id_pedido }}">
                                    <div class="endereco-card">
                                        @php
                                            $endereco = is_array($pedido->endereco_entrega)
                                                ? $pedido->endereco_entrega
                                                : json_decode($pedido->endereco_entrega, true);
                                        @endphp
                                        <p><i class="fas fa-road"></i> <strong>Endereço:</strong> {{ $endereco['rua'] }},
                                            {{ $endereco['numero'] }}
                                        </p>
                                        @if(!empty($endereco['complemento']))
                                            <p><i class="fas fa-home"></i> <strong>Complemento:</strong>
                                                {{ $endereco['complemento'] }}</p>
                                        @endif
                                        <p><i class="fas fa-map"></i> <strong>Bairro:</strong> {{ $endereco['bairro'] }}</p>
                                        <p><i class="fas fa-city"></i> <strong>Cidade/UF:</strong>
                                            {{ $endereco['cidade'] }}/{{ $endereco['estado'] }}</p>
                                        <p><i class="fas fa-mail-bulk"></i> <strong>CEP:</strong> {{ $endereco['cep'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="paginacao">
                        {{ $pedidos->links() }}
                    </div>
                @else
                    <div class="sem-pedidos">
                        <i class="fas fa-box-open"></i>
                        <p>Nenhum pedido pago ou em andamento encontrado</p>
                    </div>
                @endif
            </section>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/adm/pedidos.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>