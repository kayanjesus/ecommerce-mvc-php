<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Painel - Cantinho da Isa</title>
    <link rel="stylesheet" href="{{ asset('css/adm/sistema.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        /* Estilo para notificações não lidas */
        .list-group-item.bg-light {
            border-left: 4px solid #0d6efd;
        }

        /* Espaçamento dos botões */
        .btn-notificacao-lida {
            margin-left: 10px;
        }

        /* Efeito hover nas notificações */
        .list-group-item:hover {
            background-color: #f8f9fa;
        }
    </style>
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
                    <button class="menu-btn active">Inicial</button>
                </a>
                <a href="{{ route('adm.pedidos') }}">
                    <button class="menu-btn">Pedidos</button>
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
        <main class="conteudo">
            <div class="dashboard-metrics">
                <div class="metric">
                    <p>Vendas hoje</p>
                    <strong id="vendas-hoje">{{ $vendasHoje ?? 0 }}</strong>
                </div>
                <div class="metric">
                    <p>Valor recebido</p>
                    <strong id="valor-recebido">R$ {{ number_format($valorRecebido ?? 0, 2, ',', '.') }}</strong>
                </div>
                <div class="metric">
                    <p>Avaliações</p>
                    <strong id="avaliacoes">{{ $avaliacoes ?? 0 }}</strong>
                </div>
            </div>

            <!-- Substitua esta seção pelo novo código de notificações -->
            @if($notificacoes->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5><i class="fas fa-bell me-2"></i> Notificações Recentes</h5>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @foreach($notificacoes as $notificacao)
                                <li class="list-group-item {{ $notificacao->read_at ? '' : 'bg-light' }}"
                                    data-id="{{ $notificacao->id }}">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="mb-1">{{ $notificacao->data['message'] }}</p>
                                            <small class="text-muted">
                                                {{ $notificacao->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                        <div>
                                            <a href="{{ $notificacao->data['link'] }}"
                                                class="btn btn-sm btn-outline-primary me-2">
                                                Ver Pedido
                                            </a>
                                            @if(!$notificacao->read_at)
                                                <button class="btn btn-sm btn-outline-success btn-notificacao-lida"
                                                    data-id="{{ $notificacao->id }}">
                                                    <i class="fas fa-check"></i> Marcar como lida
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                        <h5>Nenhuma notificação no momento</h5>
                    </div>
                </div>
            @endif
        </main>
    </div>

    <!-- Adicione esses scripts no final do body -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>

    <script>
        document.querySelectorAll('.btn-notificacao-lida').forEach(button => {
            button.addEventListener('click', function () {
                const notificacaoId = this.getAttribute('data-id');

                fetch(`/notificacoes/${notificacaoId}/marcar-lida`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const item = this.closest('.list-group-item');
                            item.classList.remove('bg-light');
                            this.remove();
                        }
                    });
            });
        });
    </script>



    <script>
        function atualizarMetricas() {
            $.get('{{ route("adm.metricas") }}', function (data) {
                $('#vendas-hoje').text(data.vendasHoje);
                $('#valor-recebido').text('R$ ' + data.valorRecebido.toLocaleString('pt-BR', {
                    minimumFractionDigits: 2
                }));
                $('#avaliacoes').text(data.avaliacoes);
            });
        }

        // Chamar quando a página carregar
        $(document).ready(function () {
            atualizarMetricas();

            // Atualizar a cada 30 segundos
            setInterval(atualizarMetricas, 30000);
        });
    </>

</body >

</html >