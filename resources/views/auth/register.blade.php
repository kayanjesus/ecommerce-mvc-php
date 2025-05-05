<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('css/cadastro.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <title>Cadastro - Cantinho da Isa</title>
</head>

<body>
    <nav class="header-line">
        <div class="social-icons">
            <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
            <a href="#" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
        </div>
        <div class="top-nav">
            <a href="#"><i class="fas fa-box"></i> Meus pedidos</a>
            <a href="#"><i class="fas fa-heart"></i> Favoritos</a>
            <a href="#"><i class="fas fa-shopping-cart"></i> Meu carrinho</a>
        </div>
    </nav>

    <header>
        <div class="logo">
            <img src="../img/logo/ft_logo.png" alt="logo" class="logo-img">
        </div>
    </header>

    <main>
        <section class="cadastro">
            <h2>Cadastre-se</h2>
            <form action="{{ route('register') }}" method="POST" class="form-cadastro">
                @csrf

                <div class="form-group">
                    <label for="name">Nome Completo</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                        placeholder="Digite seu nome completo">
                    @error('name') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                        placeholder="Digite seu e-mail">
                    @error('email') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="cpf">CPF</label>
                    <input type="text" id="cpf" name="cpf" value="{{ old('cpf') }}" placeholder="Digite seu CPF"
                        maxlength="14" required>
                    @error('cpf') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="data_nasc">Data de Nascimento</label>
                    <input type="date" id="data_nasc" name="data_nasc" value="{{ old('data_nasc') }}" required>
                    @error('data_nasc') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="password">Senha</label>
                    <input type="password" id="password" name="password" required placeholder="Digite sua senha">
                    @error('password') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirme a Senha</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                        placeholder="Confirme sua senha">
                    @error('password_confirmation') <div class="error">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn-cadastro">Cadastrar</button>

                <div class="login-link">
                    <p>Já tem uma conta? <a href="{{ route('login') }}">Faça login</a></p>
                </div>
            </form>
        </section>
    </main>

    <footer class="footer">
        © 2024 Cantinho da Isa. Todos os direitos reservados.
    </footer>

</body>

</html>