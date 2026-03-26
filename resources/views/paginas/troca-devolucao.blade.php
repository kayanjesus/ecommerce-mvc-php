@extends('layouts.cabecario')

@section('content')
<link rel="stylesheet" href="{{ asset('css/paginas-institucionais.css') }}">

<div class="institutional-page">
    <div class="container py-5">
        <div class="page-header">
            <h1>🔄 Troca e Devolução 🔄</h1>
            <div class="header-decoration"></div>
            <p class="lead text-muted">Apertou? Não serviu? Arrependeu? A gente resolve! 🦸‍♀️</p>
        </div>

        <!-- Banner divertido -->
        <div class="info-card mb-5">
            <div class="info-card-body text-center p-5">
                <div class="display-1 mb-3">👕➡️👖</div>
                <h3 class="mb-3">Relaxa, Trocamos de boa!</h3>
                <p class="fs-5">Sabemos que criança cresce e às vezes a gente erra na medida. Por isso, criamos uma política de troca que cabe no seu bolso (e na sua pressa)!</p>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="info-card h-100">
                    <div class="info-card-header">
                        <h3><i class="fas fa-clock"></i> 7 Dias - Direito de Arrependimento</h3>
                    </div>
                    <div class="info-card-body">
                        <p class="fs-1 text-center mb-3">😬</p>
                        <p><strong>Comprei e me arrependi. E agora?</strong></p>
                        <p>Calma, acontece! Você tem até <strong>7 dias corridos</strong> após receber o produto para desistir da compra (é lei! ✅).</p>
                        <div class="bg-light p-3 rounded">
                            <p class="mb-0"><i class="fas fa-check-circle text-success me-2"></i> 100% do dinheiro de volta</p>
                            <p class="mb-0"><i class="fas fa-check-circle text-success me-2"></i> Não precisa dar motivo</p>
                            <p class="mb-0"><i class="fas fa-check-circle text-success me-2"></i> Frete de volta por nossa conta</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="info-card h-100">
                    <div class="info-card-header" style="background: linear-gradient(135deg, #c44b4b 0%, #9b2a2a 100%);">
                        <h3><i class="fas fa-arrows-alt-h"></i> 30 Dias - Troca por Tamanho</h3>
                    </div>
                    <div class="info-card-body">
                        <p class="fs-1 text-center mb-3">📏</p>
                        <p><strong>Comprei 2 anos, mas meu filho veste 3! 😱</strong></p>
                        <p>Acontece direto! Você tem <strong>30 dias</strong> para trocar por outro tamanho ou modelo.</p>
                        <div class="bg-light p-3 rounded">
                            <p class="mb-0"><i class="fas fa-check-circle text-success me-2"></i> Você paga o frete de volta (R$15 fixo)</p>
                            <p class="mb-0"><i class="fas fa-check-circle text-success me-2"></i> A gente paga o frete do novo produto</p>
                            <p class="mb-0"><i class="fas fa-check-circle text-success me-2"></i> Produto novo sai no mesmo dia!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="info-card h-100">
                    <div class="info-card-header">
                        <h3><i class="fas fa-tools"></i> 90 Dias - Produto com Defeito</h3>
                    </div>
                    <div class="info-card-body">
                        <p class="fs-1 text-center mb-3">😤</p>
                        <p><strong>Nossa! A roupa veio com defeito?</strong></p>
                        <p>Pedimos desculpas! Isso não é comum, mas se acontecer, você tem <strong>90 dias</strong> pra reclamar.</p>
                        <div class="bg-light p-3 rounded">
                            <p class="mb-0"><i class="fas fa-check-circle text-success me-2"></i> A gente busca o produto na sua casa</p>
                            <p class="mb-0"><i class="fas fa-check-circle text-success me-2"></i> Trocamos na hora ou devolvemos o dinheiro</p>
                            <p class="mb-0"><i class="fas fa-check-circle text-success me-2"></i> Você não paga nada!</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="info-card h-100">
                    <div class="info-card-header" style="background: linear-gradient(135deg, #c44b4b 0%, #9b2a2a 100%);">
                        <h3><i class="fas fa-truck"></i> 48 Horas - Avaria no Transporte</h3>
                    </div>
                    <div class="info-card-body">
                        <p class="fs-1 text-center mb-3">📦</p>
                        <p><strong>Chegou tudo amassado? 😭</strong></p>
                        <p>Se a embalagem chegou violada ou o produto veio danificado:</p>
                        <div class="bg-light p-3 rounded">
                            <p class="mb-0"><i class="fas fa-check-circle text-success me-2"></i> <strong>Recuse na hora!</strong> Anote o motivo no verso da nota</p>
                            <p class="mb-0"><i class="fas fa-check-circle text-success me-2"></i> Se já recebeu, avise em até 48h</p>
                            <p class="mb-0"><i class="fas fa-check-circle text-success me-2"></i> Enviamos outro produto em até 3 dias</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Como solicitar -->
        <div class="info-card mb-5">
            <div class="info-card-header">
                <h3><i class="fas fa-clipboard-list"></i> Como Solicitar? (É muito fácil!)</h3>
            </div>
            <div class="info-card-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <div class="bg-light rounded-circle p-3 d-inline-block mb-2">
                            <span class="fs-1">1️⃣</span>
                        </div>
                        <p><strong>Chama no WhatsApp</strong></p>
                        <p>(11) 99999-9999</p>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="bg-light rounded-circle p-3 d-inline-block mb-2">
                            <span class="fs-1">2️⃣</span>
                        </div>
                        <p><strong>Informa o pedido</strong></p>
                        <p>Número do pedido e o que aconteceu</p>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="bg-light rounded-circle p-3 d-inline-block mb-2">
                            <span class="fs-1">3️⃣</span>
                        </div>
                        <p><strong>Manda uma foto</strong></p>
                        <p>Do produto, da etiqueta, da embalagem</p>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="bg-light rounded-circle p-3 d-inline-block mb-2">
                            <span class="fs-1">4️⃣</span>
                        </div>
                        <p><strong>Pronto!</strong></p>
                        <p>A gente te dá as instruções</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabela de condições -->
        <div class="info-card">
            <div class="info-card-header">
                <h3><i class="fas fa-clipboard-check"></i> Regrinhas Básicas</h3>
            </div>
            <div class="info-card-body">
                <p class="mb-3">Para a troca ser aceita, o produto precisa estar:</p>
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> ✅ Sem sinal de uso</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> ✅ Com todas as etiquetas</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> ✅ Na embalagem original</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> ✅ Sem cheiro de perfume ou amaciante</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> ✅ Acompanhado da nota fiscal</li>
                        </ul>
                    </div>
                </div>
                <div class="bg-warning bg-opacity-10 p-3 rounded mt-3">
                    <p class="mb-0"><i class="fas fa-lightbulb text-warning me-2"></i> <strong>Dica de amiga:</strong> Experimente a roupa em local limpo e sem forçar as costuras. Se não servir, já guarda tudo direitinho na embalagem!</p>
                </div>
            </div>
        </div>

        <!-- Botão Voltar -->
        <div class="text-center mt-5">
            <a href="{{ url('/') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-home me-2"></i> Voltar para a Loja
            </a>
        </div>
    </div>
</div>
@endsection