<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido Confirmado - Moda Kids</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pagamento.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Estilos adicionais para o QR Code */
        .pix-qr-code-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            background-color: #f9f9f9;
            margin-top: 20px;
        }

        .pix-qr-code-container img {
            max-width: 250px;
            height: auto;
            border: 1px solid #ccc;
            padding: 5px;
            border-radius: 5px;
        }

        .pix-key-display {
            width: 100%;
            max-width: 350px;
            /* Limita a largura da caixa de texto do Pix Copia e Cola */
            margin-top: 15px;
        }
    </style>
</head>

<body>

    @if(!isset($pedido) || empty($pedido))
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            Não foi possível carregar todos os detalhes do pedido.
            Você pode verificar o status na página de acompanhamento.
        </div>
    @endif


    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home.index') }}">
                <img src="{{ asset('img/logo/ft_logo.png') }}" alt="Moda Kids">
            </a>
        </div>
    </nav>

    <div class="etapas">
        <div class="etapa etapa-ativa"></div>
        <div class="etapa etapa-ativa"></div>
        <div class="etapa etapa-ativa"></div>
    </div>

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
                                    <strong>Data:</strong> {{ $pedido['data_pedido'] ?? now()->format('d/m/Y H:i:s') }}
                                </li>
                                <li class="list-group-item">
                                    <strong>Total:</strong> R$ {{ number_format($totalFinal, 2, ',', '.') }}
                                </li>
                                <li class="list-group-item">
                                    <strong>Método de Pagamento:</strong>
                                    @if($formaPagamento == 'pix')
                                        Pix (5% de desconto)
                                    @elseif($formaPagamento == 'cartao')
                                        Cartão de Crédito
                                    @else
                                        Boleto Bancário
                                    @endif
                                </li>
                            </ul>
                        </div>

                        @if($formaPagamento == 'pix' && isset($qrCodeData))
                            <div class="pix-payment mb-4 mt-4">
                                <h5 class="mb-3 text-center"><i class="fas fa-qrcode me-2"></i>Pague com Pix</h5>
                                <p class="text-center">Escaneie o QR Code abaixo com o aplicativo do seu banco para pagar:
                                </p>
                                <div class="pix-qr-code-container">
                                    <img src="{{ $qrCodeData }}" alt="QR Code Pix" class="img-fluid mb-3">
                                    <div class="input-group pix-key-display">
                                        <input type="text" id="pixKeyInput" class="form-control text-center"
                                            value="{{ $pixKey ?? 'N/A' }}" readonly>
                                        <button class="btn btn-outline-secondary" type="button" id="copyPix">
                                            <i class="fas fa-copy"></i> Copiar Código Pix
                                        </button>
                                    </div>
                                    <p class="text-muted small mt-2">O pedido será processado após a confirmação do
                                        pagamento.</p>
                                </div>
                            </div>
                        @elseif($formaPagamento == 'pix' && !isset($qrCodeData))
                            <div class="alert alert-danger mt-4">
                                Não foi possível gerar o QR Code Pix. Por favor, tente novamente ou entre em contato com o
                                suporte.
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
        document.getElementById('copyPix')?.addEventListener('click', function () {
            const pixKeyInput = document.getElementById('pixKeyInput');
            if (pixKeyInput) {
                pixKeyInput.select();
                pixKeyInput.setSelectionRange(0, 99999); // For mobile devices
                document.execCommand("copy");

                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-check"></i> Copiado!';
                setTimeout(() => {
                    this.innerHTML = originalText;
                }, 2000);
            }
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