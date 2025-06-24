<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cantinho da Isa - Produtos e Estoque</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset("css/adm/produtos e estoque.css") }}">
</head>
<body>
    <header class="main-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col ps-4">
                    <a href="{{ route('home.index') }}" class="store-title">CANTINHO DA ISA</a>
                </div>
            </div>
        </div>
    </header>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-2 d-flex flex-column h-100">
                    <div class="admin-container ps-2 pe-2 mb-2">
                        <div class="admin-button">
                            <i class="fas fa-user"></i> {{ Auth::user()->email }}
                        </div>
                    </div>
                    
                    <ul class="nav flex-column sidebar-menu flex-grow-1">
                        <li class="nav-item">
                            <a class="nav-link menu-button" href="{{ route('adm.dashboard') }}">
                                <i class="fas fa-home"></i> Início
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link menu-button" href="{{ route('adm.pedidos') }}">
                                <i class="fas fa-receipt"></i> Pedidos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link menu-button active" href="{{ route('adm.pdtestoque') }}">
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
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="mb-0">Produtos e Estoque</h3>
                    <a href="{{ route('adm.cdtproduto') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Novo Produto
                    </a>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <div class="input-group" style="max-width: 300px;">
                                <input type="text" class="form-control" id="pesquisaProduto" placeholder="Pesquisar produto">
                                <button class="btn btn-outline-secondary" type="button" id="btnPesquisar">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <div class="btn-group">
                                <button class="btn btn-outline-secondary active" data-filter="todos">Todos</button>
                                <button class="btn btn-outline-secondary" data-filter="estoque">Com Estoque</button>
                                <button class="btn btn-outline-secondary" data-filter="semestoque">Sem Estoque</button>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-hover" id="tabelaProdutos">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Imagem</th>
                                        <th>Tipo</th>
                                        <th>Cor</th>
                                        <th>Marca</th>
                                        <th>Tamanho</th>
                                        <th>Modelo</th>
                                        <th>Estação</th>
                                        <th>Valor</th>
                                        <th>Estoque</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($produtos as $produto)
                                        <tr>
                                            <td>{{ $produto->nome_produto }}</td>
                                            <td>
                                                @if($produto->imagens->count() > 0)
                                                    @php
                                                        $mainImage = $produto->imagens->where('principal', true)->first() ?? $produto->imagens->first();
                                                    @endphp
                                                    <img src="{{ asset($mainImage->caminho) }}" alt="Imagem de {{ $produto->nome_produto }}"
                                                        width="60" class="product-img">
                                                @else
                                                    <i class="fas fa-camera" style="font-size: 20px;"></i>
                                                @endif
                                            </td>
                                            <td>
                                                @foreach($produto->categorias as $categoria)
                                                    {{ $categoria->nome_categoria }}@if(!$loop->last), @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach($produto->variacoes->unique('cor_id') as $variacao)
                                                    <span
                                                        style="background-color: {{ $variacao->cor->codigo_hex }}; 
                                                                    display: inline-block; width: 15px; height: 15px; border-radius: 50%;"></span>
                                                    {{ $variacao->cor->nome }}@if(!$loop->last), @endif
                                                @endforeach
                                            </td>
                                            <td>{{ $produto->marca }}</td>
                                            <td>
                                                @foreach($produto->variacoes->unique('tamanho_id') as $variacao)
                                                    {{ $variacao->tamanho->nome }}@if(!$loop->last), @endif
                                                @endforeach
                                            </td>
                                            <td>{{ $produto->modelo }}</td>
                                            <td>{{ $produto->estacao }}</td>
                                            <td>R${{ number_format($produto->preco, 2, ',', '.') }}</td>
                                            <td>
                                                @foreach($produto->variacoes as $variacao)
                                                    {{ $variacao->estoque }} ({{ $variacao->tamanho->nome }})@if(!$loop->last)<br>@endif
                                                @endforeach
                                            </td>
                                            <td>
                                                <!-- Botão Editar -->
                                                <a href="{{ route('produtos.edit', $produto->id_produto) }}" class="btn btn-sm btn-outline-primary me-1"
                                                    title="Editar produto">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>

                                                <!-- Botão Excluir -->
                                                <form action="{{ route('produtos.destroy', $produto->id_produto) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-outline-danger" title="Excluir produto"
                                                        onclick="showConfirmModal(this)">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <nav aria-label="Navegação de páginas">
                            <ul class="pagination justify-content-center mt-3" id="paginacao">
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal de Confirmação -->
    <div id="confirmModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Confirmar Exclusão</h3>
                <span class="close-modal">&times;</span>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir este produto?</p>
            </div>
            <div class="modal-footer">
                <button class="btn-cancel">Cancelar</button>
                <button class="btn-confirm">Confirmar</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Variável para armazenar o formulário que será submetido
        let formToSubmit = null;

        // Função para mostrar o modal
        function showConfirmModal(button) {
            const modal = document.getElementById('confirmModal');
            formToSubmit = button.closest('form');
            modal.style.display = 'flex';
        }

        // Fechar modal quando clicar no X
        document.querySelector('.close-modal').addEventListener('click', function () {
            document.getElementById('confirmModal').style.display = 'none';
        });

        // Fechar modal quando clicar no Cancelar
        document.querySelector('.btn-cancel').addEventListener('click', function () {
            document.getElementById('confirmModal').style.display = 'none';
        });

        // Confirmar exclusão
        document.querySelector('.btn-confirm').addEventListener('click', function () {
            if (formToSubmit) {
                formToSubmit.submit();
            }
            document.getElementById('confirmModal').style.display = 'none';
        });

        // Fechar modal quando clicar fora dele
        window.addEventListener('click', function (event) {
            const modal = document.getElementById('confirmModal');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    </script>
</body>
</html>