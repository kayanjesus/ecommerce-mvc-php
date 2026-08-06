<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>C - Editar Produto</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/adm/edit.css') }}" />
    <!-- CSS dos popups -->
    <link rel="stylesheet" href="{{ asset('css/popups.css') }}">
</head>

<body>
    <header class="main-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col ps-4">
                    <h1 class="store-title">CANTINHO DA ISA</h1>
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
                            <i class="bi bi-person-circle"></i> {{ Auth::user()->email }}
                        </a>
                    </div>

                    <ul class="nav flex-column sidebar-menu flex-grow-1">
                        <li class="nav-item">
                            <a class="nav-link menu-button" href="{{ route('adm.vendas') }}">
                                <i class="bi bi-house"></i> Início
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link menu-button" href="#">
                                <i class="bi bi-receipt"></i> Pedidos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link menu-button active" href="{{ route('adm.pdtestoque') }}">
                                <i class="bi bi-box-seam"></i> Produtos e estoque
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link menu-button" href="{{ route('adm.cdtproduto') }}">
                                <i class="bi bi-plus-circle"></i> Cadastro de produtos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link menu-button" href="{{ route('adm.usercadastrado') }}">
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
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="logout-button">
                                <i class="bi bi-box-arrow-right"></i> Sair
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0"><i class="bi bi-box-seam"></i> Editar Produto</h2>
                    <div>
                        <a href="{{ route('adm.pdtestoque') }}" class="btn btn-outline-secondary me-2">
                            <i class="bi bi-arrow-left"></i> Voltar
                        </a>
                        <button type="button" class="btn btn-primary" onclick="confirmarAlteracoes()">
                            <i class="bi bi-save"></i> Salvar Alterações
                        </button>
                    </div>
                </div>

                @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- As mensagens de sucesso/erro vão aparecer como popups --}}

                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('produtos.update', $produto->id_produto) }}"
                            enctype="multipart/form-data" id="editProductForm">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Coluna da Imagem e Galeria -->
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <h5>Imagem Principal</h5>

                                        @php
                                        $mainImage = $produto->imagens->where('principal', true)->first() ?? $produto->imagens->first();
                                        @endphp

                                        <div id="mainImageContainer">
                                            @if($mainImage)
                                            <img src="{{ asset($mainImage->caminho) }}"
                                                alt="{{ $produto->nome_produto }}" class="product-main-image"
                                                id="productMainImage">
                                            @else
                                            <div class="empty-image">
                                                <i class="fas fa-image fa-3x mb-2"></i>
                                                <span>Nenhuma imagem principal</span>
                                            </div>
                                            @endif
                                        </div>

                                        <div class="mt-3">
                                            <label class="d-block mb-2">Galeria de Imagens</label>
                                            <div class="thumbnail-container" id="thumbnailsContainer">
                                                @foreach($produto->imagens as $imagem)
                                                <div class="thumbnail-wrapper" data-image-id="{{ $imagem->id }}">
                                                    <img src="{{ asset($imagem->caminho) }}"
                                                        class="thumbnail {{ $imagem->principal ? 'active' : '' }}"
                                                        data-image-id="{{ $imagem->id }}"
                                                        onclick="setAsMainImage(this, {{ $imagem->id }})"
                                                        alt="Imagem do produto">
                                                    <div class="thumbnail-remove"
                                                        onclick="removeImage(this, {{ $imagem->id }})">
                                                        <i class="fas fa-times"></i>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <!-- Área para upload de novas imagens -->
                                        <div class="image-upload-area mt-3"
                                            onclick="document.getElementById('newImagesInput').click()">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                            <p class="mb-0">Clique para adicionar novas imagens</p>
                                            <small class="text-muted">Arraste ou clique para fazer upload</small>
                                        </div>

                                        <input type="file" id="newImagesInput" name="imagens[]" accept="image/*"
                                            multiple style="display: none;" onchange="previewNewImages(this)">

                                        <!-- Campos hidden para controle -->
                                        <input type="hidden" name="removed_images" id="removedImages" value="">
                                        <input type="hidden" name="main_image_id" id="mainImageId"
                                            value="{{ $mainImage->id ?? '' }}">
                                    </div>

                                    <!-- Avaliações -->
                                    <div class="card mb-3">
                                        <div class="card-header bg-primary text-white">
                                            <i class="bi bi-star"></i> Avaliações
                                        </div>
                                        <div class="card-body">
                                            @forelse($produto->avaliacao as $avaliacao)
                                            <div class="avaliacao-item mb-3">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <strong>{{ $avaliacao->usuario->name ?? 'Usuário' }}</strong>
                                                    <div class="text-warning">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <i
                                                            class="bi bi-star{{ $i <= $avaliacao->nota ? '-fill' : '' }}"></i>
                                                            @endfor
                                                    </div>
                                                </div>
                                                <p class="mb-2">{{ $avaliacao->comentario }}</p>
                                                <small class="text-muted">
                                                    {{ \Carbon\Carbon::parse($avaliacao->created_at)->format('d/m/Y H:i') }}
                                                </small>
                                            </div>
                                            @if(!$loop->last)
                                            <hr class="my-2">
                                            @endif
                                            @empty
                                            <p class="text-muted mb-0">Nenhuma avaliação ainda.</p>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>

                                <!-- Coluna do Formulário -->
                                <div class="col-md-8">
                                    <div class="row g-3">
                                        <!-- Nome do Produto -->
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="nome" class="form-label">Nome do Produto</label>
                                                <input type="text" class="form-control" id="nome" name="nome"
                                                    value="{{ old('nome', $produto->nome_produto) }}" required>
                                            </div>
                                            <p class="text-muted">Código: {{ $produto->id_produto }}</p>
                                        </div>

                                        <!-- Categorias -->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Categorias</label>
                                                <select name="categorias[]" class="form-control categorias-select"
                                                    multiple required>
                                                    @foreach ($categorias as $categoria)
                                                    <option value="{{ $categoria->id_categoria }}" {{ in_array($categoria->id_categoria, old('categorias', $produto->categorias->pluck('id_categoria')->toArray())) ? 'selected' : '' }}>
                                                        {{ $categoria->nome_categoria }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                <small class="text-muted">Selecione uma ou mais categorias</small>
                                            </div>
                                        </div>

                                        <!-- Variação -->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Variação</label>
                                                <input type="text" class="form-control" name="variacao"
                                                    value="{{ old('variacao', $produto->variacao) }}" required>
                                            </div>
                                        </div>

                                        <!-- Cores -->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Cores</label>
                                                <select name="cores[]" class="form-control cores-select" multiple
                                                    required>
                                                    @foreach ($cores as $cor)
                                                    <option value="{{ $cor->id_cor }}" data-hex="{{ $cor->codigo_hex }}"
                                                        {{ in_array($cor->id_cor, old('cores', $produto->variacoes->pluck('cor_id')->toArray())) ? 'selected' : '' }}>
                                                        <span class="color-option"
                                                            style="background-color: {{ $cor->codigo_hex }}"></span>
                                                        {{ $cor->nome }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Marca -->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Marca</label>
                                                <input type="text" class="form-control" name="marca"
                                                    value="{{ old('marca', $produto->marca) }}" required>
                                            </div>
                                        </div>

                                        <!-- Tamanhos -->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Tamanhos</label>
                                                <select name="tamanhos[]" class="form-control tamanhos-select" multiple
                                                    required>
                                                    @foreach ($tamanhos as $tamanho)
                                                    <option value="{{ $tamanho->id_tamanho }}" {{ in_array($tamanho->id_tamanho, old('tamanhos', $produto->variacoes->pluck('tamanho_id')->toArray())) ? 'selected' : '' }}>
                                                        {{ $tamanho->nome }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Tecido -->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Tecido</label>
                                                <input type="text" class="form-control" name="tecido"
                                                    value="{{ old('tecido', $produto->tecido) }}" required>
                                            </div>
                                        </div>

                                        <!-- Estação -->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Estação</label>
                                                <select name="estacao" class="form-control" required>
                                                    <option value="Verão" {{ old('estacao', $produto->estacao) == 'Verão' ? 'selected' : '' }}>Verão</option>
                                                    <option value="Inverno" {{ old('estacao', $produto->estacao) == 'Inverno' ? 'selected' : '' }}>Inverno
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Preço -->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Preço (R$)</label>
                                                <input type="number" step="0.01" class="form-control" name="valor"
                                                    value="{{ old('valor', $produto->preco) }}" required>
                                            </div>
                                        </div>

                                        <!-- Estoque -->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Estoque Total</label>
                                                <input type="number" class="form-control" name="estoque"
                                                    value="{{ old('estoque', $produto->variacoes->sum('estoque')) }}"
                                                    required>
                                                <small class="text-muted">Quantidade total em estoque</small>
                                            </div>
                                        </div>

                                        <!-- Modelo -->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Modelo</label>
                                                <input type="text" class="form-control" name="modelo"
                                                    value="{{ old('modelo', $produto->modelo) }}" required>
                                            </div>
                                        </div>

                                        <!-- Descrição -->
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Descrição</label>
                                                <textarea class="form-control" name="descricao"
                                                    rows="4">{{ old('descricao', $produto->descricao) }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Script dos popups -->
    <script src="{{ asset('js/popups.js') }}"></script>

    <script>
        // ============================================
        // FUNÇÃO PARA CONFIRMAR ALTERAÇÕES
        // ============================================

        function confirmarAlteracoes() {
            // Valida os campos obrigatórios
            if (!validarFormulario()) {
                return;
            }

            // Mostra confirmação
            confirmar(
                'Deseja salvar as alterações feitas no produto?',
                function() {
                    // Se clicou em SIM
                    salvarAlteracoes();
                },
                function() {
                    // Se clicou em NÃO
                    console.log('Edição cancelada');
                }
            );
        }

        // ============================================
        // FUNÇÃO PARA SALVAR ALTERAÇÕES (COM LOADING)
        // ============================================

        function salvarAlteracoes() {
            // MOSTRA O LOADING
            const load = loading('Salvando alterações...');

            // Pequeno delay para o loading aparecer
            setTimeout(function() {
                // Envia o formulário
                document.getElementById('editProductForm').submit();
            }, 500);
        }

        // ============================================
        // FUNÇÃO DE VALIDAÇÃO (MELHORADA)
        // ============================================

        function validarFormulario() {
            const nome = document.getElementById('nome').value.trim();
            const categorias = document.querySelector('.categorias-select');
            const cores = document.querySelector('.cores-select');
            const tamanhos = document.querySelector('.tamanhos-select');

            let erro = '';

            if (!nome) {
                erro = 'Por favor, preencha o nome do produto';
            } else if (categorias && categorias.selectedOptions.length === 0) {
                erro = 'Por favor, selecione pelo menos uma categoria';
            } else if (cores && cores.selectedOptions.length === 0) {
                erro = 'Por favor, selecione pelo menos uma cor';
            } else if (tamanhos && tamanhos.selectedOptions.length === 0) {
                erro = 'Por favor, selecione pelo menos um tamanho';
            }

            if (erro) {
                mostrarMensagem(erro, 'warning');
                return false;
            }

            return true;
        }

        // ============================================
        // FUNÇÕES EXISTENTES (MANTIDAS IGUAIS)
        // ============================================

        // Variáveis globais
        let removedImages = [];
        let newImages = [];

        // Inicialização do Select2
        $(document).ready(function() {
            $('.categorias-select').select2({
                placeholder: "Selecione as categorias",
                allowClear: true,
                width: '100%'
            });

            $('.cores-select').select2({
                templateResult: formatColor,
                templateSelection: formatColor,
                escapeMarkup: function(m) {
                    return m;
                },
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
                if (hex) {
                    return $('<span><span class="color-option" style="background-color:' + hex + '"></span> ' + option.text + '</span>');
                }
                return option.text;
            }
        });

        // Define uma imagem como principal
        function setAsMainImage(element, imageId) {
            // Remove a classe 'active' de todas as miniaturas
            document.querySelectorAll('.thumbnail').forEach(thumb => {
                thumb.classList.remove('active');
            });

            // Adiciona a classe 'active' na miniatura clicada
            element.classList.add('active');

            // Atualiza a imagem principal
            const mainImageContainer = document.getElementById('mainImageContainer');
            mainImageContainer.innerHTML = `<img src="${element.src}" class="product-main-image" id="productMainImage">`;

            // Atualiza o campo hidden com o ID da imagem principal
            document.getElementById('mainImageId').value = imageId;
        }

        // Remove uma imagem
        function removeImage(element, imageId) {
            const thumbnailWrapper = element.closest('.thumbnail-wrapper');

            // Verifica se é a imagem principal
            const isMainImage = thumbnailWrapper.querySelector('.thumbnail').classList.contains('active');

            // Remove a miniatura
            thumbnailWrapper.style.opacity = '0';
            setTimeout(() => {
                thumbnailWrapper.remove();

                // Adiciona ao array de imagens removidas
                removedImages.push(imageId);
                document.getElementById('removedImages').value = removedImages.join(',');

                // Se era a imagem principal, define outra como principal
                if (isMainImage) {
                    const remainingThumbnails = document.querySelectorAll('.thumbnail');
                    if (remainingThumbnails.length > 0) {
                        const firstThumbnail = remainingThumbnails[0];
                        const firstImageId = firstThumbnail.dataset.imageId;
                        setAsMainImage(firstThumbnail, firstImageId);
                    } else {
                        // Não há mais imagens
                        document.getElementById('mainImageId').value = '';
                        document.getElementById('mainImageContainer').innerHTML = `
                            <div class="empty-image">
                                <i class="fas fa-image fa-3x mb-2"></i>
                                <span>Nenhuma imagem principal</span>
                            </div>
                        `;
                    }
                }
            }, 300);
        }

        // Pré-visualização de novas imagens
        function previewNewImages(input) {
            if (input.files && input.files.length > 0) {
                const container = document.getElementById('thumbnailsContainer');

                Array.from(input.files).forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const wrapper = document.createElement('div');
                        wrapper.className = 'thumbnail-wrapper';
                        wrapper.dataset.newIndex = index;

                        const thumbnail = document.createElement('img');
                        thumbnail.className = 'thumbnail';
                        thumbnail.src = e.target.result;
                        thumbnail.dataset.newIndex = index;
                        thumbnail.onclick = function() {
                            setNewImageAsMain(this, index);
                        };

                        const removeBtn = document.createElement('div');
                        removeBtn.className = 'thumbnail-remove';
                        removeBtn.innerHTML = '<i class="fas fa-times"></i>';
                        removeBtn.onclick = function() {
                            removeNewImage(this, index);
                        };

                        wrapper.appendChild(thumbnail);
                        wrapper.appendChild(removeBtn);
                        container.appendChild(wrapper);

                        // Se esta for a primeira nova imagem e não houver imagem principal, define como principal
                        if (index === 0 && !document.getElementById('mainImageId').value) {
                            setNewImageAsMain(thumbnail, index);
                        }
                    };
                    reader.readAsDataURL(file);
                });
            }
        }

        // Define uma nova imagem como principal
        function setNewImageAsMain(element, newIndex) {
            // Remove a classe 'active' de todas as miniaturas
            document.querySelectorAll('.thumbnail').forEach(thumb => {
                thumb.classList.remove('active');
            });

            // Adiciona a classe 'active' na miniatura clicada
            element.classList.add('active');

            // Atualiza a imagem principal
            const mainImageContainer = document.getElementById('mainImageContainer');
            mainImageContainer.innerHTML = `<img src="${element.src}" class="product-main-image" id="productMainImage">`;

            // Atualiza o campo hidden para indicar que é uma nova imagem
            document.getElementById('mainImageId').value = 'new-' + newIndex;
        }

        // Remove uma nova imagem (ainda não salva)
        function removeNewImage(element, newIndex) {
            const wrapper = element.closest('.thumbnail-wrapper[data-new-index="' + newIndex + '"]');
            const isMainImage = wrapper.querySelector('.thumbnail').classList.contains('active');

            wrapper.style.opacity = '0';
            setTimeout(() => {
                wrapper.remove();

                // Se era a imagem principal, define outra como principal
                if (isMainImage) {
                    const remainingThumbnails = document.querySelectorAll('.thumbnail');
                    if (remainingThumbnails.length > 0) {
                        const firstThumbnail = remainingThumbnails[0];
                        if (firstThumbnail.dataset.imageId) {
                            // É uma imagem existente
                            setAsMainImage(firstThumbnail, firstThumbnail.dataset.imageId);
                        } else if (firstThumbnail.dataset.newIndex) {
                            // É uma nova imagem
                            setNewImageAsMain(firstThumbnail, firstThumbnail.dataset.newIndex);
                        }
                    } else {
                        document.getElementById('mainImageId').value = '';
                        document.getElementById('mainImageContainer').innerHTML = `
                            <div class="empty-image">
                                <i class="fas fa-image fa-3x mb-2"></i>
                                <span>Nenhuma imagem principal</span>
                            </div>
                        `;
                    }
                }
            }, 300);
        }

        // Remove a validação antiga (já que agora usamos a nova)
        // document.getElementById('editProductForm').addEventListener('submit', function (e) { ... });
    </script>
</body>

</html>