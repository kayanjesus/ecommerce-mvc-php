<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cantinho da Isa | Cadastro de Produtos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/adm/cadastro de produtos.css') }}">
</head>

<body>
    <header>
        <a href="{{ route('home.index') }}" class="botao-link">CANTINHO DA ISA</a>
    </header>

    <div class="main-container">
        <aside class="sidebar">
            <div class="user-info">
                <i class="fas fa-user"></i>
                <input type="text" value="{{ Auth::user()->email }}" readonly />
            </div>
            <nav class="menu">
                <a href="{{ route('adm.dashboard') }}"><button class="menu-btn">Inicial</button></a>
                <a href="{{ route('adm.pedidos') }}"><button class="menu-btn">Pedidos</button></a>
                <a href="{{ route('adm.pdtestoque') }}"><button class="menu-btn">Produtos e estoque</button></a>
                <a href="{{ route('adm.cdtproduto') }}"><button class="menu-btn active">Cadastro de
                        produtos</button></a>
                <a href="{{ route('adm.usercadastrado') }}"><button class="menu-btn">Usuários cadastrados</button></a>
                <a href="{{ route('adm.vendas') }}"><button class="menu-btn">Vendas</button></a>
            </nav>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout">SAIR</button>
            </form>
        </aside>

        <div class="content">
            <h1>Cadastro de Produto</h1>
            <form method="POST" action="{{ route('produtos.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="product-main-info">
                    <div class="product-image-container">
                        <div class="image-preview" id="imagePreview">
                            <i class="fas fa-camera"></i>
                            <span>Clique para adicionar imagem</span>
                        </div>
                        <input type="file" id="productImage" name="imagem" accept="image/*" style="display: none;"
                            required>
                        <button type="button" class="btn-image-upload"
                            onclick="document.getElementById('productImage').click()">
                            <i class="fas fa-upload"></i> Carregar Imagem
                        </button>
                    </div>

                    <div class="form-section">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="nome">Nome</label>
                                <input type="text" name="nome" id="nome" placeholder="Digite o nome do produto"
                                    required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="tipo">Tipo Produto</label>
                                <input type="text" name="tipo" id="tipo" placeholder="Ex: Camisa, Calça" required>
                            </div>
                            <div class="form-group">
                                <label for="descricao">Descrição</label>
                                <input type="text" name="descricao" id="descricao" placeholder="Ex: Estilo, Tamanho"
                                    required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="cor">Cor</label>
                                <input type="text" name="cor" id="cor" placeholder="Ex: Azul, Preto" required>
                            </div>
                            <div class="form-group">
                                <label for="estacao">Estação</label>
                                <select name="estacao" id="estacao" required>
                                    <option value="" disabled selected>Selecione...</option>
                                    <option value="verao">Verão</option>
                                    <option value="inverno">Inverno</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="marca">Marca</label>
                                <input type="text" name="marca" id="marca" placeholder="Ex: Nike, Zara" required>
                            </div>
                            <div class="form-group">
                                <label for="valor">Valor (R$)</label>
                                <input type="number" step="0.01" name="valor" id="valor" placeholder="Ex: 59.90"
                                    required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="tamanho">Tamanho</label>
                                <input type="text" name="tamanho" id="tamanho" placeholder="Ex: P, M, G" required>
                            </div>
                            <div class="form-group">
                                <label for="estoque">Estoque</label>
                                <input type="number" name="estoque" id="estoque" placeholder="Quantidade" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="tecido">Tecido</label>
                                <input type="text" name="tecido" id="tecido" placeholder="Ex: Algodão, Jeans" required>
                            </div>
                            <div class="form-group">
                                <label for="modelo">Modelo</label>
                                <input type="text" name="modelo" id="modelo" placeholder="Ex: Casual, 2023" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <button type="submit" class="btn-submit">Cadastrar Produto</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        const imagePreview = document.getElementById('imagePreview');
        const productImageInput = document.getElementById('productImage');

        imagePreview.addEventListener('click', function () {
            productImageInput.click();
        });

        productImageInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            const reader = new FileReader();

            reader.onload = function (e) {
                imagePreview.innerHTML = '';
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '100%';
                img.style.maxHeight = '100%';
                imagePreview.appendChild(img);
            }

            if (file) {
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>

</html>