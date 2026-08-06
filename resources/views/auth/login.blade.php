<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cantinho da Isa</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    {{-- A linha abaixo assume que seu CSS de login agora está em public/css/tela-usuario/login.css --}}
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <script src="{{ asset('javascript/login.js') }}"></script>
    <script src="{{ asset('javascript/bloco-categoria.js') }}"></script>
</head>

@extends('layouts.cabecario') {{-- ESTE É O NOVO TOPO DO SEU ARQUIVO --}}

@section('content') {{-- TUDO ABAIXO SERÁ O CONTEÚDO ESPECÍFICO DESTA PÁGINA --}}

<main>
    <section class="login">
        <h2>Acesse sua conta</h2>

        {{-- Exibição de mensagens de status (sucesso/erro) --}}
        @if (session('status'))
        <div class="status">{{ session('status') }}</div>
        @endif

        {{-- Formulário de Login --}}
        <form action="{{ route('login') }}" method="POST" class="form-login">
            @csrf {{-- Diretiva Blade para proteção CSRF --}}

            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required placeholder="Digite seu e-mail"
                    value="{{ old('email') }}">
                {{-- Exibição de erros de validação para o campo 'email' --}}
                @error('email')
                <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="password" required placeholder="Digite sua senha">
                {{-- Exibição de erros de validação para o campo 'password' --}}
                @error('password')
                <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-login">Continuar</button>

            <div class="esqueci-senha">
                <a href="{{ route('password.request') }}" class="link-esqueci-senha">Esqueci minha senha</a>
            </div>
        </form>

        <div class="cadastro-link">
            Não tem uma conta? <a href="{{ route('register') }}">CADASTRE-SE</a>
        </div>

        <div class="social-login">
            <a href="{{ route('auth.google') }}" class="btn-google">
                <i class="fab fa-google"></i> Entrar com o Google
            </a>
        </div>
        
    </section>
</main>

@endsection

</html>