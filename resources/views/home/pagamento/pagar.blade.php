<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento PIX - Cantinho da Isa</title>
    {{-- ADICIONE ESTA LINHA PARA O CSRF TOKEN --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .pix-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 30px;
            border-radius: 10px;
            background-color: #f8f9fa;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .qr-code-wrapper {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            border: 1px solid #dee2e6;
        }

        .qr-code-img {
            max-width: 250px;
            height: auto;
            margin: 0 auto 15px;
        }

        .pix-key-container {
            position: relative;
            margin-bottom: 20px;
        }

        .copy-btn {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
        }

        .timer {
            font-size: 1.2rem;
            font-weight: bold;
            color: #dc3545;
            margin-bottom: 20px;
        }

        .steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            position: relative;
        }

        .step {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #0d6efd;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 2;
        }

        .step.active {
            background-color: #0d6efd;
        }

        .step.completed {
            background-color: #198754;
        }

        .step-line {
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 2px;
            background-color: #dee2e6;
            z-index: 1;
        }

        .step-line-progress {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            background-color: #198754;
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="pix-container">
            <h2 class="text-center mb-4">Pagamento via PIX</h2>

            <div class="steps">
                <div class="step completed">
                    <i class="fas fa-check"></i>
                </div>
                <div class="step active">
                    2
                </div>
                <div class="step">
                    3
                </div>
                <div class="step-line">
                    <div class="step-line-progress" style="width: 33%;"></div>
                </div>
            </div>

            <div class="text-center mb-4">
                <h4>Pedido #{{ $pedido->id_pedido }}</h4>
                <h5 class="text-success">R$ {{ number_format($total, 2, ',', '.') }}</h5>
            </div>

            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Você tem <strong>30 minutos</strong> para realizar o pagamento.
            </div>

            <div class="qr-code-wrapper">
                <h5 class="mb-3">Escaneie o QR Code</h5>
                @if($qrCodeData)
                    <img src="{{ $qrCodeData }}" alt="QR Code PIX" class="img-fluid qr-code-img">
                    <p class="text-muted">Abra o app do seu banco e escaneie o código acima</p>
                @else
                    <div class="alert alert-danger">
                        Não foi possível gerar o QR Code. Por favor, tente novamente.
                    </div>
                @endif
            </div>

            <div class="mb-4">
                <h5 class="mb-3">Ou copie o código PIX</h5>
                <div class="pix-key-container">
                    <input type="text" id="pixKey" class="form-control" value="{{ $pixKey ?? 'Não disponível' }}"
                        readonly>
                    <button class="btn btn-outline-secondary copy-btn" onclick="copyPixKey()">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
                <small class="text-muted">Cole este código no app do seu banco para pagar</small>
            </div>

            <div class="timer text-center mb-4">
                <i class="fas fa-clock me-2"></i>
                <span id="countdown">Carregando tempo...</span>
            </div>

            <div class="d-grid gap-2">
                <button class="btn btn-success" onclick="confirmPayment()">
                    <i class="fas fa-check-circle me-2"></i> Já efetuei o pagamento
                </button>
                <a href="{{ route('home.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Voltar
                </a>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const expirationDateTimeString = "{{ $expirationDate ?? '' }}"; // Use '?? '' para evitar erro se não existir


        function copyPixKey() {
            const pixKey = document.getElementById('pixKey');
            pixKey.select();
            pixKey.setSelectionRange(0, 99999);
            document.execCommand('copy');

            // Feedback visual
            const copyBtn = document.querySelector('.copy-btn');
            copyBtn.innerHTML = '<i class="fas fa-check"></i>';
            setTimeout(() => {
                copyBtn.innerHTML = '<i class="fas fa-copy"></i>';
            }, 2000);
        }

        // Contador regressivo de 30 minutos
        function startCountdown() {
            if (!expirationDateTimeString) {
                document.getElementById('countdown').textContent = "Tempo indisponível.";
                console.error("Data de expiração do PIX não fornecida.");
                return;
            }

            // Parse a data de expiração para um objeto Date
            const expirationTime = new Date(expirationDateTimeString);

            const countdownInterval = setInterval(() => {
                const now = new Date();
                const timeLeft = expirationTime.getTime() - now.getTime(); // Tempo restante em milissegundos

                if (timeLeft <= 0) {
                    clearInterval(countdownInterval);
                    document.getElementById('countdown').textContent = "Tempo esgotado!";
                    alert('Tempo esgotado! Por favor, inicie um novo pedido para gerar um novo PIX.');
                    window.location.href = '/carrinho'; // Redirecionar para o carrinho ou outra página
                    return;
                }

                const totalSeconds = Math.floor(timeLeft / 1000);
                const minutes = Math.floor(totalSeconds / 60);
                const seconds = totalSeconds % 60;

                document.getElementById('countdown').textContent =
                    `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            }, 1000); // Atualiza a cada segundo
        }

        function confirmPayment() {
            if (confirm('Você já efetuou o pagamento deste pedido via PIX?')) {
                const pedidoId = {{ $pedido->id_pedido }}; // Correção da sintaxe, se estiver dentro do Blade

                $.ajax({
                    url: '{{ route("pagamento.confirmar", ["pedidoId" => ":pedidoId"]) }}'.replace(':pedidoId', pedidoId),
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.success) {
                            window.location.href = '{{ route("pagamento.sucesso", ["pedido" => ":pedidoId"]) }}'.replace(':pedidoId', pedidoId);
                        } else {
                            alert(response.message || 'Erro ao confirmar pagamento');
                        }
                    },
                    error: function (xhr) {
                        alert('Erro ao comunicar com o servidor: ' + xhr.statusText);
                    }
                });
            }
        }

        // Iniciar contador quando a página carregar
        $(document).ready(function () {
            startCountdown();
        });
    </script>
</body>

</html>