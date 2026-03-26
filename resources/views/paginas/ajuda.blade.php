@extends('layouts.cabecario')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/paginas-institucionais.css') }}">

    <div class="institutional-page">
        <div class="container py-5">
            <div class="page-header">
                <h1>❓ Central de Ajuda ❓</h1>
                <div class="header-decoration"></div>
                <p class="lead text-muted">Tá perdido? Relaxa, a gente te ajuda! 🦸‍♀️</p>
            </div>

            <!-- Cards de Ajuda Rápida -->
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="contact-card">
                        <i class="fab fa-whatsapp"></i>
                        <h4>WhatsApp</h4>
                        <p>(11) 99999-9999</p>
                        <span class="badge-custom badge-success">✅ Resposta em até 5min</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="contact-card">
                        <i class="far fa-envelope"></i>
                        <h4>E-mail</h4>
                        <p>ajuda@cantinhodaisa.com.br</p>
                        <span class="badge-custom badge-info">📨 Resposta em até 24h</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="contact-card">
                        <i class="fab fa-instagram"></i>
                        <h4>Instagram</h4>
                        <p>@cantinho_das_isas_</p>
                        <span class="badge-custom badge-warning">📱 Responde por lá também!</span>
                    </div>
                </div>
            </div>

            <!-- FAQ Divertido -->
            <h2 class="text-center mb-4" style="color: var(--primary);">
                <i class="fas fa-question-circle me-2"></i> Perguntas Frequentes <i class="fas fa-question-circle ms-2"></i>
            </h2>

            <div class="accordion accordion-custom mb-5" id="faqAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                            🚚 Onde está meu pedido? Já tô ansiosa!
                        </button>
                    </h2>
                    <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <p>Calma, mamãe! 🐢 Seu pedido está a caminho!</p>
                            <p>Você pode acompanhar ele em tempo real no <a
                                    href="{{ route('home.dashboard', ['show' => 'pedidos']) }}">seu dashboard</a> ou pelo
                                código de rastreio que enviamos no e-mail.</p>
                            <div class="bg-light p-3 rounded">
                                <p class="mb-0"><i class="fas fa-truck text-primary me-2"></i> <strong>Prazos
                                        médios:</strong> Sudeste 3-7 dias | Sul 4-8 dias | Nordeste 7-12 dias | Norte 8-15
                                    dias</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faq2">
                            👗 Comprei e não serviu. E agora?
                        </button>
                    </h2>
                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <p>Normal! Criança cresce tão rápido que até a gente se perde nas medidas! 📏</p>
                            <p>Você tem <strong>30 dias</strong> para trocar por outro tamanho ou modelo. A gente paga o
                                frete de volta pra você! 🎁</p>
                            <p>É só seguir o passo a passo na nossa <a
                                    href="{{ route('paginas.troca-devolucao') }}">Política de Troca</a>.</p>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faq3">
                            💔 Me arrependi da compra. Posso desistir?
                        </button>
                    </h2>
                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <p>Claro! O Código de Defesa do Consumidor te garante <strong>7 dias</strong> pra se arrepender
                                (e a gente respeita muito isso! 👍).</p>
                            <p>É só entrar em contato com a gente pelo WhatsApp que a gente te ajuda.</p>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faq4">
                            🎁 Vem brinde? Adoro surpresa!
                        </button>
                    </h2>
                    <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <p>Sempre que possível, sim! 🎉</p>
                            <p>Em compras acima de R$200, adoramos mandar um mimozinho (pode ser adesivo, chaveirinho ou até
                                uma fitinha pro cabelo).</p>
                            <p><strong>Dica:</strong> Segue a gente no Instagram pra ficar sabendo das promoções relâmpago
                                com brindes especiais!</p>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faq5">
                            💳 Posso parcelar? Tô dura até o dia 30!
                        </button>
                    </h2>
                    <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <p>Claro! Parcelamos em até <strong>6x sem juros</strong> no cartão.</p>
                            <p>E se pagar no <strong>PIX</strong>, ainda ganha 5% de desconto! 💰</p>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faq6">
                            📏 Como sei a medida certa?
                        </button>
                    </h2>
                    <div id="faq6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <p>Na página de cada produto tem uma <strong>tabela de medidas</strong> completinha! 📐</p>
                            <p>Mas se ainda ficar na dúvida, chama a gente no WhatsApp. A gente mede a roupa na hora pra
                                você ficar segura!</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tutorial em Cards -->
            <h2 class="text-center mb-4" style="color: var(--primary);">
                <i class="fas fa-video me-2"></i> Como Fazer? <i class="fas fa-video ms-2"></i>
            </h2>

            <div class="row g-4">
                <div class="col-md-3">
                    <div class="info-card text-center h-100">
                        <div class="info-card-body">
                            <div class="display-1 mb-3">1️⃣</div>
                            <h5>Como comprar</h5>
                            <p class="text-muted">Clique no produto, escolha tamanho/cor e vá pro carrinho. Fácil que nem
                                fazer um bolo! 🍰</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-card text-center h-100">
                        <div class="info-card-body">
                            <div class="display-1 mb-3">2️⃣</div>
                            <h5>Como pagar</h5>
                            <p class="text-muted">Escolha PIX (5% off), cartão ou boleto. Seguro e protegido pelo PagSeguro!
                                🔒</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-card text-center h-100">
                        <div class="info-card-body">
                            <div class="display-1 mb-3">3️⃣</div>
                            <h5>Como rastrear</h5>
                            <p class="text-muted">Assim que postar, mandamos código de rastreio. Aí é só ficar na janela
                                esperando! 🚚</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-card text-center h-100">
                        <div class="info-card-body">
                            <div class="display-1 mb-3">4️⃣</div>
                            <h5>Como trocar</h5>
                            <p class="text-muted">Não serviu? Chama a gente. A gente troca sem stress! 👗</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botão de ajuda flutuante -->
            <a href="https://wa.me/5511999999999" target="_blank" class="help-button">
                <i class="fab fa-whatsapp"></i>
                <span class="help-tooltip">Precisa de ajuda? Fala com a gente!</span>
            </a>
        </div>
    </div>
@endsection