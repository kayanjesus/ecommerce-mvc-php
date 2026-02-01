<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cantinho da Isa</title>
    <link rel="stylesheet" href="{{ asset('css/perfil-user.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="header-top">
        <div class="social-links">
            <a href="https://www.instagram.com/cantinho_das_isas_?igsh=MXVjbDF6cDBpMjR4cw=="><i
                    class="fab fa-instagram fa-lg"></i></a>
            <a href="https://wa.me/5511999999999"><i class="fab fa-whatsapp fa-lg"></i></a>
        </div>
        <nav class="user-nav">
            <a href="{{ route('home.dashboard', ['show' => 'pedidos']) }}"><i class="fas fa-box fa-lg"></i> Meus
                pedidos</a>
            <a href="{{ route('home.dashboard', ['show' => 'favoritos']) }}"><i class="fas fa-heart fa-lg"></i>
                Favoritos</a>
            <a href="{{ route('pagamento.cep') }}"><i class="fas fa-shopping-cart fa-lg"></i> Carrinho</a>
        </nav>
    </div>

    <main>
        <div class="left-column">
            {{-- SEÇÃO DO USUÁRIO --}}
            <div class="user-section">
                <div class="user-icon"><i class="fas fa-user"></i></div>
                <span class="username">{{ Auth::user()->name }}</span>
                <a href="{{ route('profile.edit') }}" class="edit-btn" id="editProfileBtn">Editar</a>
                <form method="POST" class="logout-form" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="edit-btn">SAIR</button>
                </form>
            </div>

            {{-- BOTÕES DE NAVEGAÇÃO --}}
            <div class="button-section">
                <button class="nav-button @if ($currentView === 'pedidos') active @endif" data-view="pedidos"
                    onclick="window.location.href='{{ route('home.dashboard', ['show' => 'pedidos']) }}'">
                    Pedidos <span>&gt;</span>
                </button>
                <button class="nav-button @if ($currentView === 'favoritos') active @endif" data-view="favoritos"
                    onclick="window.location.href='{{ route('home.dashboard', ['show' => 'favoritos']) }}'">
                    Favoritos <span>&gt;</span>
                </button>
            </div>
        </div>

        <div class="logo">
            <a href="{{ route('home.index') }}">
                <img src="{{ asset('img/logo/ft_logo.png') }}" alt="Cantinho da Isa - Logo" class="logo-img">
            </a>
        </div>

        {{-- CONTEÚDO DINÂMICO --}}
        <div id="dynamic-content">
            <div class="content-view">
                @if ($currentView === 'pedidos')
                    <div class="pedidos-header">
                        <h2><i class="fas fa-box-open"></i> Meus Pedidos</h2>
                    </div>

                    @if ($pedidos->isEmpty())
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                            <h3>Nenhum pedido encontrado</h3>
                            <p>Você ainda não fez nenhum pedido em nossa loja.</p>
                            <a href="{{ url('/') }}" class="action-btn primary-btn">
                                <i class="fas fa-shopping-cart mr-2"></i> Começar a Comprar
                            </a>
                        </div>
                    @else
                        <div class="pedidos-list">
                            @foreach ($pedidos as $pedido)
                                <div class="pedido-card" data-status="{{ $pedido->status }}">
                                    {{-- HEADER: Info e Status do Pedido --}}
                                    <div class="pedido-header">
                                        <div class="pedido-info">
                                            <h3>Pedido #{{ $pedido->id_pedido }}</h3>
                                            <span class="pedido-date">
                                                <i class="far fa-calendar-alt"></i>
                                                {{ \Carbon\Carbon::parse($pedido->created_at)->format('d/m/Y') }}
                                            </span>
                                        </div>
                                        <div class="pedido-status {{ $pedido->status }}">
                                            <span>{{ ucfirst(str_replace('_', ' ', $pedido->status)) }}</span>
                                        </div>
                                    </div>

                                    {{-- BODY: Produtos e Total --}}
                                    <div class="pedido-body">
                                        <div class="pedido-produtos">
                                            {{-- Lista os primeiros 3 itens --}}
                                            @foreach ($pedido->itens->take(3) as $item)
                                                <div class="produto-item">
                                                    @if ($item->produto && $item->produto->imagens->isNotEmpty())
                                                        @php
                                                            $mainImage = $item->produto->imagens->firstWhere('principal', true) ?: $item->produto->imagens->first();
                                                        @endphp
                                                        <img src="{{ asset($mainImage->caminho) }}"
                                                            alt="{{ $item->produto->nome_produto ?? 'Produto' }}" class="produto-img">
                                                    @else
                                                        <div class="produto-img empty">
                                                            <i class="fas fa-image"></i>
                                                        </div>
                                                    @endif
                                                    <div class="produto-info">
                                                        <h4>{{ $item->produto->nome_produto ?? 'Produto Indisponível' }}
                                                        </h4>
                                                        <p>
                                                            Qtd: {{ $item->quantidade }} | R$
                                                            {{ number_format($item->preco_unitario, 2, ',', '.') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endforeach
                                            {{-- Indica se há mais itens --}}
                                            @if ($pedido->itens->count() > 3)
                                                <div class="mais-itens">
                                                    +{{ $pedido->itens->count() - 3 }} itens
                                                </div>
                                            @endif
                                        </div>

                                        <div class="pedido-total">
                                            <span>Total do pedido:</span>
                                            <strong>R$ {{ number_format($pedido->total, 2, ',', '.') }}</strong>
                                        </div>
                                    </div>

                                    {{-- FOOTER: Ações (Botões) --}}
                                    <div class="pedido-footer">
                                        <div class="pedido-actions">
                                            {{-- Botão de Cancelar --}}
                                            @if ($pedido->podeSerCanceladoPeloCliente())
                                                <form action="{{ route('cliente.pedidos.cancelar', $pedido->id_pedido) }}" method="POST"
                                                    onsubmit="return confirm('Tem certeza que deseja cancelar este pedido?');">
                                                    @csrf
                                                    <button type="submit" class="action-btn cancel-btn">
                                                        <i class="fas fa-times-circle"></i> Cancelar
                                                    </button>
                                                </form>
                                            @endif

                                            {{-- Botão de Confirmar Recebimento --}}
                                            @if ($pedido->podeConfirmarEntrega())
                                                <form action="{{ route('cliente.pedidos.confirmarEntrega', $pedido->id_pedido) }}"
                                                    method="POST" onsubmit="return confirm('Confirma o recebimento deste pedido?');">
                                                    @csrf
                                                    <button type="submit" class="action-btn confirm-btn">
                                                        <i class="fas fa-check-circle"></i> Confirmar Recebimento
                                                    </button>
                                                </form>
                                            @endif

                                            {{-- Botão de Ver Detalhes --}}
                                            <a href="{{ route('cliente.pedidos.verDetalhesPedido', $pedido->id_pedido) }}"
                                                class="action-btn details-btn">
                                                <i class="fas fa-eye"></i> Ver Detalhes
                                            </a>

                                            {{-- Botão de Avaliar Produto / Avaliado --}}
                                            @if ($pedido->status === 'entregue' && $pedido->confirmado_pelo_cliente)
                                                @if ($pedido->podeAvaliar())
                                                    <a href="{{ route('cliente.pedidos.avaliar.view', $pedido->id_pedido) }}"
                                                        class="action-btn primary-btn">
                                                        <i class="fas fa-star"></i> Avaliar Produto
                                                    </a>
                                                @else
                                                    <span class="action-btn primary-btn disabled-btn">
                                                        <i class="fas fa-star"></i> Avaliado
                                                    </span>
                                                @endif
                                            @endif

                                            {{-- Botão de Solicitar Reembolso (dentro do prazo) --}}
                                            @if ($pedido->status === 'entregue' && $pedido->podeSolicitarReembolso() && $pedido->prazoReembolsoRestante > 0)
                                                <a href="#" class="action-btn refund-btn" data-bs-toggle="modal"
                                                    data-bs-target="#reembolsoModal-{{ $pedido->id_pedido }}">
                                                    <i class="fas fa-undo"></i> Solicitar Reembolso
                                                </a>
                                            @elseif($pedido->status === 'entregue' && $pedido->prazoReembolsoRestante !== null && $pedido->prazoReembolsoRestante <= 0)
                                                <span class="action-btn disabled-refund-btn">
                                                    <i class="fas fa-info-circle"></i> Prazo de Reembolso Expirado
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Informações de Entrega/Rastreio --}}
                                    @if ($pedido->entrega)
                                        <div class="pedido-entrega">
                                            <div class="entrega-info">
                                                <i class="fas fa-truck"></i>
                                                <div>
                                                    <span>Enviado via {{ ucfirst($pedido->entrega->metodo_entrega) }}</span>
                                                    @if ($pedido->entrega->rastreio)
                                                        <small>Código de rastreio:
                                                            {{ $pedido->entrega->rastreio->codigo_rastreio }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="entrega-status">
                                                @if ($pedido->entrega->data_entrega)
                                                    <span>Entregue em
                                                        {{ $pedido->entrega->data_entrega->format('d/m/Y') }}</span>
                                                @elseif($pedido->entrega->data_envio)
                                                    <span>Enviado em
                                                        {{ $pedido->entrega->data_envio->format('d/m/Y') }}</span>
                                                @else
                                                    <span>Aguardando envio</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                {{-- Modal para Solicitar Reembolso (dentro do loop para cada pedido) --}}
                                <div class="modal fade" id="reembolsoModal-{{ $pedido->id_pedido }}" tabindex="-1"
                                    aria-labelledby="reembolsoModalLabel-{{ $pedido->id_pedido }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-lg shadow-xl p-6 border border-gray-200">
                                            <form action="{{ route('cliente.pedidos.solicitarReembolso', $pedido->id_pedido) }}"
                                                method="POST">
                                                @csrf
                                                <div class="modal-header border-b pb-4 mb-4 flex items-center justify-between">
                                                    <h5 class="modal-title font-bold text-2xl text-gray-800"
                                                        id="reembolsoModalLabel-{{ $pedido->id_pedido }}">
                                                        Solicitar Reembolso
                                                    </h5>
                                                    <button type="button"
                                                        class="btn-close text-gray-500 hover:text-gray-800 focus:outline-none"
                                                        data-bs-dismiss="modal" aria-label="Close">
                                                        <i class="fas fa-times text-lg"></i>
                                                    </button>
                                                </div>
                                                <div class="modal-body text-gray-700">
                                                    <p class="mb-4 text-lg">Você está prestes a solicitar um reembolso para
                                                        o pedido <span class="font-bold">#{{ $pedido->id_pedido }}</span>
                                                        no
                                                        valor de <span class="font-bold text-green-700">R$
                                                            {{ number_format($pedido->total, 2, ',', '.') }}</span>.
                                                    </p>

                                                    @if ($pedido->prazoReembolsoRestante !== null && $pedido->prazoReembolsoRestante > 0)
                                                        <p
                                                            class="text-sm text-blue-600 mb-4 bg-blue-50 p-3 rounded-md border border-blue-200 flex items-center">
                                                            <i class="fas fa-info-circle mr-2 text-lg"></i> Você tem
                                                            {{ $pedido->prazoReembolsoRestante }} dia(s) restantes
                                                            para solicitar o reembolso.
                                                        </p>
                                                    @elseif($pedido->prazoReembolsoRestante !== null && $pedido->prazoReembolsoRestante <= 0)
                                                        <p
                                                            class="text-sm text-red-600 mb-4 bg-red-50 p-3 rounded-md border border-red-200 flex items-center">
                                                            <i class="fas fa-exclamation-triangle mr-2 text-lg"></i> O
                                                            prazo para solicitar reembolso para este pedido expirou.
                                                        </p>
                                                    @endif

                                                    <div class="mb-4">
                                                        <label for="motivo_reembolso_{{ $pedido->id_pedido }}"
                                                            class="form-label font-semibold text-gray-700">
                                                            Motivo do Reembolso:
                                                        </label>
                                                        <textarea
                                                            class="form-control mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                            id="motivo_reembolso_{{ $pedido->id_pedido }}" name="motivo_reembolso"
                                                            rows="4" placeholder="Descreva brevemente o motivo do reembolso..."
                                                            required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer flex justify-end gap-2 border-t pt-4 mt-4">
                                                    <button type="button" class="action-btn secondary-btn" data-bs-dismiss="modal">
                                                        Cancelar
                                                    </button>
                                                    <button type="submit" class="action-btn primary-btn">
                                                        Confirmar Solicitação
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @elseif($currentView === 'favoritos')
                    <div class="favoritos-section">
                        <div class="favoritos-header">
                            <h2><i class="fas fa-heart"></i> Meus Favoritos</h2>
                            <div class="favoritos-count">
                                <span>{{ $favoritos->count() }} itens</span>
                            </div>
                        </div>

                        @if ($favoritos->isEmpty())
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-heart-broken"></i>
                                </div>
                                <h3>Seus favoritos estão vazios!</h3>
                                <p>Aproveite nossas promoções e adicione produtos que você amou aqui!</p>
                                <a href="{{ url('/') }}" class="action-btn primary-btn">
                                    <i class="fas fa-arrow-left mr-2"></i> Continuar Comprando
                                </a>
                            </div>
                        @else
                            <div class="favoritos-grid">
                                @foreach ($favoritos as $item)
                                    <div class="favorito-card">
                                        <div class="favorito-img-container">
                                            <img src="{{ asset($item->attributes->image) }}" alt="{{ $item->name }}"
                                                class="favorito-img">
                                            <form action="{{ route('home.removefavoritos') }}" method="POST"
                                                class="remove-favorito">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $item->id }}">
                                                <button type="submit" class="remove-btn" title="Remover dos favoritos">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        </div>
                                        <div class="favorito-info">
                                            <h3 class="favorito-title">{{ $item->name }}</h3>
                                            <p class="favorito-price">R$
                                                {{ number_format($item->price, 2, ',', '.') }}
                                            </p>
                                            <div class="favorito-actions">
                                                <a href="#" class="action-btn small-btn add-to-cart">
                                                    <i class="fas fa-shopping-cart"></i> Adicionar ao Carrinho
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="favoritos-footer">
                                <a href="{{ url('/') }}" class="action-btn primary-btn">
                                    <i class="fas fa-arrow-left mr-2"></i> Continuar Comprando
                                </a>
                                <form action="{{ route('home.limparfavoritos') }}" method="POST" class="inline-form">
                                    @csrf
                                    <button type="submit" class="action-btn cancel-btn"
                                        onclick="return confirm('Tem certeza que deseja limpar todos os favoritos?')">
                                        <i class="fas fa-trash-alt mr-2"></i> Limpar Favoritos
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../javascript/perfil-usuario.js"></script>
</body>

</html>