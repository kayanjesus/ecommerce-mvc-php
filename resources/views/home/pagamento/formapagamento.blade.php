@extends('layouts.footer') {{-- ESTE É O NOVO TOPO DO SEU ARQUIVO --}}

@section('content') {{-- TUDO ABAIXO SERÁ O CONTEÚDO ESPECÍFICO DESTA PÁGINA --}}



    <!DOCTYPE html>
    <html lang="pt-BR">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Cantinho da Isa - Pagamento</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
        <link rel="stylesheet" href="{{ asset('css/pagamento/formapagamento.css') }}" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    </head>

    <body>
        <header class="topo">
            <div class="logo">
                <a href="{{ route('home.index') }}">
                    <img src="{{ asset('img/logo/ft_logo.webp') }}" alt="Cantinho da Isa - Logo" class="logo-img">
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
                <div class="etapa" data-step="3"> <span class="texto-etapa">Confirmação</span>
                    <div class="bolinha"></div>
                </div>
            </div>
        </header>

        <div class="linha-branca"></div>

        <div class="frete-banner">
            Frete Grátis - Sul e Sudeste a partir de R$250, demais regiões a partir de R$390
        </div>

        <main class="pagamento-container">
            <div class="pagamento-box">

                <h3 class="mt-4">Formas de Pagamento</h3>
                <form action="{{ route('pagamento.salvar-forma-pagamento') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <div class="list-group">
                            <label class="list-group-item list-group-item-action opcao">
                                <div class="d-flex align-items-center">
                                    <input class="form-check-input me-3" type="radio" name="metodo_pagamento" value="pix"
                                        required>
                                    <div>
                                        <i class="fa-solid fa-money-bill-transfer"></i>
                                        <strong>Pix</strong>
                                        <small class="d-block text-muted">5% de desconto</small>
                                    </div>

                                </div>
                            </label>

                            <label class="list-group-item list-group-item-action opcao">
                                <div class="d-flex align-items-center">
                                    <input class="form-check-input me-3" type="radio" name="metodo_pagamento"
                                        value="cartao">
                                    <div>
                                        <i class="fa-solid fa-credit-card"></i>
                                        <strong>Cartão de Crédito</strong>
                                        <small class="d-block text-muted">Parcele em até 6x</small>
                                    </div>
                                </div>
                            </label>

                            <label class="list-group-item list-group-item-action opcao">
                                <div class="d-flex align-items-center">
                                    <input class="form-check-input me-3" type="radio" name="metodo_pagamento"
                                        value="boleto">
                                    <div>
                                        <i class="fas fa-barcode fa-lg text-dark"></i>
                                        <strong>Boleto Bancário</strong>
                                        <small class="d-block text-muted">Pagamento em 1 parcela</small>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="total mt-4">
                        <strong>Total: R$ {{ number_format($total, 2, ',', '.') }}</strong>
                        <button type="submit" class="concluir">Continuar</button>
                    </div>
                </form>
            </div>
        </main>


        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // LÓGICA DA BARRA DE PROGRESSO
                const totalSteps = 3;
                const currentStep = 2; // ETAPA ATUAL
                const progressBar = document.querySelector('.progress-line');

                // Calcula a largura: (2 - 1) / (3 - 1) * 100 = 50%
                const progress = ((currentStep - 1) / (totalSteps - 1)) * 100;

                // Ajuste o tamanho da linha para 94% para alinhar com as bolinhas
                const lineWidth = (progress / 100) * 94;

                progressBar.style.width = lineWidth + '%';
            });
        </script>

    </body>

    </html>

@endsection {{-- FIM DO CONTEÚDO ESPECÍFICO DESTA PÁGINA --}}