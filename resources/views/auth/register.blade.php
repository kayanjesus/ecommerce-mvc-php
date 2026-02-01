<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cantinho da Isa - Cadastro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    {{-- A linha abaixo assume que seu CSS de cadastro agora está em public/css/cadastro.css --}}
    <link rel="stylesheet" href="{{ asset('css/cadastro.css') }}">
    <script src="{{ asset('javascript/bloco-categoria.js') }}"></script>
</head>

@extends('layouts.cabecario') {{-- ESTE É O NOVO TOPO DO SEU ARQUIVO --}}

@section('content') {{-- TUDO ABAIXO SERÁ O CONTEÚDO ESPECÍFICO DESTA PÁGINA --}}



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
                        maxlength="14" required oninput="formatarCPF(this)">
                    @error('cpf') <div class="error">{{ $message }}</div> @enderror
                </div>

                {{-- Campos separados para Dia, Mês e Ano --}}
                <div class="form-group data-nascimento">
                    <label>Data de Nascimento</label>
                    <div class="data-inputs">
                        <input type="number" id="dia" name="day" min="1" max="31" required placeholder="Dia"
                            value="{{ old('day') }}"
                            oninput="if(this.value.length > 2) this.value = this.value.slice(0, 2);">
                        <input type="number" id="mes" name="month" min="1" max="12" required placeholder="Mês"
                            value="{{ old('month') }}"
                            oninput="if(this.value.length > 2) this.value = this.value.slice(0, 2);">
                        <input type="number" id="ano" name="year" min="1900" max="{{ date('Y') }}" required
                            placeholder="Ano" value="{{ old('year') }}"
                            oninput="if(this.value.length > 4) this.value = this.value.slice(0, 4);">
                    </div>
                    {{-- Exibir erros para os campos de data, se houver --}}
                    @error('day') <div class="error">{{ $message }}</div> @enderror
                    @error('month') <div class="error">{{ $message }}</div> @enderror
                    @error('year') <div class="error">{{ $message }}</div> @enderror
                    {{-- Ou um erro geral se a combinação for inválida --}}
                    @error('data_nascimento') <div class="error">{{ $message }}</div> @enderror
                </div>

                <!-- <div class="form-group">
                        <label for="telefone">Número de Contato (com DDD)</label>
                        <input type="tel" id="telefone" name="telefone" value="{{ old('telefone') }}"
                            pattern="\(\d{2}\)\s?\d{4,5}-?\d{4}" placeholder="(XX) XXXXX-XXXX" required
                            oninput="formatarTelefone(this)">
                        @error('telefone') <div class="error">{{ $message }}</div> @enderror
                    </div> -->

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

@endsection


<script>
    function formatarCPF(campo) {
        let cpf = campo.value.replace(/\D/g, '');
        if (cpf.length > 11) cpf = cpf.slice(0, 11);
        cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
        cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
        cpf = cpf.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        campo.value = cpf;
    }
</script>

<script>
    function formatarTelefone(campo) {
        let value = campo.value.replace(/\D/g, '');
        let formattedValue = '';

        if (value.length > 0) {
            formattedValue = '(' + value.substring(0, 2);
            if (value.length > 2) {
                formattedValue += ') ';
                if (value.length <= 7) {
                    formattedValue += value.substring(2, 6);
                    if (value.length > 6) {
                        formattedValue += '-' + value.substring(6, 10);
                    }
                } else {
                    formattedValue += value.substring(2, 7);
                    if (value.length > 7) {
                        formattedValue += '-' + value.substring(7, 11);
                    }
                }
            }
        }
        campo.value = formattedValue;
    }

    document.addEventListener('DOMContentLoaded', function () {
        const telefoneInput = document.getElementById('telefone');
        if (telefoneInput && telefoneInput.value) {
            formatarTelefone(telefoneInput);
        }
    });
</script>

{{-- Script para blocos de categoria, se necessário --}}
{{--
<script src="{{ asset('javascript/bloco-categoria.js') }}"></script> --}}


</html>