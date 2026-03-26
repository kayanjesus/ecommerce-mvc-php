@extends('layouts.cabecario')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/paginas-institucionais.css') }}">

    <div class="institutional-page">
        <div class="container py-5">
            <div class="page-header">
                <h1>🚚 Política de Entrega 🚚</h1>
                <div class="header-decoration"></div>
                <p class="lead text-muted">Seu pedido chega voando! Ou quase isso... 🕊️</p>
            </div>

            <!-- Frete Grátis em destaque -->
            <div class="info-card mb-5">
                <div class="info-card-body p-5 text-center"
                    style="background: linear-gradient(135deg, #f8e6e6 0%, #fff 100%);">
                    <div class="display-1 mb-3">🎁</div>
                    <h2 class="mb-3">FRETE GRÁTIS!</h2>
                    <p class="fs-4 mb-0"><strong>Sul e Sudeste:</strong> compras acima de R$250</p>
                    <p class="fs-4"><strong>Demais regiões:</strong> compras acima de R$399</p>
                </div>
            </div>

            <!-- Prazos por região -->
            <h2 class="text-center mb-4" style="color: var(--primary);">
                <i class="fas fa-clock me-2"></i> Prazos de Entrega <i class="fas fa-clock ms-2"></i>
            </h2>

            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="info-card text-center">
                        <div class="info-card-body">
                            <div class="display-1 mb-2">🌆</div>
                            <h4>Sudeste</h4>
                            <p class="fs-1 fw-bold text-primary">3-7</p>
                            <p>dias úteis</p>
                            <span class="badge-custom badge-success">Mais rápido!</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-card text-center">
                        <div class="info-card-body">
                            <div class="display-1 mb-2">🌲</div>
                            <h4>Sul</h4>
                            <p class="fs-1 fw-bold text-primary">4-8</p>
                            <p>dias úteis</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-card text-center">
                        <div class="info-card-body">
                            <div class="display-1 mb-2">🌵</div>
                            <h4>Centro-Oeste</h4>
                            <p class="fs-1 fw-bold text-primary">5-10</p>
                            <p>dias úteis</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-card text-center">
                        <div class="info-card-body">
                            <div class="display-1 mb-2">🏖️</div>
                            <h4>Nordeste</h4>
                            <p class="fs-1 fw-bold text-primary">7-12</p>
                            <p>dias úteis</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-card text-center">
                        <div class="info-card-body">
                            <div class="display-1 mb-2">🌳</div>
                            <h4>Norte</h4>
                            <p class="fs-1 fw-bold text-primary">8-15</p>
                            <p>dias úteis</p>                         
                        </div>
                    </div>
                </div>
            </div>

            <!-- Processo de entrega em timeline -->
            <h2 class="text-center mb-4" style="color: var(--primary);">
                <i class="fas fa-box-open me-2"></i> O caminho do seu pedido <i class="fas fa-box-open ms-2"></i>
            </h2>

            <div class="timeline mb-5">
                <div class="timeline-item">
                    <div class="timeline-number">1</div>
                    <div class="timeline-content">
                        <h4>PAGOU, POSTAMOS! 💨</h4>
                        <p>Pagamento confirmado? Já vamos separar seu pedido com todo carinho.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-number">2</div>
                    <div class="timeline-content">
                        <h4>EMBALAGEM 🎁</h4>
                        <p>Até 2 dias úteis para embalar tudo com capricho (e talvez um mimozinho).</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-number">3</div>
                    <div class="timeline-content">
                        <h4>POSTAGEM 📮</h4>
                        <p>Correios ou transportadora levam seu pedido pra agência mais perto de você.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-number">4</div>
                    <div class="timeline-content">
                        <h4>RASTREIO 🔍</h4>
                        <p>Mandamos o código por e-mail e você acompanha cada passo (ansiosa igual a gente!).</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-number">5</div>
                    <div class="timeline-content">
                        <h4>CHEGOU! 🥳</h4>
                        <p>É só comemorar e vestir os pequenos com muito estilo!</p>
                    </div>
                </div>
            </div>

            <!-- Modalidades -->
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="info-card h-100">
                        <div class="info-card-header">
                            <h3><i class="fas fa-box"></i> PAC</h3>
                        </div>
                        <div class="info-card-body">
                            <p class="fs-2 text-center mb-3">🐢</p>
                            <p>Econômico, leva um tempinho a mais, mas cabe no bolso.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-card h-100">
                        <div class="info-card-header">
                            <h3><i class="fas fa-rocket"></i> Sedex</h3>
                        </div>
                        <div class="info-card-body">
                            <p class="fs-2 text-center mb-3">🚀</p>
                            <p>Entrega rápida pra quem não vê a hora de estrear!</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-card h-100">
                        <div class="info-card-header">
                            <h3><i class="fas fa-truck"></i> Transportadora</h3>
                        </div>
                        <div class="info-card-body">
                            <p class="fs-2 text-center mb-3">🚛</p>
                            <p>Para regiões específicas e compras grandes.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rastreio -->
            <div class="info-card mb-5">
                <div class="info-card-header">
                    <h3><i class="fas fa-search"></i> Quer saber onde está?</h3>
                </div>
                <div class="info-card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <p class="fs-5">Assim que postamos, mandamos um <strong>código de rastreio</strong> no seu
                                e-mail.</p>
                            <p>Você também pode acompanhar pelo <a
                                    href="{{ route('home.dashboard', ['show' => 'pedidos']) }}">seu dashboard</a>.</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <a href="https://www.correios.com.br" target="_blank" class="btn btn-primary">
                                Rastrear nos Correios
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Problemas -->
            <div class="info-card">
                <div class="info-card-header">
                    <h3><i class="fas fa-exclamation-triangle"></i> E se der problema?</h3>
                </div>
                <div class="info-card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="p-3">
                                <h5><i class="fas fa-clock text-warning me-2"></i> Atrasou?</h5>
                                <p>Chama a gente no WhatsApp que a gente pressiona os Correios!</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3">
                                <h5><i class="fas fa-house-user text-info me-2"></i> Não tava em casa?</h5>
                                <p>Após 3 tentativas, volta pra gente. Nova entrega tem custo de frete.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection