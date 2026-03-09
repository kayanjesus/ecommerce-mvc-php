<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Endereço - Cantinho da Isa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pagamento/editar-endereco.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <!-- Cabeçalho -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home.index') }}">
                <img src="{{ asset('img/logo/ft_logo.webp') }}" alt="Cantinho da Isa">
            </a>
        </div>
    </nav>

    <!-- Progresso -->
    <div class="etapas">
        <div class="etapa etapa-ativa"></div>
        <div class="etapa {{ session()->has('forma_pagamento') ? 'etapa-ativa' : '' }}"></div>
        <div class="etapa"></div>
    </div>

    <!-- Barra de Informações -->
    <div class="barra-info">
        Frete Grátis - Sul e Sudeste a partir de R$250, demais regiões a partir de R$399
    </div>

    <!-- Conteúdo Principal -->
    <div class="container py-4">
        <div class="row">
<div class="col-lg-6 mb-4">
    <div class="produto-container">
        @if(!empty($itens))
            @foreach($itens as $key => $itemArray)
                @php
                    $cartItem = \Cart::get($key);
                @endphp
                
                @if($cartItem)
                    @php
                        $cor = isset($itemArray['attributes']['cor_id'])
                            ? App\Models\Cor::find($itemArray['attributes']['cor_id'])
                            : null;
                            
                        $tamanho = isset($itemArray['attributes']['tamanho_id'])
                            ? App\Models\Tamanho::find($itemArray['attributes']['tamanho_id'])
                            : null;
                    @endphp

                    <div class="d-flex mb-3">
                        @if(isset($itemArray['attributes']['image']))
                            <img src="{{ asset($itemArray['attributes']['image']) }}" 
                                 alt="{{ $cartItem->name }}" 
                                 class="me-3" 
                                 style="width: 80px; height: 80px; object-fit: cover;">
                        @endif
                        
                        <div class="produto-info">
                            <p class="mb-1">
                                <strong>{{ $cartItem->name }}</strong><br>
                                @if($cor)
                                    Cor: {{ $cor->nome_cor }}<br>
                                @endif
                                @if($tamanho)
                                    Tamanho: {{ $tamanho->tamanho }}<br>
                                @endif
                                Quantidade: {{ $cartItem->quantity }}
                            </p>
                            <p class="mb-0">R$ {{ number_format($cartItem->price * $cartItem->quantity, 2, ',', '.') }}</p>
                        </div>
                    </div>
                @endif
            @endforeach
        @else
            <p class="text-muted">Nenhum item no carrinho</p>
        @endif
        
        <div class="total">Total: R$ {{ number_format($total, 2, ',', '.') }}</div>
    </div>
</div>

            <!-- Formulário de Edição -->
            <div class="col-lg-6">
                <div class="form-container">
                    <h5 class="form-title"><i class="fas fa-edit me-2"></i>Editar Endereço</h5>
                    
                    <form action="{{ route('pagamento.atualizar-endereco') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="cep">CEP</label>
                            <input type="text" class="form-control" name="cep" id="cep" placeholder="00000-000" 
                                   value="{{ $endereco['cep'] }}" required>
                            <small class="text-muted">Digite seu CEP para autopreencher</small>
                            <div class="alert-cep invalid-feedback"></div>
                        </div>

                        <div class="form-group">
                            <label for="rua">Rua</label>
                            <input type="text" class="form-control" name="rua" id="rua" 
                                   value="{{ $endereco['rua'] }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-8 form-group">
                                <label for="bairro">Bairro</label>
                                <input type="text" class="form-control" name="bairro" id="bairro" 
                                       value="{{ $endereco['bairro'] }}" required>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="numero">Número</label>
                                <input type="text" class="form-control" name="numero" id="numero" 
                                       value="{{ $endereco['numero'] }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8 form-group">
                                <label for="cidade">Cidade</label>
                                <input type="text" class="form-control" name="cidade" id="cidade" 
                                       value="{{ $endereco['cidade'] }}" required>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="estado">Estado</label>
                                <input type="text" class="form-control" name="estado" id="estado" 
                                       value="{{ $endereco['estado'] }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="complemento">Complemento (Opcional)</label>
                            <input type="text" class="form-control" name="complemento" id="complemento"
                                   value="{{ $endereco['complemento'] ?? '' }}">
                        </div>

                        <div class="d-grid gap-2 mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Salvar Alterações
                            </button>
                            <a href="{{ session()->has('forma_pagamento') ? route('pagamento.revisao') : route('pagamento.forma-pagamento') }}" 
                               class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Mesmo script de busca de CEP da página anterior -->
    <script>
    $(document).ready(function() {
        // Formatação automática do CEP
        $('#cep').on('input', function() {
            let value = $(this).val().replace(/\D/g, '');
            if (value.length > 5) {
                value = value.substring(0, 5) + '-' + value.substring(5, 8);
            }
            $(this).val(value.substring(0, 9));
        });

        // Busca CEP via API
        $('#cep').on('blur', function() {
            const cep = $(this).val().replace(/\D/g, '');
            const $feedback = $('.alert-cep');
            
            if (cep.length !== 8) {
                $feedback.text('CEP deve conter 8 dígitos').show();
                return;
            }

            $(this).prop('disabled', true);
            $feedback.hide().removeClass('text-danger text-success');
            
            // Adiciona spinner
            const $spinner = $('<span class="spinner-border spinner-border-sm ms-2" role="status"></span>');
            $(this).after($spinner);

            $.ajax({
                url: "{{ route('pagamento.buscar-cep') }}",
                method: 'GET',
                data: { cep: cep },
                dataType: 'json',
                success: function(response) {
                    if (response.cep_data) {
                        $('#rua').val(response.cep_data.rua);
                        $('#bairro').val(response.cep_data.bairro);
                        $('#cidade').val(response.cep_data.cidade);
                        $('#estado').val(response.cep_data.estado);
                        $('#numero').focus();
                        $feedback.text('CEP encontrado!').addClass('text-success').show();
                    } else {
                        $feedback.text(response.error || 'CEP não encontrado').addClass('text-danger').show();
                    }
                },
                error: function(xhr) {
                    const msg = xhr.responseJSON?.error || 'Erro ao buscar CEP';
                    $feedback.text(msg).addClass('text-danger').show();
                },
                complete: function() {
                    $('#cep').prop('disabled', false);
                    $spinner.remove();
                }
            });
        });
    });
    </script>
</body>
</html>