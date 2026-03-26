<style>
    :root {
        --primary-red: #9b2a2a;
        --white: #fff;
        --dark-gray: #333;
    }

    .error-404-container {
        min-height: 70vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
        background-color: var(--white);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .error-content {
        text-align: center;
        max-width: 600px;
        animation: fadeInUp 0.6s ease-out;
    }

    .error-number {
        font-size: 120px;
        font-weight: 800;
        color: var(--primary-red);
        line-height: 1;
        margin-bottom: 10px;
        text-shadow: 3px 3px 0 rgba(155, 42, 42, 0.2);
        animation: bounce 2s infinite;
    }

    .error-message {
        font-size: 28px;
        color: var(--dark-gray);
        margin-bottom: 15px;
        font-weight: 600;
    }

    .error-description {
        font-size: 18px;
        color: #666;
        margin-bottom: 30px;
        line-height: 1.6;
    }

    .kids-illustration {
        margin: 30px 0;
        position: relative;
        display: flex;
        justify-content: center;
        gap: 20px;
        flex-wrap: wrap;
    }

    .illustration-item {
        width: 100px;
        height: 100px;
        background-color: #f5f5f5;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: float 3s ease-in-out infinite;
        border: 3px solid var(--primary-red);
    }

    .illustration-item:nth-child(2) {
        animation-delay: 0.5s;
    }

    .illustration-item:nth-child(3) {
        animation-delay: 1s;
    }

    .illustration-item i {
        font-size: 50px;
        color: var(--primary-red);
    }

    .action-buttons {
        display: flex;
        gap: 15px;
        justify-content: center;
        flex-wrap: wrap;
        margin-top: 20px;
    }

    .btn-primary {
        display: inline-block;
        padding: 14px 35px;
        background-color: var(--primary-red);
        color: var(--white);
        text-decoration: none;
        border-radius: 30px;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s;
        border: 2px solid var(--primary-red);
    }

    .btn-primary:hover {
        background-color: #7a2121;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(155, 42, 42, 0.3);
    }

    .btn-secondary {
        display: inline-block;
        padding: 14px 35px;
        background-color: transparent;
        color: var(--primary-red);
        text-decoration: none;
        border-radius: 30px;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s;
        border: 2px solid var(--primary-red);
    }

    .btn-secondary:hover {
        background-color: var(--primary-red);
        color: var(--white);
        transform: translateY(-2px);
    }

    .warning-box {
        background-color: #fff3e0;
        padding: 25px;
        border-radius: 15px;
        margin: 30px 0;
        border-left: 5px solid var(--primary-red);
    }

    .warning-box p {
        font-size: 16px;
        color: var(--dark-gray);
        margin-bottom: 10px;
    }

    .warning-box i {
        color: var(--primary-red);
        margin-right: 10px;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes bounce {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-10px);
        }
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0) rotate(0deg);
        }

        50% {
            transform: translateY(-10px) rotate(5deg);
        }
    }

    @media (max-width: 768px) {
        .error-number {
            font-size: 80px;
        }

        .error-message {
            font-size: 24px;
        }

        .illustration-item {
            width: 70px;
            height: 70px;
        }

        .illustration-item i {
            font-size: 35px;
        }

        .btn-primary,
        .btn-secondary {
            padding: 12px 25px;
            font-size: 14px;
        }
    }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<div class="error-404-container">
    <div class="error-content">
        <div class="error-number">⏰ 419</div>

        <h1 class="error-message">Tempo Esgotado!</h1>

        <p class="error-description">
            Ai ai! Você demorou tanto para escolher a roupinha perfeita
            que o tempo acabou! Mas não se preocupe, seu carrinho está guardadinho.
        </p>

        <div class="kids-illustration">
            <div class="illustration-item">
                <i class="fas fa-hourglass-half"></i>
            </div>
            <div class="illustration-item">
                <i class="fas fa-clock"></i>
            </div>
            <div class="illustration-item">
                <i class="fas fa-shopping-cart"></i>
            </div>
        </div>

        <div class="warning-box">
            <p><i class="fas fa-info-circle"></i> O que aconteceu?</p>
            <p>Você ficou muito tempo sem interagir com a página e sua sessão expirou. É normal acontecer quando estamos
                escolhendo as melhores roupinhas!</p>
        </div>

        <div class="action-buttons">
            <a href="{{ route('pagamento.cep') }}" class="btn-primary">
                <i class="fas fa-shopping-cart"></i> Ver Meu Carrinho
            </a>
            <a href="javascript:location.reload()" class="btn-secondary">
                <i class="fas fa-sync-alt"></i> Tentar Novamente
            </a>
        </div>

        <div class="help-links" style="margin-top: 30px; display: flex; gap: 20px; justify-content: center;">
            <a href="{{ route('home.index') }}" style="color: var(--dark-gray); text-decoration: none;">
                <i class="fas fa-home" style="color: var(--primary-red);"></i> Voltar para Home
            </a>
            <a href="{{ route('home.index') }}" style="color: var(--dark-gray); text-decoration: none;">
                <i class="fas fa-search" style="color: var(--primary-red);"></i> Continuar Comprando
            </a>
        </div>
    </div>
</div>