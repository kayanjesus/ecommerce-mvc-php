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
                            <div class="empty-preview" onclick="document.getElementById('productImages').click()">
                                <i class="fas fa-camera"></i>
                                <span>Clique para adicionar imagens</span>
                            </div>
                        </div>
                        <input type="file" id="productImages" name="imagens[]" accept="image/*" multiple
                            style="display: none;" required />
                        <input type="hidden" name="main_image" id="mainImageId" value="">
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
                                <label for="categoria">Tipo do Produto</label>
                                <select name="categorias[]" class="form-control categorias-select" multiple required>
                                    @foreach ($categorias as $categoria)
                                        <option value="{{ $categoria->id_categoria }}">{{ $categoria->nome_categoria }}
                                        </option>
                                    @endforeach
                                </select>
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
                                <select name="cores[]" id="cor" multiple required class="cores-select">
                                    @foreach ($cores as $cor)
                                        <option value="{{ $cor->id }}" data-hex="{{ $cor->codigo_hex }}">
                                            <span
                                                style="background-color: {{ $cor->codigo_hex }}; display: inline-block; width: 15px; height: 15px;"></span>
                                            {{ $cor->nome }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- ESTAÇÃO; CAMPO NA TABLE PRODUTO; ALTERAR-->
                            <!-- SO VERÃO e INVERNO -->
                            <div class="form-group">
                                <label for="estacao">Estação</label>
                                <select name="estacao" id="estacao" required>
                                    <option value="" disabled selected>Selecione uma estação</option>
                                    <option value="Verão">Verão</option>
                                    <option value="Inverno">Inverno</option>
                                </select>
                            </div>
                            <!--  -->
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
                                <select name="tamanhos[]" id="tamanho" multiple required class="tamanhos-select">
                                    @foreach ($tamanhos as $tamanho)
                                        <option value="{{ $tamanho->id }}">{{ $tamanho->nome }}</option>
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
                            <!-- O MODELO É UM CODIGO DA ROUPA, ARRUMAR ISSO TAMBÉM -->
                            <div class="form-group">
                                <label for="modelo">Modelo</label>
                                <input type="text" name="modelo" id="modelo" required />
                            </div>
                            <!--  -->
                        </div>
                        <div class="form-row">
                            <button type="submit" class="btn-submit">Cadastrar Produto</button>
                        </div>
                    </div>
            </form>
        </div>
    </div>


    <script>
        $('.cores-select').select2({
            templateResult: formatColor,
            templateSelection: formatColor,
            escapeMarkup: function (m) { return m; }
        });

        function formatColor(option) {
            if (!option.id) return option.text;
            var hex = $(option.element).data('hex');
            return $('<span><span style="background-color:' + hex + '; width:15px; height:15px; display:inline-block; margin-right:5px;"></span> ' + option.text + '</span>');
        }
    </script>

    <script>
        const imagePreview = document.getElementById('imagePreview');
        const productImagesInput = document.getElementById('productImages');
        let mainImageId = null;

        productImagesInput.addEventListener('change', function (e) {
            if (this.files.length > 0) {
                // Remove o placeholder
                const emptyPreview = imagePreview.querySelector('.empty-preview');
                if (emptyPreview) {
                    emptyPreview.remove();
                }

                // Cria container principal se não existir
                if (!imagePreview.querySelector('.main-image-container')) {
                    const mainContainer = document.createElement('div');
                    mainContainer.className = 'main-image-container';
                    imagePreview.appendChild(mainContainer);
                }

                // Cria container secundário se não existir
                if (!imagePreview.querySelector('.secondary-images-container')) {
                    const secondaryContainer = document.createElement('div');
                    secondaryContainer.className = 'secondary-images-container';
                    imagePreview.appendChild(secondaryContainer);
                }

                // Processa cada arquivo selecionado
                Array.from(this.files).forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        // Se for a primeira imagem, define como principal
                        if (index === 0) {
                            const mainContainer = imagePreview.querySelector('.main-image-container');
                            mainContainer.innerHTML = `
                            <img src="${e.target.result}" id="mainProductImage" alt="Imagem principal" />
                        `;
                            mainImageId = `new-${index}`;
                            document.getElementById('mainImageId').value = mainImageId;
                        } else {
                            // Adiciona às miniaturas secundárias
                            const secondaryContainer = imagePreview.querySelector('.secondary-images-container');
                            const previewItem = document.createElement('div');
                            previewItem.className = 'image-preview-item';
                            previewItem.innerHTML = `
                            <img src="${e.target.result}" alt="Pré-visualização" />
                            <div class="image-actions">
                                <button type="button" onclick="setAsMain(this, 'new-${index}')" 
                                    title="Marcar como principal">
                                    <i class="fas fa-star"></i>
                                </button>
                                <button type="button" onclick="removeNewImage(this)" 
                                    title="Remover imagem">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        `;
                            secondaryContainer.appendChild(previewItem);
                        }
                    };
                    reader.readAsDataURL(file);
                });
            }
        });

        function setAsMain(button, imageId) {
            const previewItem = button.closest('.image-preview-item');
            const imgSrc = previewItem.querySelector('img').src;

            // Atualiza a imagem principal
            const mainContainer = imagePreview.querySelector('.main-image-container');
            mainContainer.innerHTML = `<img src="${imgSrc}" id="mainProductImage" alt="Imagem principal" />`;

            // Atualiza o ID da imagem principal
            mainImageId = imageId;
            document.getElementById('mainImageId').value = mainImageId;

            // Remove a imagem da lista secundária
            previewItem.remove();
        }

        function removeNewImage(button) {
            const previewItem = button.closest('.image-preview-item');
            previewItem.remove();

            // Se não houver mais imagens, mostra o placeholder
            const hasImages = imagePreview.querySelector('.main-image-container img') ||
                imagePreview.querySelector('.image-preview-item');
            if (!hasImages) {
                imagePreview.innerHTML = `
                <div class="empty-preview" onclick="document.getElementById('productImages').click()">
                    <i class="fas fa-camera"></i>
                    <span>Clique para adicionar imagens</span>
                </div>
            `;
                document.getElementById('mainImageId').value = '';
            }
        }
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