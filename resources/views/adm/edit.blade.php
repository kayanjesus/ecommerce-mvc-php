<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Cantinho da Isa | Editar Produto</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/adm/cadastro de produtos.css') }}" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--multiple {
            min-height: 40px;
        }

        /* Estilo para pré-visualização de imagens */
        .image-preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
        }

        .image-preview-item {
            position: relative;
            width: 80px;
            height: 80px;
            border: 1px solid #ddd;
            border-radius: 4px;
            overflow: hidden;
        }

        .image-preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .image-actions {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0,0,0,0.7);
            display: flex;
            justify-content: space-around;
            padding: 5px;
        }

        .image-actions button {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 12px;
        }

        .main-image-badge {
            position: absolute;
            top: 5px;
            right: 5px;
            background: #4CAF50;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }

        .color-option {
            display: inline-block;
            width: 15px;
            height: 15px;
            margin-right: 5px;
            vertical-align: middle;
            border-radius: 50%; /* Adicionado para cores redondas */
            border: 1px solid #ccc; /* Adicionado para cores claras como branco */
        }

        .empty-preview {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100px;
            border: 2px dashed #ccc;
            color: #777;
            cursor: pointer;
        }

        .carousel-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 20px;
        }

        .main-image {
            width: 100%;
            height: 400px;
            border: 1px solid #ddd;
            border-radius: 4px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .main-image img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .thumbnail-container {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .thumbnail-wrapper {
            position: relative;
            width: 80px;
            height: 80px;
            border: 1px solid #ddd;
            border-radius: 4px;
            overflow: hidden;
            cursor: pointer;
        }

        .thumbnail.active {
            border: 2px solid #4CAF50;
        }

        .thumbnail-actions {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0,0,0,0.7);
            display: flex;
            justify-content: center;
            padding: 5px;
        }

        .thumbnail-actions button {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 12px;
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
                <a href="{{ route('adm.pdtestoque') }}"><button class="menu-btn">Voltar</button></a>
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
            <h1>Editar Produto</h1>
            <form method="POST" action="{{ route('produtos.update', $produto->id_produto) }}" enctype="multipart/form-data"> {{-- Corrigido: rota 'produtos.update' --}}
                @csrf
                @method('PUT')

                <div class="product-main-info">
                    <div class="product-image-container">
                        <div class="carousel-container">
                            <div class="carousel-track">
                                <!-- Imagem principal grande -->
                                @php
                                    $mainImage = $produto->imagens->where('principal', true)->first() ?? $produto->imagens->first();
                                @endphp
                                <div class="main-image">
                                    <img src="{{ asset($mainImage->caminho) }}" id="mainProductImage" alt="Imagem principal" />
                                </div>
                            </div>

                            <!-- Miniaturas (para navegação) -->
                            <div class="thumbnail-container">
                                @foreach($produto->imagens as $imagem)
                                    <div class="thumbnail-wrapper">
                                        <img src="{{ asset($imagem->caminho) }}"
                                             class="thumbnail {{ $imagem->principal ? 'active' : '' }}"
                                             onclick="changeMainImage(this, {{ $imagem->id }})"
                                             data-image-id="{{ $imagem->id }}" /> {{-- Adicionado data-image-id --}}
                                        <div class="thumbnail-actions">
                                            <button type="button" onclick="removeImage(this, {{ $imagem->id }})"
                                                    title="Remover imagem">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                                <!-- Espaço para novas miniaturas quando novas imagens são adicionadas -->
                                <div id="newThumbnailsContainer"></div>
                            </div>
                        </div>

                        <input type="file" id="productImages" name="imagens[]" accept="image/*" multiple style="display: none;" />
                        <input type="hidden" name="removed_images" id="removedImages">
                        <input type="hidden" name="main_image_id" id="mainImageId"
                               value="{{ $mainImage->id ?? '' }}">
                        <button type="button" class="btn-image-upload" onclick="document.getElementById('productImages').click()">
                            <i class="fas fa-upload"></i> Adicionar Imagens
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
                                <input type="text" name="nome" id="nome"
                                       value="{{ old('nome', $produto->nome_produto) }}" required />
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="categoria">Tipo do Produto</label>
                                <select name="categorias[]" id="categoria" class="form-control categorias-select" multiple required>
                                    @foreach ($categorias as $categoria)
                                        <option value="{{ $categoria->id_categoria }}"
                                            {{ $produto->categorias->contains('id_categoria', $categoria->id_categoria) ? 'selected' : '' }}>
                                            {{ $categoria->nome_categoria }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="descricao">Descrição</label>
                                <input type="text" name="descricao" id="descricao"
                                       value="{{ old('descricao', $produto->variacao) }}" required />
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="cor">Cor</label>
                                <select name="cores[]" id="cor" multiple required class="cores-select">
                                    @foreach ($cores as $cor)
                                        <option value="{{ $cor->id_cor }}" data-hex="{{ $cor->codigo_hex }}"
                                            {{ $produto->variacoes->contains('cor_id', $cor->id_cor) ? 'selected' : '' }}>
                                            <span class="color-option" style="background-color: {{ $cor->codigo_hex }}"></span>
                                            {{ $cor->nome }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="estacao">Estação</label>
                                <select name="estacao" id="estacao" required>
                                    <option value="Verão" {{ $produto->estacao == 'Verão' ? 'selected' : '' }}>Verão</option>
                                    <option value="Inverno" {{ $produto->estacao == 'Inverno' ? 'selected' : '' }}>Inverno</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="marca">Marca</label>
                                <input type="text" name="marca" id="marca"
                                       value="{{ old('marca', $produto->marca) }}" required />
                            </div>
                            <div class="form-group">
                                <label for="valor">Valor (R$)</label>
                                <input type="number" step="0.01" name="valor" id="valor"
                                       value="{{ old('valor', $produto->preco) }}" required />
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="tamanho">Tamanho</label>
                                <select name="tamanhos[]" id="tamanho" multiple required class="tamanhos-select">
                                    @foreach ($tamanhos as $tamanho)
                                        <option value="{{ $tamanho->id_tamanho }}"
                                            {{ $produto->variacoes->contains('tamanho_id', $tamanho->id_tamanho) ? 'selected' : '' }}>
                                            {{ $tamanho->nome }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="estoque">Estoque (por tamanho)</label>
                                <input type="number" name="estoque" id="estoque"
                                       value="{{ old('estoque', $produto->variacoes->first()->estoque ?? 0) }}" required />
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="tecido">Tecido</label>
                                <input type="text" name="tecido" id="tecido"
                                       value="{{ old('tecido', $produto->tecido) }}" required />
                            </div>
                            <div class="form-group">
                                <label for="modelo">Modelo</label>
                                <input type="text" name="modelo" id="modelo"
                                       value="{{ old('modelo', $produto->modelo) }}" required />
                            </div>
                        </div>
                        <div class="form-row">
                            <button type="submit" class="btn-submit">Atualizar Produto</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        // Inicialização do Select2 para os selects múltiplos
        $(document).ready(function () {
            $('.categorias-select').select2({
                placeholder: "Selecione as categorias",
                allowClear: true,
                width: '100%'
            });

            $('.cores-select').select2({
                templateResult: formatColor,
                templateSelection: formatColor,
                escapeMarkup: function(m) { return m; },
                placeholder: "Selecione as cores",
                width: '100%'
            });

            $('.tamanhos-select').select2({
                placeholder: "Selecione os tamanhos",
                allowClear: true,
                width: '100%'
            });

            function formatColor(option) {
                if (!option.id) return option.text;
                var hex = $(option.element).data('hex');
                return $('<span><span class="color-option" style="background-color:'+hex+'"></span> ' + option.text + '</span>');
            }
        });
    </script>
    <!-- Scripts JavaScript -->
    <script>
        // Funções para manipulação de imagens
        let removedImages = [];
        let newImages = [];

        // Função para trocar a imagem principal ao clicar em uma miniatura
        function changeMainImage(thumbnail, imageId) {
            const newSrc = thumbnail.src;

            // Atualiza a imagem principal
            document.getElementById('mainProductImage').src = newSrc;

            // Atualiza o campo hidden com o ID da imagem principal
            document.getElementById('mainImageId').value = imageId;

            // Remove a classe 'active' de todas as miniaturas
            document.querySelectorAll('.thumbnail').forEach(thumb => {
                thumb.classList.remove('active');
            });

            // Adiciona a classe 'active' apenas na miniatura clicada
            thumbnail.classList.add('active');
        }

        // Função para remover imagem
        function removeImage(button, imageId) {
            const thumbnailWrapper = button.closest('.thumbnail-wrapper');

            // Se for a imagem principal, precisamos definir uma nova principal
            if (thumbnailWrapper.querySelector('.thumbnail').classList.contains('active')) {
                // Encontra a primeira miniatura que não está sendo removida
                const nextThumbnail = document.querySelector('.thumbnail-wrapper:not(.removing) .thumbnail');
                if (nextThumbnail) {
                    changeMainImage(nextThumbnail, nextThumbnail.dataset.imageId);
                } else {
                    // Não há mais imagens
                    document.getElementById('mainImageId').value = '';
                    document.getElementById('mainProductImage').src = '';
                }
            }

            // Marca como removendo para evitar seleção durante a animação
            thumbnailWrapper.classList.add('removing');

            // Animação de fade out
            thumbnailWrapper.style.opacity = '0';
            setTimeout(() => {
                thumbnailWrapper.remove();

                // Adiciona o ID da imagem removida ao array
                if (!isNaN(imageId)) {
                    removedImages.push(imageId);
                    document.getElementById('removedImages').value = removedImages.join(',');
                }
            }, 300);
        }

        // Pré-visualização de novas imagens
        document.getElementById('productImages').addEventListener('change', function(e) {
            const thumbnailContainer = document.getElementById('newThumbnailsContainer');

            Array.from(this.files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const thumbnailWrapper = document.createElement('div');
                    thumbnailWrapper.className = 'thumbnail-wrapper';
                    thumbnailWrapper.innerHTML = `
                        <img src="${e.target.result}"
                             class="thumbnail"
                             onclick="changeMainImage(this, 'new-${index}')"
                             data-image-id="new-${index}" />
                        <div class="thumbnail-actions">
                            <button type="button" onclick="removeNewImage(this, 'new-${index}')"
                                    title="Remover imagem">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `;
                    thumbnailContainer.appendChild(thumbnailWrapper);

                    // Se esta for a primeira imagem adicionada e não houver imagem principal, define como principal
                    if (index === 0 && !document.getElementById('mainImageId').value) {
                        changeMainImage(thumbnailWrapper.querySelector('.thumbnail'), 'new-0');
                    }
                };
                reader.readAsDataURL(file);
            });
        });

        // Função para remover novas imagens (ainda não salvas)
        function removeNewImage(button, imageId) {
            const thumbnailWrapper = button.closest('.thumbnail-wrapper');

            // Se for a imagem principal, precisamos definir uma nova principal
            if (thumbnailWrapper.querySelector('.thumbnail').classList.contains('active')) {
                // Encontra a primeira miniatura que não está sendo removida
                const nextThumbnail = document.querySelector('.thumbnail-wrapper:not(.removing) .thumbnail');
                if (nextThumbnail) {
                    changeMainImage(nextThumbnail, nextThumbnail.dataset.imageId);
                } else {
                    // Volta para a primeira imagem existente, se houver
                    const existingThumbnail = document.querySelector('.thumbnail:not([data-image-id^="new-"])');
                    if (existingThumbnail) {
                        changeMainImage(existingThumbnail, existingThumbnail.dataset.imageId);
                    } else {
                        // Não há mais imagens
                        document.getElementById('mainImageId').value = '';
                        document.getElementById('mainProductImage').src = '';
                    }
                }
            }

            thumbnailWrapper.classList.add('removing');
            thumbnailWrapper.style.opacity = '0';
            setTimeout(() => {
                thumbnailWrapper.remove();
            }, 300);
        }
    </script>
</body>
</html>