<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Cantinho da Isa\Produtos e Estoque</title>
    <link rel="stylesheet" href="{{ asset('css/adm/produtos e estoque.css') }}">
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
                <!-- type="file" CASO FOR COLOCAR FOTO PERFIL -->
                <input type="text" id="profile-img" accept="image/*" style="display:none">
                <input type="text" id="username" value="{{ Auth::user()->email }}" readonly />
            </div>
            <nav class="menu">
                <a href="{{ route('adm.dashboard') }}">
                    <button class="menu-btn">Inicial</button>
                </a>
                <a href="{{ route('adm.pedidos') }}">
                    <button class="menu-btn">Pedidos</button>
                </a>
                <a href="{{ route('adm.pdtestoque') }}">
                    <button class="menu-btn active">Produtos e estoque</button>
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

            <section class="search-bar">
                <input type="text" placeholder="Pesquise aqui...">
                <button type="submit"><i class="fas fa-search"></i></button>
            </section>

            <main class="main-content">
                <h2 class="recent-title">Recentes</h2>
                <table class="products-table">
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
                            <th>Ações</th> <!-- Nova coluna para os botões -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($produtos as $produto)
                            <td>{{ $produto->nome_produto }}</td>
                            <td>
                                @if($produto->imagens->count() > 0)
                                    @php
                                        $mainImage = $produto->imagens->where('principal', true)->first() ?? $produto->imagens->first();
                                    @endphp
                                    <img src="{{ asset($mainImage->caminho) }}" alt="{{ $produto->nome_produto }}" width="60">
                                @else
                                    <i class="fas fa-camera" style="font-size: 20px;"></i>
                                @endif
                            </td>
                            <td>{{ $produto->tipo }}</td>
                            <td>
                                @foreach($produto->variacoes->unique('cor_id') as $variacao)
                                    <span
                                        style="background-color: {{ $variacao->cor->codigo_hex }}; 
                                                                                          display: inline-block; width: 15px; height: 15px;"></span>
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
                                <a href="{{ route('produtos.edit', $produto->id_produto) }}" class="btn-edit"
                                    title="Editar produto">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>

                                <!-- Botão Excluir -->
                                <form action="{{ route('produtos.destroy', $produto->id_produto) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn-delete" title="Excluir produto"
                                        onclick="showConfirmModal(this)">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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



    <script src="script.js"></script>

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