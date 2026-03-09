<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cantinho da Isa - Produtos e Estoque</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset("css/adm/produtos e estoque.css") }}">
    <!-- CSS dos popups -->
    <link rel="stylesheet" href="{{ asset('css/popups.css') }}">
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

                {{-- As mensagens vão aparecer como popups bonitinhos agora! --}}
                {{-- Não precisa mais dos alerts do Bootstrap --}}

                <div class="card">
                    <div class="card-body">
                        {{-- Formulário de Pesquisa e Filtros --}}
                        <form action="{{ route('adm.pdtestoque') }}" method="GET"
                            class="mb-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                            <div class="input-group" style="max-width: 300px;">
                                <input type="text" class="form-control" name="search" placeholder="Pesquisar produto"
                                    value="{{ $searchQuery }}">
                                <button class="btn btn-outline-secondary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <div class="btn-group" role="group" aria-label="Filtro de Estoque">
                                <button type="submit" name="stock_filter" value="todos"
                                    class="btn btn-outline-secondary {{ $stockFilter === 'todos' ? 'active' : '' }}">Todos</button>
                                <button type="submit" name="stock_filter" value="estoque"
                                    class="btn btn-outline-secondary {{ $stockFilter === 'estoque' ? 'active' : '' }}">Com
                                    Estoque</button>
                                <button type="submit" name="stock_filter" value="semestoque"
                                    class="btn btn-outline-secondary {{ $stockFilter === 'semestoque' ? 'active' : '' }}">Sem
                                    Estoque</button>
                            </div>
                        </form>

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
                                    @forelse ($produtos as $produto)
                                        <tr>
                                            <td>{{ $produto->nome_produto }}</td>
                                            <td>
                                                @if($produto->imagens->count() > 0)
                                                    @php
                                                        $mainImage = $produto->imagens->where('principal', true)->first() ?? $produto->imagens->first();
                                                    @endphp
                                                    <img src="{{ asset($mainImage->caminho) }}"
                                                        alt="Imagem de {{ $produto->nome_produto }}" width="60"
                                                        class="product-img">
                                                @else
                                                    <i class="fas fa-camera" style="font-size: 20px; color: #ccc;"></i>
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
                                                        style="background-color: {{ $variacao->cor->codigo_hex ?? '#000' }}; 
                                                                                                                                display: inline-block; width: 15px; height: 15px; border-radius: 50%; border: 1px solid #ccc; vertical-align: middle;"></span>
                                                    {{ $variacao->cor->nome ?? 'N/A' }}@if(!$loop->last), @endif
                                                @endforeach
                                            </td>
                                            <td>{{ $produto->marca }}</td>
                                            <td>
                                                @foreach($produto->variacoes->unique('tamanho_id') as $variacao)
                                                    {{ $variacao->tamanho->nome ?? 'N/A' }}@if(!$loop->last), @endif
                                                @endforeach
                                            </td>
                                            <td>{{ $produto->modelo }}</td>
                                            <td>{{ $produto->estacao }}</td>
                                            <td>R${{ number_format($produto->preco, 2, ',', '.') }}</td>
                                            <td>
                                                @forelse($produto->variacoes as $variacao)
                                                    {{ $variacao->estoque }}
                                                    ({{ $variacao->tamanho->nome ?? 'N/A' }})@if(!$loop->last)<br>@endif
                                                @empty
                                                    N/A
                                                @endforelse
                                            </td>
                                            <td>
                                                <!-- Botão Editar -->
                                                <a href="{{ route('produtos.edit', $produto->id_produto) }}"
                                                    class="btn btn-sm btn-outline-primary me-1" title="Editar produto">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>

                                                <!-- Botão Excluir - AGORA USA O NOVO SISTEMA DE CONFIRMAÇÃO -->
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                    title="Excluir produto"
                                                    onclick="confirmarExclusao('{{ $produto->id_produto }}', '{{ $produto->nome_produto }}')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>

                                                <!-- Formulário escondido para cada produto -->
                                                <form id="form-excluir-{{ $produto->id_produto }}"
                                                    action="{{ route('produtos.destroy', $produto->id_produto) }}"
                                                    method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="11" class="text-center py-4 text-muted">Nenhum produto encontrado
                                                com os critérios de busca e filtro.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Paginação --}}
                        <nav aria-label="Navegação de páginas">
                            {{ $produtos->appends(['search' => $searchQuery, 'stock_filter' => $stockFilter])->links('pagination::bootstrap-5') }}
                        </nav>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- NÃO PRECISA MAIS DO MODAL ANTIGO - FOI REMOVIDO -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Script dos popups -->
    <script src="{{ asset('js/popups.js') }}"></script>

    <script>
        // ============================================
        // FUNÇÃO DE CONFIRMAÇÃO PARA EXCLUIR PRODUTOS (COM LOADING)
        // ============================================

        function confirmarExclusao(id, nome) {
            // Usa a função 'confirmar' que está no popups.js
            confirmar(
                `Tem certeza que deseja excluir o produto "${nome}"? Esta ação não pode ser desfeita.`,
                function () {
                    // MOSTRA O LOADING ANTES DE ENVIAR
                    const load = loading('Excluindo produto...');

                    // Pequeno delay para o loading aparecer
                    setTimeout(function () {
                        // Envia o formulário
                        document.getElementById('form-excluir-' + id).submit();
                    }, 300);
                },
                function () {
                    // Se clicou em NÃO (opcional)
                    console.log('Exclusão cancelada');
                }
            );
        }

        // ============================================
        // EXEMPLO DE COMO USAR LOADING (SEPARADO)
        // ============================================

        function exemploLoading() {
            const load = loading('Processando...');

            // Simula uma tarefa demorada
            setTimeout(function () {
                load.fechar();
                mostrarMensagem('Operação concluída!', 'success');
            }, 2000);
        }

        // ============================================
        // EXEMPLO DE COMO MOSTRAR MENSAGENS MANUALMENTE
        // ============================================

        // Para testar, descomente as linhas abaixo:
        //
        // mostrarMensagem('Produto excluído com sucesso!', 'success');  <-- AGORA ESTÁ COMENTADO
        // mostrarMensagem('Isso é um erro!', 'error');
        // mostrarMensagem('Isso é um aviso!', 'warning');
        // mostrarMensagem('Isso é uma informação!', 'info');

    </script>
</body>

</html>