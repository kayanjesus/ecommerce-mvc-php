<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cadastro de Produtos - Moda Kids</title>
    <link rel="stylesheet" href="{{ asset('css/sistema.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>

<body>
    <a href="{{ route('dashboard') }}" class="botao-link">voltar</a>

    <header>
        <a href="{{ route('home.index') }}" class="botao-link">
            Moda Kids
        </a>
    </header>

    <div class="container">
        <aside class="sidebar">
            <div class="user-info">
                <label for="profile-img" class="profile-icon">
                    <i class="fas fa-user"></i>
                </label>
                <input type="file" id="profile-img" accept="image/*" style="display:none">
                <input type="text" id="username" value="{{ Auth::user()->email }}" />
            </div>

            <nav class="menu">
                <button class="menu-btn">Inicial</button>
                <button class="menu-btn">Pedidos</button>
                <button class="menu-btn">Produtos e estoque</button>
                <button class="menu-btn active">Cadastro de produtos</button>
                <button class="menu-btn">Usuários cadastrados</button>
                <a href="{{ route('vendas') }}">
                    <button class="menu-btn">Vendas</button>
                </a>
            </nav>

            <button class="logout">SAIR</button>
        </aside>

        <main class="conteudo">
            <h2>Cadastro de Produto</h2>
            <form action="{{ route('produtos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-grid">
                    <label>Foto do Produto:</label>
                    <input type="file" name="foto" required>

                    <label>Nome:</label>
                    <input type="text" name="nome_produto" required>

                    <label>Tipo do Produto:</label>
                    <input type="text" name="tipo" required>

                    <label>Variação:</label>
                    <input type="text" name="variacao">

                    <label>Cor:</label>
                    <input type="text" name="cor">

                    <label>Estação:</label>
                    <select name="estacao" required>
                        <option value="primavera">Primavera</option>
                        <option value="verao">Verão</option>
                        <option value="outono">Outono</option>
                        <option value="inverno">Inverno</option>
                    </select>

                    <label>Marca:</label>
                    <input type="text" name="marca" required>

                    <label>Valor:</label>
                    <input type="number" step="0.01" name="preco" required>

                    <label>Tamanho:</label>
                    <input type="text" name="tamanho">

                    <label>Estoque:</label>
                    <input type="number" name="estoque" required>

                    <label>Tecido:</label>
                    <input type="text" name="tecido">

                    <label>Modelo:</label>
                    <input type="text" name="modelo">
                </div>

                <button type="submit" class="botao-link">Cadastrar Produto</button>
            </form>
        </main>
    </div>

    <script src="{{ asset('js/carrosel.js') }}"></script>
</body>

</html>
