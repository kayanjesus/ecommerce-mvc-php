<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Confirmação de Compra</title>
    {{-- Importar a fonte Inter do Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/pagamento-confirmacao.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>
    <header class="topo">
        <div class="logo">
            <a href="{{ route('home.index') }}">
                <img src="{{ asset('img/logo/ft_logo.png') }}" alt="Cantinho da Isa - Logo" class="logo-img">
            </a>
        </div>
        <div class="barra-progresso">
            <div class="progress-line"></div>
            <div class="etapa ativo" data-step="1"> <span class="texto-etapa">Carrinho</span>
                <div class="bolinha"></div>
            </div>
            <div class="etapa ativo" data-step="2"> <span class="texto-etapa">Pagamento</span>
                <div class="bolinha"></div>
            </div>
            <div class="etapa ativo" data-step="3"> <span class="texto-etapa">Confirmação</span>
                <div class="bolinha"></div>
            </div>
        </div>
    </header>

    <main class="confirmacao-container">
        <!-- Substitua TODO o conteúdo dentro de confirmacao-box por este código: -->

        <div class="confirmacao-box">
            <!-- COLUNA 1: Sucesso e ID do Pedido -->
            <div class="sucesso-header">
                <i class="fas fa-check-circle sucesso-icon"></i>
                <p class="mensagem">Compra realizada!</p>
                @if(isset($pedido))
                    <div class="pedido-id-card">
                        <span class="pedido-id-label">Pedido</span>
                        <span class="pedido-id-valor">#{{ $pedido->id_pedido }}</span>
                    </div>
                @endif
            </div>

            <!-- COLUNA 2: QR Code e PIX -->
            <div class="qr-pix-container">
                @if(isset($qrCodeData))
                    <div class="qr-code-wrapper">
                        <h5 class="qr-code-titulo">QR Code PIX</h5>
                        <div class="qr-code-border">
                            <img src="{{ $qrCodeData }}" alt="QR Code PIX" class="qr-code-img">
                        </div>
                        <p class="qr-code-instrucao">Escaneie com o app do seu banco</p>
                    </div>
                @endif

                <div class="pix-section">
                    <h5 class="pix-titulo">Código PIX</h5>
                    <div class="pix-key-container">
                        <input type="text" id="pixKey" class="pix-input" value="{{ $pixKey ?? 'Não disponível' }}"
                            readonly>
                        <button class="btn-pix-copy" onclick="copyPixKey()">
                            <i class="fas fa-copy"></i> Copiar
                        </button>
                    </div>
                    <small class="pix-ajuda">Cole no app do banco</small>
                </div>
            </div>

            <!-- COLUNA 3: Status e Total -->
            <div class="status-total-container">
                <div class="timer-container">
                    <i class="fas fa-clock timer-icon"></i>
                    <span id="countdown" class="timer-text">30:00</span>
                </div>

                @if(isset($pedido))
                    <div class="status-card">
                        <div class="status-item">
                            <span class="status-label">Status</span>
                            <span class="status-badge">{{ ucfirst(str_replace('_', ' ', $pedido->status)) }}</span>
                        </div>
                        <div class="total-item">
                            <span class="total-label">Total</span>
                            <span class="total-valor">R$ {{ number_format($pedido->total, 2, ',', '.') }}</span>
                        </div>
                    </div>
                    <p class="detalhe-info">
                        <i class="fas fa-envelope me-1"></i>
                        Detalhes enviados por e-mail
                    </p>
                @endif
            </div>

            <!-- BOTÃO VOLTAR - OCUPA AS 3 COLUNAS -->
            <div class="voltar-container">
                <a href="{{ route('home.index') }}" class="botao-voltar">
                    <i class="fas fa-arrow-left"></i> Voltar à página inicial
                </a>
            </div>
        </div>
    </main>
    <footer>
        <section class="top-footer">
            <h3>Cantinho da Isa</h3>
            <p>Crianças crescem rápido, não é mesmo? Em pouco tempo, as roupinhas vão ficando mais curtas, e é preciso
                renovar os guarda-roupas. Aqui no Cantinho da Isa, temos o melhor vestuário para os pequenos, e com os
                menores preços. Venha conferir. </p>
        </section>
        <div class="footer-container">
            <div class="footer-column">
                <h3>Institucional</h3>
                <ul>
                    <li><a href="#">Quem Somos</a></li>
                    <li><a href="#">Política de Privacidade</a></li>
                    <li><a href="#">Troca e Devolução</a></li>
                    <li><a href="#">Política de Entrega</a></li>
                    <li><a href="#">Política de Pagamento</a></li>
                    <li><a href="#">Ajuda</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Atendimento</h3>
                <p>( xx ) xxxx-xxxx</p>
                <p>De segunda-feira a sexta-feira:<br>12h ás 18h</p>
            </div>
            <div class="footer-column">
                <h3>Compre Seguro</h3>
                <p>Suas compras são processadas com segurança através do <strong>PagSeguro</strong>, garantindo proteção
                    total de seus dados e tranquilidade nas transações.</p>
                <ul class="payment-methods">
                    <li><img src="{{ asset('img/pagseguro.png') }}" alt="PagSeguro"></li>
                    <li><img src="{{ asset('img/mastercard.png') }}" alt="Mastercard"></li>
                    <li><img src="{{ asset('img/pix.png') }}" alt="Pix"></li>
                </ul>
            </div>


        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                // Não precisamos fazer uma requisição AJAX para o backend aqui,
                // pois o webhook já lida com a atualização do status.
                // Apenas informamos ao usuário e redirecionamos.

                alert('Obrigado! Assim que confirmarmos o pagamento via PIX, seu pedido será processado.');
                window.location.href = '{{ route("home.index") }}'; // Redireciona para a home
            }
        }

        // Iniciar contador quando a página carregar
        $(document).ready(function () {
            startCountdown();
        });


        // Iniciar contador e barra de progresso quando a página carregar
        $(document).ready(function () {
            startCountdown();

            // LÓGICA DA BARRA DE PROGRESSO
            const totalSteps = 3;
            const currentStep = 3; // ETAPA FINAL DE CONFIRMAÇÃO
            const progressBar = document.querySelector('.progress-line');

            // Calcula a largura: (3 - 1) / (3 - 1) * 100 = 100% de progresso
            const progress = ((currentStep - 1) / (totalSteps - 1)) * 100;

            // Define a largura da linha de progresso (94% para alinhar com a bolinha)
            const lineWidth = (progress / 100) * 94;

            progressBar.style.width = lineWidth + '%';
        });
    </script>

</body>

</html>