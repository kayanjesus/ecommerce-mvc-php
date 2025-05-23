<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Cantinho da Isa | Cadastro de Produtos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/adm/cadastro de produtos.css') }}" />

    <!-- CSS Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Opcional: ajuste para que o select2 ocupe 100% da largura do container */
        .select2-container--default .select2-selection--multiple {
            min-height: 40px;
        }
    </style>
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

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="content">
            <h1>Cadastro de Produto</h1>
            <form method="POST" action="{{ route('produtos.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="product-main-info">
                    <div class="product-image-container">
                        <div class="image-preview" id="imagePreview">
                            <i class="fas fa-camera"></i>
                            <span>Clique para adicionar imagens</span>
                        </div>
                        <!-- Alteração principal: 'imagens[]' e 'multiple' -->
                        <input type="file" id="productImages" name="imagens[]" accept="image/*" multiple
                            style="display: none;" required />
                        <button type="button" class="btn-image-upload"
                            onclick="document.getElementById('productImages').click()">
                            <i class="fas fa-upload"></i> Carregar Imagens (Múltiplas)
                        </button>
                    </div>
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-section">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="nome">Nome</label>
                                <input type="text" name="nome" id="nome" placeholder="Digite o nome do produto"
                                    required />
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="tipo">Tipo Produto</label>
                                <input type="text" name="tipo" id="tipo" placeholder="Ex: Camisa, Calça" required />
                            </div>
                            <div class="form-group">
                                <label for="descricao">Descrição</label>
                                <input type="text" name="descricao" id="descricao" placeholder="Ex: Estilo, Tamanho"
                                    required />
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="cor">Cor</label>
                                <select name="cores[]" id="cor" multiple required style="height: 100px;"
                                    class="cores-select">
                                    <option value="">Selecione uma cor</option>
                                    @foreach ($cores as $cor)
                                        <option value="{{ $cor->nome }}">{{ $cor->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="categoria">Categorias</label>
                                <select name="categorias[]" class="form-control categorias-select" multiple required>
                                    @foreach ($categorias as $categoria)
                                        <option value="{{ $categoria->id_categoria }}">{{ $categoria->nome_categoria }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="marca">Marca</label>
                                <input type="text" name="marca" id="marca" placeholder="Ex: Nike, Zara" required />
                            </div>
                            <div class="form-group">
                                <label for="valor">Valor (R$)</label>
                                <input type="number" step="0.01" name="valor" id="valor" placeholder="Ex: 59.90"
                                    required />
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="tamanho">Tamanho</label>
                                <select name="tamanhos[]" id="tamanho" multiple required style="height: 100px;"
                                    class="tamanhos-select">
                                    <option value="">Selecione um tamanho</option>
                                    @foreach ($tamanhos as $tamanho)
                                        <option value="{{ $tamanho->nome }}">{{ $tamanho->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="estoque">Estoque</label>
                                <input type="number" name="estoque" id="estoque" placeholder="Quantidade" required />
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="tecido">Tecido</label>
                                <input type="text" name="tecido" id="tecido" placeholder="Ex: Algodão, Jeans"
                                    required />
                            </div>
                            <div class="form-group">
                                <label for="genero">Gênero</label>
                                <select name="genero" id="genero" required>
                                    <option value="" disabled selected>Selecione um gênero</option>
                                    <option value="masculino">Masculino</option>
                                    <option value="feminino">Feminino</option>
                                    <option value="bebe">Bebê</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <button type="submit" class="btn-submit">Cadastrar Produto</button>
                        </div>
                    </div>
            </form>
        </div>
    </div>

    <script>
        const imagePreview = document.getElementById('imagePreview');
        const productImagesInput = document.getElementById('productImages');

        imagePreview.addEventListener('click', () => productImagesInput.click());

        productImagesInput.addEventListener('change', function (e) {
            imagePreview.innerHTML = '';

            if (this.files.length > 0) {
                Array.from(this.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.style.maxWidth = '80px';
                        img.style.margin = '5px';
                        imagePreview.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });
            } else {
                imagePreview.innerHTML = '<i class="fas fa-camera"></i><span>Clique para adicionar imagens</span>';
            }
        });
    </script>

    <!-- JS jQuery e Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.categorias-select').select2({
                placeholder: "Selecione as categorias",
                allowClear: true,
                width: '100%'
            });

            $('.cores-select').select2({
                placeholder: "Selecione as cores",
                allowClear: true,
                width: '100%'
            });

            $('.tamanhos-select').select2({
                placeholder: "Selecione os tamanhos",
                allowClear: true,
                width: '100%'
            });
        });

    </script>
</body>

</html>