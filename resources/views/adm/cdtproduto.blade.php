<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cantinho da Isa\Cadastro de Produtos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/cadastro de produtos.css') }}">
</head>
<body>
    <div class="container">

        
        <form>
            <div class="product-main-info">
                <div class="product-image-container">
                    <div class="image-preview" id="imagePreview">
                        <i class="fas fa-camera"></i>
                        <span>Clique para adicionar imagem</span>
                    </div>
                    <input type="file" id="productImage" accept="image/*" style="display: none;">
                    <button type="button" class="btn-image-upload" onclick="document.getElementById('productImage').click()">
                        <i class="fas fa-upload"></i> Carregar Imagem
                    </button>
                </div>
                
                <div class="product-basic-info">
                    <div class="form-section">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="product-type">Tipo do produto</label>
                                <select id="product-type" required>
                                    <option value="">Selecione...</option>
                                    <option value="vestido">Vestido</option>
                                    <option value="macacao">Macacão</option>
                                    <option value="blusa">Blusa</option>
                                    <option value="calca">Calça</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="variation">Variação</label>
                                <input type="text" id="variation" placeholder="Ex: Com estampa, básico">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="color">Cor</label>
                                <input type="text" id="color" required>
                            </div>
                            <div class="form-group">
                                <label for="brand">Marca</label>
                                <input type="text" id="brand" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-section">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="size">Tamanho</label>
                                <input type="text" id="size" placeholder="Ex: P, M, G ou números" required>
                            </div>
                            <div class="form-group">
                                <label for="fabric">Tecido</label>
                                <input type="text" id="fabric" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="season">Estação</label>
                                <select id="season" required>
                                    <option value="">Selecione...</option>
                                    <option value="verao">Verão</option>
                                    <option value="inverno">Inverno</option>
                                    <option value="primavera">Primavera</option>
                                    <option value="outono">Outono</option>
                                    <option value="todas">Todas as estações</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="price">Valor (R$)</label>
                                <input type="number" id="price" step="0.01" min="0" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="stock">Estoque</label>
                                <input type="number" id="stock" min="0" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="buttons">
                <button type="submit" class="btn-primary">Adicionar</button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('productImage').addEventListener('change', function(e) {
            const preview = document.getElementById('imagePreview');
            const file = e.target.files[0];
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.innerHTML = '';
                const img = document.createElement('img');
                img.src = e.target.result;
                preview.appendChild(img);
            }
            
            if (file) {
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>