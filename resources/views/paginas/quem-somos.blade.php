@extends('layouts.cabecario')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/paginas-institucionais.css') }}">

    <div class="institutional-page">
        <div class="container py-5">
            <!-- Header Divertido -->
            <div class="page-header">
                <h1>✨ Quem Somos ✨</h1>
                <div class="header-decoration"></div>
                <p class="lead text-muted">Um cantinho cheio de amor e roupinhas fofas para seus pequenos!</p>
            </div>

            <!-- Cards da História -->
            <div class="row mb-5">
                <div class="col-md-6">
                    <div class="info-card h-100">
                        <div class="info-card-header">
                            <h3><i class="fas fa-fairy"></i> Como Tudo Começou</h3>
                        </div>
                        <div class="info-card-body">
                            <div class="text-center mb-4">
                                <img src="{{ asset('img/logo/ft_logo.webp') }}" alt="Cantinho da Isa"
                                    style="max-width: 150px;">
                            </div>
                            <p class="fs-5">Era uma vez... uma mãe chamada <strong>Isabela</strong> que, como toda mãe,
                                vivia reclamando: <em>"Por que roupa de criança é tão cara?"</em> 🫠</p>

                            <p>Foi aí que ela teve uma ideia brilhante 💡: <strong>"E se eu criar minha própria loja de
                                    roupas infantis com preços que cabem no bolso e qualidade que dura até passar de irmão
                                    pra irmão?"</strong></p>

                            <div class="bg-light p-3 rounded mt-3">
                                <p class="mb-0"><i class="fas fa-quote-left text-primary me-2"></i> Começamos vendendo no
                                    quintal de casa, com 10 peças e muito sonho. Hoje, já vestimos crianças de todo o
                                    Brasil! <i class="fas fa-quote-right text-primary ms-2"></i></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="info-card h-100">
                        <div class="info-card-header"
                            style="background: linear-gradient(135deg, #c44b4b 0%, #9b2a2a 100%);">
                            <h3><i class="fas fa-heart"></i> Nossa Missão</h3>
                        </div>
                        <div class="info-card-body">
                            <div class="row text-center mb-4">
                                <div class="col-4">
                                    <div class="p-3 bg-light rounded-circle d-inline-block mb-2">
                                        <i class="fas fa-child fa-2x text-primary"></i>
                                    </div>
                                    <p class="fw-bold mb-0">Conforto</p>
                                </div>
                                <div class="col-4">
                                    <div class="p-3 bg-light rounded-circle d-inline-block mb-2">
                                        <i class="fas fa-smile fa-2x text-primary"></i>
                                    </div>
                                    <p class="fw-bold mb-0">Felicidade</p>
                                </div>
                                <div class="col-4">
                                    <div class="p-3 bg-light rounded-circle d-inline-block mb-2">
                                        <i class="fas fa-hand-holding-heart fa-2x text-primary"></i>
                                    </div>
                                    <p class="fw-bold mb-0">Economia</p>
                                </div>
                            </div>

                            <p>Nossa missão é simples: <strong>vestir os pequenos com estilo, conforto e sem pesar no
                                    bolso!</strong> Porque criança precisa:</p>

                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Correr, pular e
                                    brincar à vontade</li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Se sujar na escola
                                    sem preocupação</li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Usar roupas que duram
                                    até o irmão mais novo</li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Estar na moda sem
                                    gastar uma fortuna</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Valores em Cards Divertidos -->
            <h2 class="text-center mb-4" style="color: var(--primary);">
                <i class="fas fa-star me-2"></i> Nossos Valores <i class="fas fa-star ms-2"></i>
            </h2>

            <div class="row g-4 mb-5">
                <div class="col-md-3">
                    <div class="info-card text-center h-100">
                        <div class="info-card-body">
                            <div class="display-1 mb-3">🧵</div>
                            <h4>Qualidade</h4>
                            <p class="text-muted">Escolhemos cada peça como se fosse pro nosso próprio filho</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-card text-center h-100">
                        <div class="info-card-body">
                            <div class="display-1 mb-3">🤝</div>
                            <h4>Transparência</h4>
                            <p class="text-muted">O que você vê é o que você leva (sem letrinhas miúdas)</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-card text-center h-100">
                        <div class="info-card-body">
                            <div class="display-1 mb-3">💕</div>
                            <h4>Respeito</h4>
                            <p class="text-muted">Cada cliente é único e especial pra gente</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-card text-center h-100">
                        <div class="info-card-body">
                            <div class="display-1 mb-3">🌱</div>
                            <h4>Sustentabilidade</h4>
                            <p class="text-muted">Pensando no futuro dos nossos pequenos</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline da História -->
            <h2 class="text-center mb-4" style="color: var(--primary);">
                <i class="fas fa-clock me-2"></i> Nossa História em Linha do Tempo
            </h2>

            <div class="timeline mb-5">
                <div class="timeline-item">
                    <div class="timeline-number">1</div>
                    <div class="timeline-content">
                        <h4>2020 - O Sonho Começa 🌱</h4>
                        <p>Isabela começa vendendo roupinhas no quintal de casa para as amigas da maternidade.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-number">2</div>
                    <div class="timeline-content">
                        <h4>2022 - Crescendo Juntos 📈</h4>
                        <p>Já não cabia mais no quintal! Alugamos nosso primeiro espacinho e contratamos nossa primeira
                            ajudante.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-number">3</div>
                    <div class="timeline-content">
                        <h4>2024 - A Revolução Digital 💻</h4>
                        <p>Nasce o Cantinho da Isa Online! Agora clientes de todo o Brasil podem se encantar com nossas
                            peças.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-number">4</div>
                    <div class="timeline-content">
                        <h4>2026 - O Futuro 🚀</h4>
                        <p>Queremos vestir cada criança brasileira com amor, qualidade e preço justo.</p>
                    </div>
                </div>
            </div>

            <!-- Por que escolher a gente -->
            <div class="info-card">
                <div class="info-card-header">
                    <h3><i class="fas fa-question-circle"></i> Por que escolher o Cantinho da Isa?</h3>
                </div>
                <div class="info-card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled fs-5">
                                <li class="mb-3"><i class="fas fa-heart text-primary me-2"></i> <strong>Porque entendemos de
                                        infância!</strong> (já fomos crianças um dia 😉)</li>
                                <li class="mb-3"><i class="fas fa-heart text-primary me-2"></i> <strong>Porque criança
                                        precisa de roupa que dure</strong> (e a gente faz durar!)</li>
                                <li class="mb-3"><i class="fas fa-heart text-primary me-2"></i> <strong>Porque moda infantil
                                        não precisa ser caro</strong> (e a gente prova isso!)</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light p-4 rounded text-center">
                                <p class="fs-1 mb-2">🎉</p>
                                <p class="fw-bold">Já somos mais de <span class="text-primary">5.000 mães</span> que confiam
                                    na gente!</p>
                                <p>E você vai ser a próxima!</p>
                            </div>
                        </div>
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