<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Moda Kids | Editar Produto</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/adm/cadastro de produtos.css') }}" />
    <style>
        /* Seus estilos CSS existentes aqui */
        .select2-container--default .select2-selection--multiple {
            min-height: 40px;
        }

        .color-option {
            display: inline-block;
            width: 15px;
            height: 15px;
            margin-right: 5px;
            vertical-align: middle;
            border-radius: 50%;
            border: 1px solid #ccc;
        }

        /* Adicione outros estilos conforme necessário */
    </style>
</head>

<body>
    <header class="main-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col ps-4">
                    <h1 class="store-title">Moda Kids</h1>
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
                        <button type="submit" form="editProductForm" class="btn btn-primary">
                            <i class="bi bi-pencil"></i> Salvar Alterações
                        </button>
                    </div>
                </div>
                
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('produtos.update', $produto->id_produto) }}" enctype="multipart/form-data" id="editProductForm">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="product-image-container mb-4">
                                        @php
                                            $mainImage = $produto->imagens->where('principal', true)->first() ?? $produto->imagens->first();
                                        @endphp
                                        
                                        <div class="image-preview" id="imagePreview">
                                            @if($mainImage)
                                                <img src="{{ asset($mainImage->caminho) }}" alt="{{ $produto->nome_produto }}" id="productImage">
                                            @else
                                                <div class="empty-preview">
                                                    <i class="fas fa-image fa-3x mb-2"></i>
                                                    <span>Nenhuma imagem</span>
                                                </div>
                                            @endif
                                            
                                        </div>
                                        
                                        <div class="mt-3 d-flex justify-content-between">
                                            <button type="button" class="btn btn-outline-primary" id="alterarFotoBtn" onclick="document.getElementById('productImages').click()">
                                                <i class="bi bi-image"></i> Alterar Foto
                                            </button>
                                            <button type="button" class="btn btn-outline-danger" id="excluirFotoBtn" onclick="removeMainImage()">
                                                <i class="bi bi-trash"></i> Excluir
                                            </button>
                                        </div>
                                        
                                        <input type="file" id="productImages" name="imagens[]" accept="image/*" multiple style="display: none;" />
                                        <input type="hidden" name="removed_images" id="removedImages">
                                        <input type="hidden" name="main_image_id" id="mainImageId" value="{{ $mainImage->id ?? '' }}">
                                        
                                        
                                    </div>
                                    
                                    <div class="card mb-3">
                                        <div class="card-header bg-primary text-white">
                                            <i class="bi bi-star"></i> Avaliações
                                        </div>
                                        <div class="card-body">
                                            @foreach($produto->avaliacao as $avaliacao)
                                                <div class="avaliacao-item">
                                                    <div class="d-flex justify-content-between">
                                                        <strong>{{ $avaliacao->usuario->name }}</strong>
                                                        <div class="text-warning">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <i class="bi bi-star{{ $i <= $avaliacao->nota ? '-fill' : '' }}"></i>
                                                            @endfor
                                                        </div>
                                                    </div>
                                                    <p class="mb-0 mt-2">{{ $avaliacao->comentario }}</p>
                                                    <small class="text-muted">{{ $avaliacao->created_at->format('d/m/Y') }}</small>
                                                </div>
                                                @if(!$loop->last)
                                                    <hr>
                                                @endif
                                            @endforeach
                                            
                                            @if($produto->avaliacao->isEmpty())
                                                <p class="text-muted mb-0">Nenhuma avaliação ainda.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-8">
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <h3 class="product-name">
                                                <input type="text" class="form-control" name="nome" value="{{ old('nome', $produto->nome_produto) }}" required>
                                            </h3>
                                            <p class="text-muted">Código: {{ $produto->codigo }}</p>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label class="form-label">Tipo do produto</label>
                                            <select name="categorias[]" class="form-control categorias-select" multiple required>
                                                @foreach ($categorias as $categoria)
                                                    <option value="{{ $categoria->id_categoria }}"
                                                        {{ $produto->categorias->contains('id_categoria', $categoria->id_categoria) ? 'selected' : '' }}>
                                                        {{ $categoria->nome_categoria }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label class="form-label">Variação</label>
                                            <input type="text" class="form-control" name="variacao" value="{{ old('variacao', $produto->variacao) }}" required>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label class="form-label">Cor</label>
                                            <select name="cores[]" class="form-control cores-select" multiple required>
                                                @foreach ($cores as $cor)
                                                    <option value="{{ $cor->id_cor }}" data-hex="{{ $cor->codigo_hex }}"
                                                        {{ $produto->variacoes->contains('cor_id', $cor->id_cor) ? 'selected' : '' }}>
                                                        <span class="color-option" style="background-color: {{ $cor->codigo_hex }}"></span>
                                                        {{ $cor->nome }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label class="form-label">Marca</label>
                                            <input type="text" class="form-control" name="marca" value="{{ old('marca', $produto->marca) }}" required>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label class="form-label">Tamanho</label>
                                            <select name="tamanhos[]" class="form-control tamanhos-select" multiple required>
                                                @foreach ($tamanhos as $tamanho)
                                                    <option value="{{ $tamanho->id_tamanho }}"
                                                        {{ $produto->variacoes->contains('tamanho_id', $tamanho->id_tamanho) ? 'selected' : '' }}>
                                                        {{ $tamanho->nome }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label class="form-label">Tecido</label>
                                            <input type="text" class="form-control" name="tecido" value="{{ old('tecido', $produto->tecido) }}" required>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label class="form-label">Estação</label>
                                            <select name="estacao" class="form-control" required>
                                                <option value="Verão" {{ $produto->estacao == 'Verão' ? 'selected' : '' }}>Verão</option>
                                                <option value="Inverno" {{ $produto->estacao == 'Inverno' ? 'selected' : '' }}>Inverno</option>
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label class="form-label">Valor</label>
                                            <input type="number" step="0.01" class="form-control" name="valor" value="{{ old('valor', $produto->preco) }}" required>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label class="form-label">Estoque Total</label>
                                            <div class="d-flex align-items-center">
                                                <input type="number" class="form-control me-2" name="estoque" value="{{ old('estoque', $produto->variacoes->sum('estoque')) }}" required>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label class="form-label">Modelo</label>
                                            <input type="text" class="form-control" name="modelo" value="{{ old('modelo', $produto->modelo) }}" required>
                                        </div>
                                        
                                        <div class="col-md-12 mt-4">
                                            <label class="form-label">Descrição</label>
                                            <div class="card">
                                                <div class="card-body">
                                                    <textarea class="form-control" name="descricao" rows="3">{{ old('descricao', $produto->descricao) }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-12 mt-4">
                                            <label class="form-label">Histórico de Movimentação</label>
                                            <div class="table-responsive">
                                                <table class="table table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>Data</th>
                                                            <th>Tipo</th>
                                                            <th>Quantidade</th>
                                                            <th>Responsável</th>
                                                        </tr>
                                                    </thead>
                                                    
                                                </table>
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

    <!-- Modal para Adicionar Estoque -->
    <div class="modal fade" id="estoqueModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Adicionar ao Estoque</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEstoque">
                        <div class="mb-3">
                            <label for="quantidade" class="form-label">Quantidade</label>
                            <input type="number" class="form-control" id="quantidade" min="1" value="1" required>
                        </div>
                        <div class="mb-3">
                            <label for="observacao" class="form-label">Observação</label>
                            <textarea class="form-control" id="observacao" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="confirmarEstoqueBtn">Confirmar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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

        // Variáveis globais para controle de imagens
        let removedImages = [];
        let newImages = [];

        // Função para trocar a imagem principal
        function changeMainImage(thumbnail, imageId) {
            const newSrc = thumbnail.src;
            
            // Atualiza a imagem principal
            document.getElementById('productImage').src = newSrc;
            
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
                    document.getElementById('productImage').src = '';
                    document.getElementById('imagePreview').innerHTML = `
                        <div class="empty-preview">
                            <i class="fas fa-image fa-3x mb-2"></i>
                            <span>Nenhuma imagem</span>
                        </div>
                    `;
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

        // Função para remover a imagem principal
        function removeMainImage() {
            const mainImage = document.getElementById('productImage');
            const mainImageId = document.getElementById('mainImageId').value;
            
            if (mainImageId) {
                removedImages.push(mainImageId);
                document.getElementById('removedImages').value = removedImages.join(',');
            }
            
            document.getElementById('mainImageId').value = '';
            document.getElementById('imagePreview').innerHTML = `
                <div class="empty-preview">
                    <i class="fas fa-image fa-3x mb-2"></i>
                    <span>Nenhuma imagem</span>
                </div>
            `;
            
            // Remove a classe 'active' de todas as miniaturas
            document.querySelectorAll('.thumbnail').forEach(thumb => {
                thumb.classList.remove('active');
            });
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
                        const imagePreview = document.getElementById('imagePreview');
                        imagePreview.innerHTML = `<img src="${e.target.result}" id="productImage">`;
                        document.getElementById('mainImageId').value = 'new-0';
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
                        document.getElementById('imagePreview').innerHTML = `
                            <div class="empty-preview">
                                <i class="fas fa-image fa-3x mb-2"></i>
                                <span>Nenhuma imagem</span>
                            </div>
                        `;
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