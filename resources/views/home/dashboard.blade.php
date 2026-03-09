<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Conta - Cantinho da Isa</title>
    <link rel="stylesheet" href="{{ asset('css/perfil-user.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- CSS dos popups -->
    <link rel="stylesheet" href="{{ asset('css/popups.css') }}">
</head>

<body>
    <div class="dashboard-container">
        <!-- Header do Dashboard -->
        <div class="dashboard-header">
            <div class="user-profile-main">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="user-info">
                    <h1>{{ Auth::user()->name }}</h1>
                    <p><i class="fas fa-envelope me-2"></i>{{ Auth::user()->email }}</p>
                    <div class="user-stats">
                        <div class="stat-item">
                            <span class="stat-number">{{ $pedidos->count() }}</span>
                            <span class="stat-label">Pedidos</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">{{ $favoritos->count() }}</span>
                            <span class="stat-label">Favoritos</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Conteúdo Principal -->
        <div class="dashboard-main">
            <!-- Sidebar de Navegação -->
            <div class="dashboard-sidebar">
                <div class="nav-section">
                    <div class="nav-section-title">Minha Conta</div>
                    <a href="{{ route('home.dashboard', ['show' => 'pedidos']) }}" 
                       class="nav-item @if ($currentView === 'pedidos') active @endif">
                        <i class="fas fa-shopping-bag"></i>
                        Meus Pedidos
                    </a>
                    <a href="{{ route('home.dashboard', ['show' => 'favoritos']) }}" 
                       class="nav-item @if ($currentView === 'favoritos') active @endif">
                        <i class="fas fa-heart"></i>
                        Meus Favoritos
                    </a>
                    <a href="{{ route('pagamento.cep') }}" class="nav-item">
                        <i class="fas fa-shopping-cart"></i>
                        Meu Carrinho
                    </a>
                    <a href="{{ route('profile.edit') }}" class="nav-item">
                        <i class="fas fa-user-edit"></i>
                        Editar Perfil
                    </a>
                </div>
                
                <div class="nav-section">
                    <div class="nav-section-title">Links Rápidos</div>
                    <a href="{{ url('/') }}" class="nav-item">
                        <i class="fas fa-home"></i>
                        Voltar à Loja
                    </a>
                    <a href="https://wa.me/5511999999999" class="nav-item" target="_blank">
                        <i class="fab fa-whatsapp"></i>
                        Suporte por WhatsApp
                    </a>
                    <a href="https://www.instagram.com/cantinho_das_isas_" class="nav-item" target="_blank">
                        <i class="fab fa-instagram"></i>
                        Nosso Instagram
                    </a>
                </div>
                
                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                    @csrf
                    <button type="button" class="logout-btn" onclick="confirmarLogout()">
                        <i class="fas fa-sign-out-alt"></i>
                        Sair da Conta
                    </button>
                </form>
            </div>

            <!-- Área de Conteúdo -->
            <div class="dashboard-content">
                @if ($currentView === 'pedidos')
                    <div class="content-header">
                        <h2><i class="fas fa-shopping-bag"></i> Meus Pedidos</h2>
                        <div class="content-actions">
                            <a href="{{ url('/') }}" class="action-btn btn-primary">
                                <i class="fas fa-plus-circle me-1"></i>
                                Novo Pedido
                            </a>
                        </div>
                    </div>

                    @if ($pedidos->isEmpty())
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                            <h3>Nenhum pedido encontrado</h3>
                            <p>Você ainda não fez nenhum pedido em nossa loja.</p>
                            <a href="{{ url('/') }}" class="action-btn btn-primary">
                                <i class="fas fa-shopping-cart me-2"></i> Começar a Comprar
                            </a>
                        </div>
                    @else
                        <div class="pedidos-list">
                            @foreach ($pedidos as $pedido)
                                <div class="pedido-card">
                                    <div class="pedido-card-header">
                                        <div>
                                            <div class="pedido-number">Pedido #{{ $pedido->id_pedido }}</div>
                                            <div class="pedido-date">
                                                <i class="far fa-calendar-alt"></i>
                                                {{ \Carbon\Carbon::parse($pedido->created_at)->format('d/m/Y H:i') }}
                                            </div>
                                        </div>
                                        <div class="status-badge status-{{ str_replace('_', '-', $pedido->status) }}">
                                            {{ ucfirst(str_replace('_', ' ', $pedido->status)) }}
                                        </div>
                                    </div>

                                    <div class="pedido-body">
                                        <div class="pedido-items">
                                            @foreach ($pedido->itens->take(3) as $item)
                                                <div class="pedido-item">
                                                    @if ($item->produto && $item->produto->imagens->isNotEmpty())
                                                        @php
                                                            $mainImage = $item->produto->imagens->firstWhere('principal', true) ?: $item->produto->imagens->first();
                                                        @endphp
                                                        <img src="{{ asset($mainImage->caminho) }}" 
                                                             alt="{{ $item->produto->nome_produto ?? 'Produto' }}" 
                                                             class="item-image">
                                                    @else
                                                        <div class="item-image" style="background: var(--light); display: flex; align-items: center; justify-content: center;">
                                                            <i class="fas fa-image text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div class="item-info">
                                                        <h4>{{ $item->produto->nome_produto ?? 'Produto Indisponível' }}</h4>
                                                        <div class="item-meta">
                                                            <span>Qtd: {{ $item->quantidade }}</span>
                                                            <span>Unit: R$ {{ number_format($item->preco_unitario, 2, ',', '.') }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="item-total">
                                                        <strong>R$ {{ number_format($item->quantidade * $item->preco_unitario, 2, ',', '.') }}</strong>
                                                    </div>
                                                </div>
                                            @endforeach
                                            
                                            @if ($pedido->itens->count() > 3)
                                                <div class="text-center py-2">
                                                    <span class="text-muted">
                                                        +{{ $pedido->itens->count() - 3 }} itens adicionais
                                                    </span>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="pedido-summary">
                                            <div>
                                                <div class="pedido-total">
                                                    Total: R$ {{ number_format($pedido->total, 2, ',', '.') }}
                                                </div>
                                                @if($pedido->entrega)
                                                    <small class="text-muted">
                                                        <i class="fas fa-truck me-1"></i>
                                                        {{ ucfirst($pedido->entrega->metodo_entrega) }}
                                                        @if($pedido->entrega->rastreio)
                                                            | Código: {{ $pedido->entrega->rastreio->codigo_rastreio }}
                                                        @endif
                                                    </small>
                                                @endif
                                            </div>
                                            
                                            <div class="pedido-actions">
                                                @if ($pedido->podeSerCanceladoPeloCliente())
                                                    <form action="{{ route('cliente.pedidos.cancelar', $pedido->id_pedido) }}" 
                                                          method="POST" 
                                                          id="form-cancelar-{{ $pedido->id_pedido }}" 
                                                          style="display: inline;">
                                                        @csrf
                                                        <button type="button" class="action-btn btn-danger" 
                                                                onclick="confirmarCancelamento({{ $pedido->id_pedido }})">
                                                            <i class="fas fa-times-circle"></i> Cancelar
                                                        </button>
                                                    </form>
                                                @endif

                                                @if ($pedido->podeConfirmarEntrega())
                                                    <form action="{{ route('cliente.pedidos.confirmarEntrega', $pedido->id_pedido) }}"
                                                          method="POST" 
                                                          id="form-confirmar-{{ $pedido->id_pedido }}"
                                                          style="display: inline;">
                                                        @csrf
                                                        <button type="button" class="action-btn btn-success"
                                                                onclick="confirmarRecebimento({{ $pedido->id_pedido }})">
                                                            <i class="fas fa-check-circle"></i> Confirmar Recebimento
                                                        </button>
                                                    </form>
                                                @endif

                                                <a href="{{ route('cliente.pedidos.verDetalhesPedido', $pedido->id_pedido) }}"
                                                   class="action-btn btn-secondary">
                                                    <i class="fas fa-eye"></i> Ver Detalhes
                                                </a>

                                                @if ($pedido->status === 'entregue' && $pedido->confirmado_pelo_cliente)
                                                    @if ($pedido->podeAvaliar())
                                                        <a href="{{ route('cliente.pedidos.avaliar.view', $pedido->id_pedido) }}"
                                                           class="action-btn btn-info">
                                                            <i class="fas fa-star"></i> Avaliar
                                                        </a>
                                                    @else
                                                        <button class="action-btn btn-disabled" disabled>
                                                            <i class="fas fa-star"></i> Avaliado
                                                        </button>
                                                    @endif
                                                @endif

                                                @if ($pedido->status === 'entregue' && $pedido->podeSolicitarReembolso() && $pedido->prazoReembolsoRestante > 0)
                                                    <button type="button" class="action-btn btn-warning" 
                                                            onclick="abrirModalReembolso({{ $pedido->id_pedido }})">
                                                        <i class="fas fa-undo"></i> Reembolso
                                                    </button>
                                                @elseif($pedido->status === 'entregue' && $pedido->prazoReembolsoRestante !== null && $pedido->prazoReembolsoRestante <= 0)
                                                    <button class="action-btn btn-disabled" disabled>
                                                        <i class="fas fa-info-circle"></i> Prazo Expirado
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal de Reembolso (MANTIDO, mas com trigger via JS) -->
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
                                            <form action="{{ route('cliente.pedidos.solicitarReembolso', $pedido->id_pedido) }}" 
                                                  method="POST" 
                                                  id="form-reembolso-{{ $pedido->id_pedido }}">
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
                                                        <textarea class="form-control" name="motivo_reembolso" 
                                                                  rows="4" placeholder="Descreva brevemente o motivo do reembolso..." 
                                                                  style="resize: none;" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer modal-footer-custom">
                                                    <button type="button" class="action-btn btn-secondary" data-bs-dismiss="modal">
                                                        <i class="fas fa-times me-1"></i> Cancelar
                                                    </button>
                                                    <button type="button" class="action-btn btn-primary" 
                                                            onclick="confirmarReembolso({{ $pedido->id_pedido }})">
                                                        <i class="fas fa-check me-1"></i> Confirmar Solicitação
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
                    <div class="content-header">
                        <h2><i class="fas fa-heart"></i> Meus Favoritos</h2>
                        <div class="content-actions">
                            <span class="badge bg-primary" style="font-size: 14px; padding: 8px 15px;">
                                {{ $favoritos->count() }} itens
                            </span>
                        </div>
                    </div>

                    @if ($favoritos->isEmpty())
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-heart-broken"></i>
                            </div>
                            <h3>Seus favoritos estão vazios!</h3>
                            <p>Aproveite nossas promoções e adicione produtos que você amou aqui!</p>
                            <a href="{{ url('/') }}" class="action-btn btn-primary">
                                <i class="fas fa-arrow-left me-2"></i> Continuar Comprando
                            </a>
                        </div>
                    @else
                        <div class="favoritos-grid">
                            @foreach ($favoritos as $item)
                                <div class="favorito-card">
                                    <div class="favorito-image">
                                        <img src="{{ asset($item->attributes->image) }}" alt="{{ $item->name }}">
                                        <div class="favorito-actions">
                                            <form action="{{ route('home.removefavoritos') }}" method="POST" 
                                                  id="form-remove-favorito-{{ $item->id }}" style="display: inline;">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $item->id }}">
                                                <button type="button" class="remove-btn" title="Remover dos favoritos"
                                                        onclick="confirmarRemoverFavorito('{{ $item->id }}', '{{ $item->name }}')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="favorito-info">
                                        <h3 class="favorito-title">{{ $item->name }}</h3>
                                        <div class="favorito-price">
                                            R$ {{ number_format($item->price, 2, ',', '.') }}
                                        </div>
                                        <div class="d-grid">
                                            <a href="{{ route('home.details', ['slug' => $item->attributes->product_id]) }}" class="action-btn btn-primary">
                                                <i class="fas fa-shopping-cart me-2"></i>
                                                Ver Produto
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4 pt-4 border-top">
                            <a href="{{ url('/') }}" class="action-btn btn-primary">
                                <i class="fas fa-arrow-left me-2"></i> Continuar Comprando
                            </a>
                            <form action="{{ route('home.limparfavoritos') }}" method="GET" id="form-limpar-favoritos">
                                 <button type="button" class="action-btn btn-danger" onclick="confirmarLimparFavoritos()">
                                      <i class="fas fa-trash-alt me-2"></i> Limpar Favoritos
                                 </button>
                            </form>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Script dos popups -->
    <script src="{{ asset('js/popups.js') }}"></script>

    <script>
        // ============================================
        // FUNÇÕES DE CONFIRMAÇÃO PARA O DASHBOARD
        // ============================================

        // Confirmar cancelamento de pedido
        function confirmarCancelamento(pedidoId) {
            confirmar(
                'Deseja realmente cancelar este pedido?',
                function() {
                    const load = loading('Cancelando pedido...');
                    setTimeout(function() {
                        document.getElementById('form-cancelar-' + pedidoId).submit();
                    }, 500);
                }
            );
        }

        // Confirmar recebimento do pedido
        function confirmarRecebimento(pedidoId) {
            confirmar(
                'Confirma o recebimento deste pedido?',
                function() {
                    const load = loading('Confirmando recebimento...');
                    setTimeout(function() {
                        document.getElementById('form-confirmar-' + pedidoId).submit();
                    }, 500);
                }
            );
        }

        // Confirmar reembolso
        function confirmarReembolso(pedidoId) {
            confirmar(
                'Deseja solicitar reembolso para este pedido?',
                function() {
                    const load = loading('Processando solicitação...');
                    setTimeout(function() {
                        document.getElementById('form-reembolso-' + pedidoId).submit();
                    }, 500);
                }
            );
        }

        // Abrir modal de reembolso (mantém o Bootstrap modal)
        function abrirModalReembolso(pedidoId) {
            const modal = new bootstrap.Modal(document.getElementById('reembolsoModal-' + pedidoId));
            modal.show();
        }

        // Confirmar remoção de favorito
        function confirmarRemoverFavorito(itemId, nomeProduto) {
            confirmar(
                `Deseja remover "${nomeProduto}" dos favoritos?`,
                function() {
                    const load = loading('Removendo dos favoritos...');
                    setTimeout(function() {
                        document.getElementById('form-remove-favorito-' + itemId).submit();
                    }, 500);
                }
            );
        }

        // Confirmar limpar todos os favoritos
        function confirmarLimparFavoritos() {
            confirmar(
                'Tem certeza que deseja limpar TODOS os favoritos? Esta ação não pode ser desfeita.',
                function() {
                    const load = loading('Limpando favoritos...');
                    setTimeout(function() {
                        document.getElementById('form-limpar-favoritos').submit();
                    }, 500);
                }
            );
        }

        // Confirmar logout
        function confirmarLogout() {
            confirmar(
                'Deseja realmente sair da sua conta?',
                function() {
                    const load = loading('Saindo...');
                    setTimeout(function() {
                        document.getElementById('logout-form').submit();
                    }, 500);
                }
            );
        }

        // ============================================
        // ANIMAÇÕES E INTERAÇÕES (MANTIDAS)
        // ============================================

        document.addEventListener('DOMContentLoaded', function() {
            // Tooltips para botões de ação
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Animação ao rolar
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

            // Observar elementos para animação
            document.querySelectorAll('.pedido-card, .favorito-card').forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                observer.observe(card);
            });

            // Remover os confirms antigos (já que agora usamos os popups)
            document.querySelectorAll('form[onsubmit*="confirm"]').forEach(form => {
                form.removeAttribute('onsubmit');
            });
        });
    </script>
</body>
</html>