<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cantinho da Isa\Cadastro de Produtos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/adm/cadastro de produtos.css') }}">
</head>

<body>
    <header>
        <a href="{{ route('home.index') }}" class="botao-link">
            CANTINHO DA ISA
        </a>
    </header>

    <div class="main-container">
        <aside class="sidebar">
            <div class="user-info">
                <i class="fas fa-user"></i>
                <input type="text" value="{{ Auth::user()->email }}" readonly />
            </div>
            <nav class="menu">
                <a href="{{ route('adm.dashboard') }}">
                    <button class="menu-btn">Inicial</button>
                </a>
                <a href="{{ route('adm.pedidos') }}">
                    <button class="menu-btn">Pedidos</button>
                </a>
                <a href="{{ route('adm.pdtestoque') }}">
                    <button class="menu-btn">Produtos e estoque</button>
                </a>
                <a href="{{ route('adm.cdtproduto') }}">
                    <button class="menu-btn active">Cadastro de produtos</button>
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

        <div class="content">
            <h1>Cadastro de Produto</h1>
            <form>
                <div class="product-main-info">
                    <div class="product-image-container">
                        <div class="image-preview" id="imagePreview">
                            <i class="fas fa-camera"></i>
                            <span>Clique para adicionar imagem</span>
                        </div>
                        <input type="file" id="productImage" accept="image/*" style="display: none;">
                        <button type="button" class="btn-image-upload"
                            onclick="document.getElementById('productImage').click()">
                            <i class="fas fa-upload"></i> Carregar Imagem
                        </button>
                    </div>

                    <div class="form-section">
                        <div class="form-row">
                            <!-- <div class="form-group">
                                <label for="product-type">Tipo do produto</label>
                                <select id="product-type" required>
                                    <option value="">Selecione...</option>
                                    <option value="vestido">Vestido</option>
                                    <option value="macacao">Macacão</option>
                                    <option value="blusa">Blusa</option>
                                    <option value="calca">Calça</option>
                                </select>
                            </div> -->
                            <div class="form-group">
                                <label for="product-type">Nome</label>
                                <input type="text" id="variation" placeholder="Digite o nome do produto">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="color">Tipo Produto</label>
                                <input type="text" id="color" required placeholder="Ex: Camisa, Calça, Jaqueta">
                            </div>
                            <div class="form-group">
                                <label for="brand">Descrição</label>
                                <input type="text" id="brand" required placeholder="Ex: Estilo, Tamanho, Cor">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="size">Cor</label>
                                <input type="text" id="size" placeholder="Ex: Vermelho, Azul, Preto" required>
                            </div>
                            <div class="form-group">
                                <label for="fabric">Estação</label>
                                <input type="text" id="fabric" required
                                    placeholder="Ex: Verão, Outono, Inverno, Primavera">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="size">Marca</label>
                                <input type="text" id="size" placeholder="Ex: Nike, Adidas, Zara" required>
                            </div>
                            <div class="form-group">
                                <label for="fabric">Valor</label>
                                <input type="text" id="fabric" required placeholder="Dígito o valor do produto (R$)">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="size">Tamanho</label>
                                <input type="text" id="size" placeholder="Ex: P, M, G, GG, ou números" required>
                            </div>
                            <div class="form-group">
                                <label for="fabric">Estoque</label>
                                <input type="text" id="fabric" required placeholder="Digite a quantidade em estoque">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="size">Tequecido</label>
                                <input type="text" id="size" placeholder="Ex: Algodão, Poliéster, Jeans" required>
                            </div>
                            <div class="form-group">
                                <label for="fabric">Modelo</label>
                                <input type="text" id="fabric" required placeholder="Ex: 2023, Casual, Sport">
                            </div>
                        </div>
                        <!-- <div class="form-row">
                            <div class="form-group">
                                <label for="season">Estação</label>
                                <select id="season" required>
                                    <option value="">Selecione...</option>
                                    <option value="verao">Verão</option>
                                    <option value="inverno">Inverno</option>
                                    <option value="outono">Outono</option>
                                    <option value="primavera">Primavera</option>
                                </select>
                            </div>
                        </div> -->
                    </div>
                </div>

                <!-- <div class="buttons">
                    <button type="submit" class="btn-primary">Salvar</button>
                    <button type="reset" class="btn-secondary">Cancelar</button>
                </div> -->
            </form>
        </div>
    </div>

    <script>
        const imagePreview = document.getElementById('imagePreview');
        const productImageInput = document.getElementById('productImage');

        // Quando clicar no quadrado da imagem, abre o seletor de arquivos
        imagePreview.addEventListener('click', function () {
            productImageInput.click();
        });

        // Quando selecionar uma imagem, exibe o preview
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