<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cantinho da Isa - Usuários Cadastrados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    {{-- Ajuste o caminho do CSS para usar a função asset do Laravel --}}
    <link rel="stylesheet" href="{{ asset('css/adm/usuarios-cadastrados.css') }}">
</head>

<body>
    <header class="main-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col ps-4">
                    {{-- Link para a home, como na TELA 1 --}}
                    <h1 class="store-title"><a href="{{ route('home.index') }}"
                            class="text-decoration-none text-white">CANTINHO DA ISA</a></h1>
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
                            {{-- Exibe o e-mail do usuário logado do Laravel --}}
                            <i class="bi bi-person-circle"></i> {{ Auth::user()->email }}
                        </a>
                    </div>

                    <ul class="nav flex-column sidebar-menu flex-grow-1">
                        <li class="nav-item">
                            {{-- Links de navegação usando as rotas Laravel --}}
                            <a class="nav-link menu-button" href="{{ route('adm.dashboard') }}">
                                <i class="bi bi-house"></i> Início
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link menu-button" href="{{ route('adm.pedidos') }}">
                                <i class="bi bi-receipt"></i> Pedidos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link menu-button" href="{{ route('adm.pdtestoque') }}">
                                <i class="bi bi-box-seam"></i> Produtos e estoque
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link menu-button" href="{{ route('adm.cdtproduto') }}">
                                <i class="bi bi-plus-circle"></i> Cadastro de produtos
                            </a>
                        </li>
                        <li class="nav-item">
                            {{-- Botão de Usuários Cadastrados agora ativo --}}
                            <a class="nav-link menu-button active" href="{{ route('adm.usercadastrado') }}">
                                <i class="bi bi-people"></i> Usuários cadastrados
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link menu-button" href="{{ route('adm.vendas') }}">
                                <i class="bi bi-graph-up-arrow"></i> Vendas
                            </a>
                        </li>
                    </ul>

                    <div class="mt-auto p-3">
                        {{-- Formulário de logout, como na TELA 1 --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="logout-button" style="background: none; border: none;">
                                <i class="bi bi-box-arrow-right"></i> Sair
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content pt-2">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="mb-0">Usuários Cadastrados</h3>
                    {{-- O botão "Novo Usuário" foi removido conforme solicitado --}}
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <div class="input-group" style="max-width: 300px;">
                                <input type="text" class="form-control" id="pesquisaUsuario"
                                    placeholder="Pesquisar usuário">
                                <button class="btn btn-outline-secondary" type="button" id="btnPesquisar">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Container da tabela com scroll --}}
                        <div class="table-responsive" style="max-height: 500px;">
                            <table class="table table-hover" id="tabelaUsuarios">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>E-mail</th>
                                        <th>Perfil</th>
                                        <th>Cadastro</th>
                                        <th>Telefone</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->access_level }}</td>
                                            <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                            <td>{{ $user->telefone}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Paginação (se houver) --}}
                        @if($users->hasPages())
                            <nav aria-label="Navegação de páginas" class="mt-3">
                                <ul class="pagination justify-content-center">
                                    {{ $users->links() }}
                                </ul>
                            </nav>
                        @endif
                    </div>
                </div>
            </main>
        </div>
    </div>

    {{-- O Modal para Novo/Editar Usuário foi removido conforme solicitado --}}
    {{-- O script '../javascript/usuarios-cadastrados.js' também foi removido, pois suas funcionalidades foram
    desabilitadas --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    {{-- Apenas um script simples para a barra de pesquisa, se desejar --}}
    <script>
        document.getElementById('btnPesquisar').addEventListener('click', function () {
            const searchTerm = document.getElementById('pesquisaUsuario').value.toLowerCase();
            const tableRows = document.getElementById('tabelaUsuarios').getElementsByTagName('tbody')[0].getElementsByTagName('tr');

            for (let i = 0; i < tableRows.length; i++) {
                const row = tableRows[i];
                const name = row.cells[0].textContent.toLowerCase();
                const email = row.cells[1].textContent.toLowerCase();

                if (name.includes(searchTerm) || email.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });

        document.getElementById('pesquisaUsuario').addEventListener('keyup', function (event) {
            if (event.key === 'Enter') {
                document.getElementById('btnPesquisar').click();
            }
        });
    </script>
</body>

</html>