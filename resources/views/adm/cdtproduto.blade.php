<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" >
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cantinho da Isa - Cadastro de Produtos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset("css/adm/cadastro de produtos.css") }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Opcional: ajuste para que o select2 ocupe 100% da largura do container */
        .select2-container--default .select2-selection--multiple {
            min-height: 40px;
        }
    </style>
</head>

<body>
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
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('produtos.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="product-image-container mb-4">
                                        <div class="image-preview" id="imagePreview">
                                            <div class="image-preview" onclick="document.getElementById('productImages').click()">
                                                <i class="fas fa-camera"></i>
                                                <span>Clique para adicionar imagens</span>
                                            </div>
                                        </div>
                                        <input type="file" id="productImages" name="imagens[]" accept="image/*" multiple style="display: none;" required />
                                        <input type="hidden" name="main_image" id="mainImageId" value="">
                                        <button type="button" class="btn btn-primary w-100 mt-2" onclick="document.getElementById('productImages').click()">
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
                                </div>

                                <div class="col-md-8">
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <label for="nome" class="form-label">Nome</label>
                                            <input type="text" class="form-control" name="nome" id="nome" placeholder="Digite o nome do produto" required />
                                        </div>

                                        <div class="col-md-6">
                                            <label for="categoria" class="form-label">Tipo do Produto</label>
                                            <select name="categorias[]" class="form-control categorias-select" multiple required>
                                                <option value="" disabled>Selecione as categorias</option>
                                                @foreach ($categorias as $categoria)
                                                    <option value="{{ $categoria->id_categoria }}">{{ $categoria->nome_categoria }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="descricao" class="form-label">Descrição</label>
                                            <input type="text" class="form-control" name="descricao" id="descricao" placeholder="Ex: Estilo, Tamanho" required />
                                        </div>

                                        <div class="col-md-6">
                                            <label for="cor" class="form-label">Cor</label>
                                            <select name="cores[]" id="cor" multiple required class="form-control cores-select">
                                                <option value="" disabled>Selecione as cores</option>
                                                @foreach ($cores as $cor)
                                                    <option value="{{ $cor->id_cor }}" data-hex="{{ $cor->codigo_hex }}">
                                                        <span style="background-color: {{ $cor->codigo_hex }}; display: inline-block; width: 15px; height: 15px;"></span>
                                                        {{ $cor->nome }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="estacao" class="form-label">Estação</label>
                                            <select name="estacao" id="estacao" class="form-select" required>
                                                <option value="" disabled selected>Selecione uma estação</option>
                                                <option value="Verão">Verão</option>
                                                <option value="Inverno">Inverno</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="marca" class="form-label">Marca</label>
                                            <input type="text" class="form-control" name="marca" id="marca" placeholder="Ex: Nike, Zara" required />
                                        </div>

                                        <div class="col-md-6">
                                            <label for="valor" class="form-label">Valor (R$)</label>
                                            <input type="number" step="0.01" class="form-control" name="valor" id="valor" placeholder="Ex: 59.90" required />
                                        </div>

                                        <div class="col-md-6">
                                            <label for="tamanho" class="form-label">Tamanho</label>
                                            <select name="tamanhos[]" id="tamanho" multiple required class="form-control tamanhos-select">
                                                <option value="" disabled>Selecione os tamanhos</option>
                                                @foreach ($tamanhos as $tamanho)
                                                    <option value="{{ $tamanho->id_tamanho }}">{{ $tamanho->nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="estoque" class="form-label">Estoque</label>
                                            <input type="number" class="form-control" name="estoque" id="estoque" placeholder="Quantidade" required />
                                        </div>

                                        <div class="col-md-6">
                                            <label for="tecido" class="form-label">Tecido</label>
                                            <input type="text" class="form-control" name="tecido" id="tecido" placeholder="Ex: Algodão, Jeans" required />
                                        </div>

                                        <div class="col-md-6">
                                            <label for="modelo" class="form-label">Modelo</label>
                                            <input type="text" class="form-control" name="modelo" id="modelo" required />
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
                width: '100%',
                templateResult: formatColor,
                templateSelection: formatColor,
                escapeMarkup: function (m) { return m; }
            });

            function formatColor(option) {
                if (!option.id) return option.text;
                var hex = $(option.element).data('hex');
                if (hex) {
                    return $('<span><span style="background-color:' + hex + '; width:15px; height:15px; display:inline-block; margin-right:5px; border: 1px solid #ccc;"></span> ' + option.text + '</span>');
                }
                return option.text;
            }

            $('.tamanhos-select').select2({
                placeholder: "Selecione os tamanhos",
                allowClear: true,
                width: '100%'
            });
        });

        // Script de upload de imagens (adaptado para a estrutura da Tela 2)
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

                // Limpa os containers existentes para evitar duplicação em novas seleções
                const mainContainer = imagePreview.querySelector('.main-image-container');
                const secondaryContainer = imagePreview.querySelector('.secondary-images-container');
                if (mainContainer) mainContainer.remove();
                if (secondaryContainer) secondaryContainer.remove();

                const newMainContainer = document.createElement('div');
                newMainContainer.className = 'main-image-container mb-2'; // Adicionado margin-bottom
                imagePreview.appendChild(newMainContainer);

                const newSecondaryContainer = document.createElement('div');
                newSecondaryContainer.className = 'secondary-images-container d-flex flex-wrap gap-2'; // Usando flexbox para miniaturas
                imagePreview.appendChild(newSecondaryContainer);

                // Processa cada arquivo selecionado
                Array.from(this.files).forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        if (index === 0) {
                            // Define a primeira imagem como principal
                            newMainContainer.innerHTML = `<img src="${e.target.result}" id="mainProductImage" alt="Imagem principal" class="img-fluid" />`;
                            mainImageId = `new-${index}`;
                            document.getElementById('mainImageId').value = mainImageId;
                        } else {
                            // Adiciona às miniaturas secundárias
                            const previewItem = document.createElement('div');
                            previewItem.className = 'image-preview-item position-relative'; // Adicionado position-relative para posicionamento dos botões
                            previewItem.innerHTML = `
                                <img src="${e.target.result}" alt="Pré-visualização" class="img-thumbnail" />
                                <div class="image-actions position-absolute top-0 end-0 p-1">
                                    <button type="button" class="btn btn-sm btn-light me-1" onclick="setAsMain(this, 'new-${index}')" title="Marcar como principal">
                                        <i class="fas fa-star"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="removeNewImage(this)" title="Remover imagem">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            `;
                            newSecondaryContainer.appendChild(previewItem);
                        }
                    };
                    reader.readAsDataURL(file);
                });
            } else {
                // Se nenhum arquivo for selecionado, restaura o placeholder
                imagePreview.innerHTML = `
                    <div class="empty-preview" onclick="document.getElementById('productImages').click()">
                        <i class="fas fa-camera"></i>
                        <span>Clique para adicionar imagens</span>
                    </div>
                `;
                document.getElementById('mainImageId').value = '';
            }
        });

        function setAsMain(button, imageId) {
            const previewItem = button.closest('.image-preview-item');
            const imgSrc = previewItem.querySelector('img').src;

            // Pega a imagem principal atual e a move para as secundárias (se houver)
            const currentMainImageContainer = imagePreview.querySelector('.main-image-container');
            const currentMainImage = currentMainImageContainer ? currentMainImageContainer.querySelector('img') : null;
            if (currentMainImage) {
                const secondaryContainer = imagePreview.querySelector('.secondary-images-container');
                const oldMainPreviewItem = document.createElement('div');
                oldMainPreviewItem.className = 'image-preview-item position-relative';
                oldMainPreviewItem.innerHTML = `
                    <img src="${currentMainImage.src}" alt="Pré-visualização" class="img-thumbnail" />
                    <div class="image-actions position-absolute top-0 end-0 p-1">
                        <button type="button" class="btn btn-sm btn-light me-1" onclick="setAsMain(this, '${mainImageId}')" title="Marcar como principal">
                            <i class="fas fa-star"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger" onclick="removeNewImage(this)" title="Remover imagem">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `;
                secondaryContainer.appendChild(oldMainPreviewItem);
            }


            // Atualiza a imagem principal
            imagePreview.querySelector('.main-image-container').innerHTML = `<img src="${imgSrc}" id="mainProductImage" alt="Imagem principal" class="img-fluid" />`;

            // Atualiza o ID da imagem principal
            mainImageId = imageId;
            document.getElementById('mainImageId').value = mainImageId;

            // Remove a imagem da lista secundária
            previewItem.remove();

            // Se não houver mais imagens secundárias e a principal foi movida para lá, reexibe o placeholder
            const hasSecondaryImages = imagePreview.querySelector('.secondary-images-container').children.length > 0;
            if (!imagePreview.querySelector('.main-image-container img') && !hasSecondaryImages) {
                 imagePreview.innerHTML = `
                    <div class="empty-preview" onclick="document.getElementById('productImages').click()">
                        <i class="fas fa-camera"></i>
                        <span>Clique para adicionar imagens</span>
                    </div>
                `;
                document.getElementById('mainImageId').value = '';
            }
        }

        function removeNewImage(button) {
            const previewItem = button.closest('.image-preview-item');
            previewItem.remove();

            // Se a imagem principal foi removida, define a primeira secundária como principal ou mostra o placeholder
            const mainImgElement = imagePreview.querySelector('.main-image-container img');
            const secondaryImagesContainer = imagePreview.querySelector('.secondary-images-container');

            if (!mainImgElement && secondaryImagesContainer.children.length > 0) {
                const firstSecondaryImage = secondaryImagesContainer.querySelector('.image-preview-item');
                const imgSrc = firstSecondaryImage.querySelector('img').src;
                imagePreview.querySelector('.main-image-container').innerHTML = `<img src="${imgSrc}" id="mainProductImage" alt="Imagem principal" class="img-fluid" />`;
                mainImageId = `new-${Array.from(secondaryImagesContainer.children).indexOf(firstSecondaryImage)}`; // Atualiza o ID
                document.getElementById('mainImageId').value = mainImageId;
                firstSecondaryImage.remove();
            } else if (!mainImgElement && secondaryImagesContainer.children.length === 0) {
                // Se não houver mais imagens, mostra o placeholder
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
</body>

</html>