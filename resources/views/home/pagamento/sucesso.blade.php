<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido Confirmado - Cantinho da Isa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pagamento.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>

    @if(!isset($pedido) || empty($pedido))
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            Não foi possível carregar todos os detalhes do pedido.
            Você pode verificar o status na página de acompanhamento.
        </div>
    @endif


    <!-- Cabeçalho -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home.index') }}">
                <img src="{{ asset('img/logo/ft_logo.png') }}" alt="Cantinho da Isa">
            </a>
        </div>
    </nav>

    <!-- Progresso -->
    <div class="etapas">
        <div class="etapa etapa-ativa"></div>
        <div class="etapa etapa-ativa"></div>
        <div class="etapa etapa-ativa"></div>
    </div>

    <!-- Conteúdo Principal -->
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-success pedido-sucesso">
                    <div class="card-header text-white">
                        <h4 class="mb-0"><i class="fas fa-check-circle me-2"></i>Pedido Confirmado!</h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i> Seu pedido foi recebido com sucesso!
                        </div>

                        <h5 class="mb-3"><i class="fas fa-receipt me-2"></i>Detalhes do Pedido</h5>
                        <div class="card pedido-detalhes">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <strong>Número do Pedido:</strong> #{{ $pedido['id_pedido'] }}
                                </li>
                                <li class="list-group-item">
                                    <strong>Data:</strong> {{ $pedido['data'] ?? now()->format('d/m/Y H:i:s') }}
                                </li>
                                <li class="list-group-item">
                                    <strong>Total:</strong> R$ {{ number_format($pedido['total'], 2, ',', '.') }}
                                </li>
                                <li class="list-group-item">
                                    <strong>Método de Pagamento:</strong>
                                    @if($pedido['metodo_pagamento'] == 'pix')
                                        Pix (5% de desconto)
                                    @elseif($pedido['metodo_pagamento'] == 'cartao')
                                        Cartão de Crédito
                                    @else
                                        Boleto Bancário
                                    @endif
                                </li>
                            </ul>
                        </div>

                        @if($pedido['metodo_pagamento'] == 'pix')
                            <div class="pix-payment mb-4 mt-4">
                                <h5 class="mb-3"><i class="fas fa-qrcode me-2"></i>Pagamento via Pix</h5>
                                <div class="card">
                                    <div class="card-body text-center">
                                        <img src="{{ asset('img/pix-qrcode-placeholder.jpg') }}" alt="QR Code Pix"
                                            class="img-fluid mb-3" style="max-width: 200px;">
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control text-center"
                                                value="ilustrativo@cantinhodaisa.com.br" readonly>
                                            <button class="btn btn-outline-secondary" type="button" id="copyPix">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                        <p class="mb-1"><strong>Valor:</strong> R$
                                            {{ number_format($pedido['total'], 2, ',', '.') }}
                                        </p>
                                        <p class="text-muted small">O pedido será processado após a confirmação do
                                            pagamento.</p>

                                        <!-- Botão de confirmação de pagamento fictício -->
                                        <button id="confirmar-pagamento" class="btn btn-success mt-3">
                                            <i class="fas fa-check-circle me-2"></i> Confirmar Pagamento (Simulação)
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="d-grid gap-2 mt-4">
                            <a href="{{ route('home.index') }}" class="btn btn-success">
                                <i class="fas fa-home me-2"></i> Voltar à Loja
                            </a>
                            <a href="{{ route('home.dashboard') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-list me-2"></i> Acompanhar Pedidos
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById('confirmar-pagamento')?.addEventListener('click', function () {
            const btn = this;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Confirmando...';

            fetch('{{ route("pagamento.confirmar-ficticio") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    pedido_id: '{{ $pedido["id_pedido"] }}'
                })
            })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw new Error(err.message || 'Erro na resposta do servidor');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Mostrar mensagem de sucesso
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-success position-fixed top-0 start-50 translate-middle-x mt-3';
                        alertDiv.style.zIndex = '9999';
                        alertDiv.innerHTML = `
                <i class="fas fa-check-circle me-2"></i>
                ${data.message}
                <button type="button" class="btn-close float-end" data-bs-dismiss="alert"></button>
            `;
                        document.body.appendChild(alertDiv);

                        setTimeout(() => alertDiv.remove(), 5000);
                        btn.style.display = 'none';
                    } else {
                        throw new Error(data.message || 'Erro ao confirmar pagamento');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'alert alert-danger position-fixed top-0 start-50 translate-middle-x mt-3';
                    errorDiv.style.zIndex = '9999';
                    errorDiv.innerHTML = `
            <i class="fas fa-exclamation-circle me-2"></i>
            ${error.message}
            <button type="button" class="btn-close float-end" data-bs-dismiss="alert"></button>
        `;
                    document.body.appendChild(errorDiv);

                    setTimeout(() => errorDiv.remove(), 5000);
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-check-circle me-2"></i> Confirmar Pagamento (Simulação)';
                });
        });
    </script>










    <script>
        document.getElementById('confirmar-pagamento')?.addEventListener('click', function () {
            const btn = this;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Confirmando...';

            fetch('{{ route("pagamento.confirmar-ficticio") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    pedido_id: '{{ $pedido["id_pedido"] }}'
                })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erro na resposta do servidor');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Mostrar mensagem de sucesso
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-success position-fixed top-0 start-50 translate-middle-x mt-3';
                        alertDiv.style.zIndex = '9999';
                        alertDiv.innerHTML = `
                <i class="fas fa-check-circle me-2"></i>
                Pagamento confirmado com sucesso! O pedido foi enviado para processamento.
                <button type="button" class="btn-close float-end" data-bs-dismiss="alert"></button>
            `;
                        document.body.appendChild(alertDiv);

                        setTimeout(() => alertDiv.remove(), 5000);
                        btn.style.display = 'none';
                    } else {
                        throw new Error(data.message || 'Erro ao confirmar pagamento');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'alert alert-danger position-fixed top-0 start-50 translate-middle-x mt-3';
                    errorDiv.style.zIndex = '9999';
                    errorDiv.innerHTML = `
            <i class="fas fa-exclamation-circle me-2"></i>
            ${error.message}
            <button type="button" class="btn-close float-end" data-bs-dismiss="alert"></button>
        `;
                    document.body.appendChild(errorDiv);

                    setTimeout(() => errorDiv.remove(), 5000);
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-check-circle me-2"></i> Confirmar Pagamento (Simulação)';
                });
        });
    </script>

    <script>
        $(document).ready(function () {
            // Copiar chave Pix
            $('#copyPix').click(function () {
                const pixKey = $('.input-group input').val();
                navigator.clipboard.writeText(pixKey).then(function () {
                    const originalText = $(this).html();
                    $(this).html('<i class="fas fa-check"></i> Copiado!');
                    setTimeout(() => {
                        $(this).html(originalText);
                    }, 2000);
                });
            });
        });
    </script>

    <script>
        // Substitua o alert genérico por algo mais amigável
        function showError(message) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'alert alert-danger position-fixed top-0 start-50 translate-middle-x mt-3';
            errorDiv.style.zIndex = '9999';
            errorDiv.innerHTML = `
        <i class="fas fa-exclamation-circle me-2"></i>
        ${message}
        <button type="button" class="btn-close float-end" data-bs-dismiss="alert"></button>
    `;
            document.body.appendChild(errorDiv);

            // Remove após 5 segundos
            setTimeout(() => errorDiv.remove(), 5000);
        }
    </script>
</body>

</html>