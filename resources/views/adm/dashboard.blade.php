<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cantinho da Isa - Início</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset("css/adm/sistema.css") }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <header class="main-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col ps-4">
                    <h1 class="store-title">
                        <a href="{{ route('home.index') }}" style="color: white; text-decoration: none;">
                            CANTINHO DA ISA
                        </a>
                    </h1>
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
                            <i class="fas fa-user-circle"></i> {{ Auth::user()->email }}
                        </a>
                    </div>

                    <ul class="nav flex-column sidebar-menu flex-grow-1">
                        <li class="nav-item">
                            <a class="nav-link menu-button active" href="{{ route('adm.dashboard') }}">
                                <i class="fas fa-home"></i> Início
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link menu-button" href="{{ route('adm.pedidos') }}">
                                <i class="fas fa-receipt"></i> Pedidos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link menu-button" href="{{ route('adm.pdtestoque') }}">
                                <i class="fas fa-box"></i> Produtos e estoque
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link menu-button" href="{{ route('adm.cdtproduto') }}">
                                <i class="fas fa-plus-circle"></i> Cadastro de produtos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link menu-button" href="{{ route('adm.usercadastrado') }}">
                                <i class="fas fa-users"></i> Usuários cadastrados
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link menu-button" href="{{ route('adm.vendas') }}">
                                <i class="fas fa-chart-line"></i> Vendas
                            </a>
                        </li>
                    </ul>

                    <div class="mt-auto p-3">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="logout-button">
                                <i class="fas fa-sign-out-alt"></i> Sair
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content pt-2">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="card p-3">
                            <div class="card-body text-center">
                                <h5 class="card-title">VENDAS HOJE</h5>
                                <p class="card-value" id="vendas-hoje">{{ $vendasHoje ?? 0 }}</p>
                                <p class="card-subtext">pedidos</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card p-3">
                            <div class="card-body text-center">
                                <h5 class="card-title">VALOR RECEBIDO</h5>
                                <p class="card-value" id="valor-recebido">R$
                                    {{ number_format($valorRecebido ?? 0, 2, ',', '.') }}
                                </p>
                                <p class="card-subtext">hoje</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card p-3">
                            <div class="card-body text-center">
                                <h5 class="card-title">AVALIAÇÕES</h5>
                                <p class="card-value" id="avaliacoes">{{ $totalAvaliacoes ?? 0 }}</p>
                                <p class="card-subtext">total</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="mb-0">Últimos Pedidos</h5>
                            </div>
                            <div class="card-body">
                                <div class="list-group">
                                    @if(isset($ultimosPedidos) && $ultimosPedidos->count() > 0)
                                        @foreach($ultimosPedidos as $pedido)
                                                                    <a href="{{ route('adm.pedidos.detalhes', $pedido->id_pedido) }}"
                                                                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                                                        <div>
                                                                            <h6 class="mb-1">Pedido #{{ $pedido->id_pedido }}</h6>
                                                                            <p class="mb-1 text-muted">
                                                                                Cliente: {{ $pedido->usuario->name ?? 'N/A' }}
                                                                            </p>
                                                                            <p class="mb-1">
                                                                                Total: R$ {{ number_format($pedido->total, 2, ',', '.') }}
                                                                            </p>
                                                                        </div>
                                                                        <small class="text-end">
                                                                            {{ $pedido->created_at->diffForHumans() }} <br>
                                                                            <span class="badge {{
                                            $pedido->status == 'pago' ? 'bg-success' :
                                            ($pedido->status == 'processando' ? 'bg-info' :
                                                ($pedido->status == 'enviado' ? 'bg-primary' :
                                                    ($pedido->status == 'entregue' ? 'bg-secondary' :
                                                        ($pedido->status == 'cancelado' ? 'bg-danger' : 'bg-warning'))))
                                                                }}">
                                                                                {{ ucfirst(str_replace('_', ' ', $pedido->status)) }}
                                                                            </span>
                                                                        </small>
                                                                    </a>
                                        @endforeach
                                    @else
                                        <div class="list-group-item list-group-item-action">
                                            <p class="mb-1 text-center">Nenhum pedido recente.</p>
                                        </div>
                                    @endif
                                    <a href="{{ route('adm.pedidos') }}"
                                        class="list-group-item list-group-item-action mt-2">
                                        <div class="d-flex w-100 justify-content-between align-items-center">
                                            <h6 class="mb-0">Ver todos os pedidos</h6>
                                            <i class="fas fa-arrow-right"></i>
                                        </div>
                                    </a>
                                </div>
                                </div>
                            </div>
                        </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Notificações</h5>
                            </div>
                            <div class="card-body">
                                <div class="list-group" id="notificacoes-container">
                                    @if(isset($notificacoes) && $notificacoes->count() > 0)
                                        @foreach($notificacoes as $notificacao)
                                            <div class="list-group-item list-group-item-action">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <h6 class="mb-1">Notificação</h6>
                                                    <small>{{ $notificacao->created_at->diffForHumans() }}</small>
                                                </div>
                                                <p class="mb-1">{{ $notificacao->data['message'] }}</p>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="list-group-item list-group-item-action">
                                            <p class="mb-1 text-center">Nenhuma notificação no momento</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>

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

        function carregarNotificacoes() {
            $.get('{{ route("adm.notificacoes") }}', function (data) {
                const notificacoesContainer = $('#notificacoes-container');
                notificacoesContainer.empty();

                if (data.length > 0) {
                    data.forEach(function (notificacao) {
                        if (notificacao.data && notificacao.data.message) {
                            notificacoesContainer.append(`
                                <div class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Notificação</h6>
                                        <small>${new Date(notificacao.created_at).toLocaleTimeString()}</small>
                                    </div>
                                    <p class="mb-1">${notificacao.data.message}</p>
                                </div>
                            `);
                        }
                    });
                } else {
                    notificacoesContainer.append(`
                        <div class="list-group-item list-group-item-action">
                            <p class="mb-1 text-center">Nenhuma notificação no momento</p>
                        </div>
                    `);
                }
            });
        }

        $(document).ready(function () {
            atualizarMetricas();
            carregarNotificacoes();
            setInterval(atualizarMetricas, 30000);
            setInterval(carregarNotificacoes, 15000);

            if (typeof Echo !== 'undefined' && {{ Auth::id() }} !== null) {
                Echo.private('App.Models.User.' + {{ Auth::id() }})
                    .notification((notification) => {
                        console.log('Nova notificação recebida em tempo real:', notification);
                        const notificacoesContainer = $('#notificacoes-container');
                        notificacoesContainer.prepend(`
                            <div class="list-group-item list-group-item-action" style="background-color: #e0ffe0; border: 1px solid #a0ffa0;">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">Nova Notificação</h6>
                                    <small>agora</small>
                                </div>
                                <p class="mb-1">${notification.notification.message}</p>
                            </div>
                        `);
                    });
            } else {
                console.warn('Laravel Echo não está configurado ou o usuário não está logado para receber notificações em tempo real.');
            }
        });
    </script>
</body>

</html>