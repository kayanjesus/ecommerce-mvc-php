<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moda Kids - Pedidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset("css/adm/pedidos.css") }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <header class="main-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col ps-4">
                    <h1 class="store-title">
                        <a href="{{ route('home.index') }}" style="color: white; text-decoration: none;">
                            Moda Kids
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
                            <a class="nav-link menu-button" href="{{ route('adm.dashboard') }}">
                                <i class="fas fa-home"></i> Início
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link menu-button active" href="{{ route('adm.pedidos') }}">
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
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">

                
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
                
                <div class="card">
                    <div class="card-body">

                        
                        <div class="list-group" id="listaPedidos">
                            @if($pedidos->count() > 0)
                                @foreach($pedidos as $pedido)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">Pedido #{{ $pedido->id_pedido }}</h6>
                                            <small class="text-muted">Cliente: {{ $pedido->usuario->name }}</small>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge {{
                                                $pedido->status === 'pago' ? 'bg-success' :
                                                ($pedido->status === 'processando' ? 'bg-warning text-dark' :
                                                    ($pedido->status === 'enviado' ? 'bg-info' :
                                                        ($pedido->status === 'entregue' ? 'bg-primary' : 'bg-secondary')))
                                            }}">
                                                {{ ucfirst($pedido->status) }}
                                            </span>
                                            <p class="mb-0">R$ {{ number_format($pedido->total, 2, ',', '.') }}</p>
                                            <small class="text-muted">{{ $pedido->created_at->format('d/m/Y - H:i') }}</small>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <a href="{{ route('adm.detalhe_pedido', $pedido->id_pedido) }}" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fas fa-eye"></i> Detalhes
                                        </a>
                                        @if($pedido->status !== 'entregue' && $pedido->status !== 'cancelado')
                                    
                                            <!-- <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelarPedidoModal" data-pedido-id="{{ $pedido->id_pedido }}">
                                                <i class="fas fa-times-circle"></i> Cancelar
                                            </button> -->
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="list-group-item text-center py-4">
                                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                    <p class="text-muted fs-5">Nenhum pedido encontrado.</p>
                                </div>
                            @endif
                        </div>
                        
                        @if($pedidos->count() > 0)
                        <nav aria-label="Navegação de páginas" class="mt-4">
                            {{ $pedidos->links('pagination::bootstrap-5') }}
                        </nav>
                        @endif
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal Confirmar Entrega -->
    <div class="modal fade" id="confirmarEntregaModal" tabindex="-1" aria-labelledby="confirmarEntregaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmarEntregaModalLabel">Confirmar Entrega</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Tem certeza que deseja marcar este pedido como entregue?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="formConfirmarEntrega" method="POST" action="">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-success">Confirmar Entrega</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Cancelar Pedido -->
    <div class="modal fade" id="cancelarPedidoModal" tabindex="-1" aria-labelledby="cancelarPedidoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelarPedidoModalLabel">Cancelar Pedido</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Tem certeza que deseja cancelar este pedido?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="formCancelarPedido" method="POST" action="">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-danger">Confirmar Cancelamento</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    
    <script>
        // Configura os modais para atualizar o action do form com o ID do pedido
        document.addEventListener('DOMContentLoaded', function() {
            var confirmarEntregaModal = document.getElementById('confirmarEntregaModal');
            var cancelarPedidoModal = document.getElementById('cancelarPedidoModal');
            
            confirmarEntregaModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var pedidoId = button.getAttribute('data-pedido-id');
                var form = document.getElementById('formConfirmarEntrega');
                form.action = '/admin/pedidos/' + pedidoId + '/entregue';
            });
            
            cancelarPedidoModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var pedidoId = button.getAttribute('data-pedido-id');
                var form = document.getElementById('formCancelarPedido');
                form.action = '/admin/pedidos/' + pedidoId + '/cancelar';
            });
        });
    </script>
</body>
</html>