<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cantinho da Isa - Cadastro de Produtos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset("css/adm/cadastro de produtos.css") }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<body>
    <!-- Modal para erros (agora dentro do body) -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="errorModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i> Erro ao Cadastrar
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-times-circle text-danger fa-2x me-3"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-2">Não foi possível cadastrar o produto</h6>
                            <p class="mb-0" id="modalErrorMessage"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" onclick="focusOnNomeField()">
                        <i class="fas fa-edit me-1"></i> Corrigir Nome
                    </button>
                </div>
            </div>
        </div>
    </div>

    <header class="main-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col ps-4">
                    <h1 class="store-title"><a href="{{ route('home.index') }}" class="text-decoration-none text-white">CANTINHO DA ISA</a></h1>
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
                            <a class="nav-link menu-button active" href="{{ route('adm.cdtproduto') }}">
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
                    <h2 class="mb-0"><i class="bi bi-plus-circle"></i> Cadastro de Produtos</h2>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <h5 class="alert-heading"><i class="fas fa-exclamation-circle me-2"></i> Erros encontrados:</h5>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                @if(!str_contains($error, 'Já existe um produto'))
                                    <li>{{ $error }}</li>
                                @endif
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('produtos.store') }}" enctype="multipart/form-data" id="productForm">
                            @csrf
                            <div class="row">
                                <!-- Coluna da Imagem -->
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <h5>Imagem Principal</h5>
                                        
                                        <div id="mainImageContainer" class="mb-3">
                                            <div class="empty-image">
                                                <i class="fas fa-image fa-3x mb-2"></i>
                                                <span>Nenhuma imagem carregada</span>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="d-block mb-2">Galeria de Imagens</label>
                                            <div class="thumbnail-container" id="thumbnailsContainer">
                                                <!-- Miniaturas serão adicionadas aqui via JavaScript -->
                                            </div>
                                        </div>
                                        
                                        <!-- Área para upload -->
                                        <div class="image-upload-area" onclick="document.getElementById('imageInput').click()">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                            <p class="mb-0">Clique para adicionar imagens</p>
                                            <small class="text-muted">Arraste ou clique para fazer upload</small>
                                        </div>
                                        
                                        <input type="file" 
                                               id="imageInput" 
                                               name="imagens[]" 
                                               accept="image/*" 
                                               multiple 
                                               style="display: none;" 
                                               onchange="handleImageUpload(this)"
                                               required>
                                        
                                        <!-- Campo hidden para imagem principal -->
                                        <input type="hidden" name="main_image_id" id="mainImageId" value="">
                                    </div>
                                </div>

                                <!-- Coluna do Formulário -->
                                <div class="col-md-8">
                                    <div class="row g-3">
                                        <!-- Nome do Produto -->
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="nome" class="form-label">Nome do Produto *</label>
                                                <input type="text" 
                                                       class="form-control {{ $errors->has('nome') ? 'is-invalid border-danger' : '' }}" 
                                                       name="nome" 
                                                       id="nome" 
                                                       placeholder="Digite o nome do produto" 
                                                       value="{{ old('nome') }}"
                                                       required>
                                                @if($errors->has('nome'))
                                                    <div class="text-danger mt-1 fw-semibold">
                                                        <small><i class="fas fa-exclamation-circle"></i> {{ $errors->first('nome') }}</small>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Categorias -->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Categorias *</label>
                                                <select name="categorias[]" class="form-control categorias-select" multiple required>
                                                    @foreach ($categorias as $categoria)
                                                        <option value="{{ $categoria->id_categoria }}"
                                                            {{ in_array($categoria->id_categoria, old('categorias', [])) ? 'selected' : '' }}>
                                                            {{ $categoria->nome_categoria }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <small class="text-muted">Selecione uma ou mais categorias</small>
                                            </div>
                                        </div>

                                        <!-- Descrição/Variação -->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="descricao" class="form-label">Descrição/Variação *</label>
                                                <input type="text" 
                                                       class="form-control" 
                                                       name="descricao" 
                                                       id="descricao" 
                                                       placeholder="Ex: Estilo, Características" 
                                                       value="{{ old('descricao') }}"
                                                       required>
                                            </div>
                                        </div>

                                        <!-- Cores -->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Cores *</label>
                                                <select name="cores[]" id="cor" multiple required class="form-control cores-select">
                                                    @foreach ($cores as $cor)
                                                        <option value="{{ $cor->id_cor }}" 
                                                                data-hex="{{ $cor->codigo_hex }}"
                                                                {{ in_array($cor->id_cor, old('cores', [])) ? 'selected' : '' }}>
                                                            <span class="color-option" style="background-color: {{ $cor->codigo_hex }}"></span>
                                                            {{ $cor->nome }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Estação -->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="estacao" class="form-label">Estação *</label>
                                                <select name="estacao" id="estacao" class="form-select" required>
                                                    <option value="" disabled {{ !old('estacao') ? 'selected' : '' }}>Selecione uma estação</option>
                                                    <option value="Verão" {{ old('estacao') == 'Verão' ? 'selected' : '' }}>Verão</option>
                                                    <option value="Inverno" {{ old('estacao') == 'Inverno' ? 'selected' : '' }}>Inverno</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Marca -->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="marca" class="form-label">Marca *</label>
                                                <input type="text" 
                                                       class="form-control" 
                                                       name="marca" 
                                                       id="marca" 
                                                       placeholder="Ex: Nike, Zara" 
                                                       value="{{ old('marca') }}"
                                                       required>
                                            </div>
                                        </div>

                                        <!-- Preço -->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="valor" class="form-label">Preço (R$) *</label>
                                                <input type="number" 
                                                       step="0.01" 
                                                       class="form-control" 
                                                       name="valor" 
                                                       id="valor" 
                                                       placeholder="Ex: 59.90" 
                                                       value="{{ old('valor') }}"
                                                       required>
                                            </div>
                                        </div>

                                        <!-- Tamanhos -->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Tamanhos *</label>
                                                <select name="tamanhos[]" id="tamanho" multiple required class="form-control tamanhos-select">
                                                    @foreach ($tamanhos as $tamanho)
                                                        <option value="{{ $tamanho->id_tamanho }}"
                                                            {{ in_array($tamanho->id_tamanho, old('tamanhos', [])) ? 'selected' : '' }}>
                                                            {{ $tamanho->nome }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Estoque -->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="estoque" class="form-label">Estoque Total *</label>
                                                <input type="number" 
                                                       class="form-control" 
                                                       name="estoque" 
                                                       id="estoque" 
                                                       placeholder="Quantidade total" 
                                                       value="{{ old('estoque') }}"
                                                       required>
                                                <small class="text-muted">Quantidade total em estoque</small>
                                            </div>
                                        </div>

                                        <!-- Tecido -->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="tecido" class="form-label">Tecido *</label>
                                                <input type="text" 
                                                       class="form-control" 
                                                       name="tecido" 
                                                       id="tecido" 
                                                       placeholder="Ex: Algodão, Jeans" 
                                                       value="{{ old('tecido') }}"
                                                       required>
                                            </div>
                                        </div>

                                        <!-- Modelo -->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="modelo" class="form-label">Modelo *</label>
                                                <input type="text" 
                                                       class="form-control" 
                                                       name="modelo" 
                                                       id="modelo" 
                                                       placeholder="Ex: Camiseta Básica" 
                                                       value="{{ old('modelo') }}"
                                                       required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Cadastrar Produto
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Adicione o Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script>
        // Inicialização do Select2
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
                if (hex) {
                    return $('<span><span class="color-option" style="background-color:' + hex + '"></span> ' + option.text + '</span>');
                }
                return option.text;
            }
        });

        // Variáveis globais
        let uploadedImages = [];
        let mainImageIndex = null;

        // Manipula o upload de imagens
        function handleImageUpload(input) {
            if (input.files && input.files.length > 0) {
                Array.from(input.files).forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const imageData = {
                            id: `new-${index}`,
                            src: e.target.result,
                            file: file
                        };
                        
                        uploadedImages.push(imageData);
                        createThumbnail(imageData);
                        
                        // Define a primeira imagem como principal
                        if (uploadedImages.length === 1) {
                            setAsMainImage(imageData.id);
                        }
                    };
                    reader.readAsDataURL(file);
                });
            }
        }

        // Cria miniatura na galeria
        function createThumbnail(imageData) {
            const container = document.getElementById('thumbnailsContainer');
            
            const wrapper = document.createElement('div');
            wrapper.className = 'thumbnail-wrapper';
            wrapper.dataset.imageId = imageData.id;
            
            const thumbnail = document.createElement('img');
            thumbnail.className = 'thumbnail';
            thumbnail.src = imageData.src;
            thumbnail.dataset.imageId = imageData.id;
            thumbnail.onclick = function() {
                setAsMainImage(imageData.id);
            };
            
            const removeBtn = document.createElement('div');
            removeBtn.className = 'thumbnail-remove';
            removeBtn.innerHTML = '<i class="fas fa-times"></i>';
            removeBtn.onclick = function() {
                removeImage(imageData.id);
            };
            
            wrapper.appendChild(thumbnail);
            wrapper.appendChild(removeBtn);
            container.appendChild(wrapper);
        }

        // Define uma imagem como principal
        function setAsMainImage(imageId) {
            // Remove a classe 'active' de todas as miniaturas
            document.querySelectorAll('.thumbnail').forEach(thumb => {
                thumb.classList.remove('active');
            });
            
            // Adiciona a classe 'active' na miniatura clicada
            const thumbnail = document.querySelector(`.thumbnail[data-image-id="${imageId}"]`);
            if (thumbnail) {
                thumbnail.classList.add('active');
            }
            
            // Atualiza a imagem principal
            const mainImageContainer = document.getElementById('mainImageContainer');
            const imageData = uploadedImages.find(img => img.id === imageId);
            
            if (imageData) {
                mainImageContainer.innerHTML = `
                    <img src="${imageData.src}" class="product-main-image" alt="Imagem principal do produto">
                `;
            }
            
            // Atualiza o campo hidden
            mainImageIndex = imageId;
            document.getElementById('mainImageId').value = imageId;
        }

        // Remove uma imagem
        function removeImage(imageId) {
            // Remove do array
            uploadedImages = uploadedImages.filter(img => img.id !== imageId);
            
            // Remove a miniatura
            const wrapper = document.querySelector(`.thumbnail-wrapper[data-image-id="${imageId}"]`);
            if (wrapper) {
                wrapper.style.opacity = '0';
                setTimeout(() => {
                    wrapper.remove();
                    
                    // Se a imagem removida era a principal, define outra como principal
                    if (imageId === mainImageIndex) {
                        if (uploadedImages.length > 0) {
                            const nextImage = uploadedImages[0];
                            setAsMainImage(nextImage.id);
                        } else {
                            // Não há mais imagens
                            mainImageIndex = null;
                            document.getElementById('mainImageId').value = '';
                            document.getElementById('mainImageContainer').innerHTML = `
                                <div class="empty-image">
                                    <i class="fas fa-image fa-3x mb-2"></i>
                                    <span>Nenhuma imagem carregada</span>
                                </div>
                            `;
                        }
                    }
                }, 300);
            }
        }

        // Validação do formulário
        document.getElementById('productForm').addEventListener('submit', function(e) {
            // Valida campos obrigatórios
            const nome = document.getElementById('nome').value.trim();
            const categorias = document.querySelector('.categorias-select').value;
            const cores = document.querySelector('.cores-select').value;
            const tamanhos = document.querySelector('.tamanhos-select').value;
            const estacao = document.getElementById('estacao').value;
            const valor = document.getElementById('valor').value;
            const estoque = document.getElementById('estoque').value;
            
            let isValid = true;
            let errorMessage = '';
            
            if (!nome) {
                isValid = false;
                errorMessage += '• Nome do produto é obrigatório\n';
            }
            
            if (!categorias || categorias.length === 0) {
                isValid = false;
                errorMessage += '• Selecione pelo menos uma categoria\n';
            }
            
            if (!cores || cores.length === 0) {
                isValid = false;
                errorMessage += '• Selecione pelo menos uma cor\n';
            }
            
            if (!tamanhos || tamanhos.length === 0) {
                isValid = false;
                errorMessage += '• Selecione pelo menos um tamanho\n';
            }
            
            if (!estacao) {
                isValid = false;
                errorMessage += '• Selecione uma estação\n';
            }
            
            if (!valor || parseFloat(valor) <= 0) {
                isValid = false;
                errorMessage += '• Preço deve ser maior que zero\n';
            }
            
            if (!estoque || parseInt(estoque) < 0) {
                isValid = false;
                errorMessage += '• Estoque deve ser um número positivo\n';
            }
            
            if (uploadedImages.length === 0) {
                isValid = false;
                errorMessage += '• É necessário carregar pelo menos uma imagem\n';
            }
            
            if (!isValid) {
                e.preventDefault();
                alert('Por favor, corrija os seguintes erros:\n\n' + errorMessage);
                return false;
            }
            
            // Mostra loading
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Cadastrando...';
            submitBtn.disabled = true;
        });

        // ============= TRATAMENTO DE ERROS =============
        
        // Função para mostrar modal de erro
        function showErrorModal(message) {
            document.getElementById('modalErrorMessage').textContent = message;
            const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
            errorModal.show();
        }
        
        // Função para focar no campo nome
        function focusOnNomeField() {
            const errorModal = bootstrap.Modal.getInstance(document.getElementById('errorModal'));
            if (errorModal) {
                errorModal.hide();
            }
            document.getElementById('nome').focus();
            document.getElementById('nome').select();
        }
        
        // Verifica se há erro de nome duplicado ao carregar a página
        document.addEventListener('DOMContentLoaded', function() {
            @if($errors->has('nome') && old('duplicate_error') == 'nome')
                setTimeout(function() {
                    showErrorModal('{{ $errors->first("nome") }}');
                    document.getElementById('nome').focus();
                }, 300);
            @endif
        });

        // Sugestão automática de nome único
        document.getElementById('nome').addEventListener('blur', function() {
            const nome = this.value.trim();
            if (nome.length < 3) {
                return;
            }
            
            // Remove qualquer sugestão anterior
            const existingSuggestion = document.getElementById('nomeSuggestion');
            if (existingSuggestion) {
                existingSuggestion.remove();
            }
            
            // Cria uma sugestão visual
            const suggestionDiv = document.createElement('div');
            suggestionDiv.id = 'nomeSuggestion';
            suggestionDiv.className = 'text-muted mt-1 small';
            suggestionDiv.innerHTML = '<i class="fas fa-lightbulb"></i> Sugestão para URL: ' + 
                nome.toLowerCase()
                    .normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/[^a-z0-9-]/g, '');
            
            this.parentNode.appendChild(suggestionDiv);
        });

        // Previne envio duplo
        let isSubmitting = false;
        document.getElementById('productForm').addEventListener('submit', function(e) {
            if (isSubmitting) {
                e.preventDefault();
                return false;
            }
            isSubmitting = true;
            
            // Adiciona um pequeno delay para mostrar o loading
            setTimeout(() => {
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Cadastrando...';
                submitBtn.disabled = true;
            }, 100);
        });

    </script>

    <style>
        /* Estilos para mensagens de erro específicas */
        .is-invalid {
            border-color: #dc3545 !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }
        
        .is-invalid:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
        }
        
        .border-danger {
            border-width: 2px !important;
        }
        
        /* Estilo para mensagem de erro específica */
        .text-danger small i {
            margin-right: 5px;
        }
        
        /* Estilo para sugestão */
        #nomeSuggestion {
            font-size: 0.85rem;
            padding: 3px 8px;
            background-color: #f8f9fa;
            border-radius: 4px;
            border-left: 3px solid #6c757d;
        }
        
        #nomeSuggestion i {
            color: #ffc107;
        }
    </style>
</body>
</html>