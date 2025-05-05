<!DOCTYPE html>
<html lang="pt-BR">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="{{ asset('css/login.css') }}">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
   <title>Login - Cantinho da Isa</title>
</head>

<body>
    <nav class="header-line">
      <div class="social-icons">
        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
        <a href="#" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
      </div>
      <div class="top-nav">
        <a href="#" aria-label="Meus pedidos"><i class="fas fa-box"></i> Meus pedidos</a>
        <a href="#" aria-label="Favoritos"><i class="fas fa-heart"></i> Favoritos</a>
        <a href="#" aria-label="Carrinho"><i class="fas fa-shopping-cart"></i> Meu carrinho</a>
      </div>
    </nav>

    <header>
        <div class="logo">
            <img src="../img/logo/ft_logo.png" alt="logo" class="logo-img">
        </div>
    </header>

    <main>
        <section class="login">
            <h2>Faça seu login</h2>

            @if (session('status'))
                <div class="status">{{ session('status') }}</div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="form-login">
                @csrf

                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" required placeholder="Digite seu e-mail" value="{{ old('email') }}">
                    @error('email')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="password" required placeholder="Digite sua senha">
                    @error('password')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-login">Entrar</button>

                <div class="esqueci-senha">
                    <a href="{{ route('password.request') }}" class="link-esqueci-senha">Esqueceu sua senha?</a>
                </div>

                <div class="registrar-conta">
                    <p>Não tem uma conta? <a href="{{ route('register') }}">Registre-se</a></p>
                </div>
            </form>
        </section>
    </main>

    <footer class="footer">
        © 2024 Cantinho da Isa. Todos os direitos reservados.
    </footer>

</body>
</html>
