@extends('layouts.cabecario')

@section('content')
<link rel="stylesheet" href="{{ asset('css/paginas-institucionais.css') }}">

<div class="institutional-page">
    <div class="container py-5">
        <div class="page-header">
            <h1>🔒 Política de Privacidade 🔒</h1>
            <div class="header-decoration"></div>
            <p class="lead text-muted">Seus dados estão mais seguros que brinquedo escondido do cachorro! 🐶</p>
        </div>

        <!-- Mascote da privacidade -->
        <div class="info-card mb-5">
            <div class="info-card-body p-5 text-center">
                <div class="display-1 mb-3">🦊</div>
                <h3 class="mb-3">Conheça o Guardião dos Dados!</h3>
                <p class="fs-5">Ele é o protetor das suas informações. Pode confiar!</p>
                <div class="row justify-content-center mt-4">
                    <div class="col-md-8">
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar bg-success" style="width: 100%">100% SEGURO</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cards de coleta de dados -->
        <h2 class="text-center mb-4" style="color: var(--primary);">
            <i class="fas fa-database me-2"></i> Quais informações a gente coleta? <i class="fas fa-database ms-2"></i>
        </h2>

        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="info-card h-100">
                    <div class="info-card-header">
                        <h3><i class="fas fa-pencil-alt"></i> Você nos conta</h3>
                    </div>
                    <div class="info-card-body">
                        <ul class="list-unstyled">
                            <li class="mb-3"><i class="fas fa-user-circle text-primary me-2"></i> <strong>Nome completo</strong> - pra gente te chamar pelo nome</li>
                            <li class="mb-3"><i class="fas fa-id-card text-primary me-2"></i> <strong>CPF</strong> - pra nota fiscal e segurança</li>
                            <li class="mb-3"><i class="fas fa-envelope text-primary me-2"></i> <strong>E-mail</strong> - pra te avisar das novidades</li>
                            <li class="mb-3"><i class="fas fa-map-marker-alt text-primary me-2"></i> <strong>Endereço</strong> - pra entrega chegar certinha</li>
                            <li class="mb-3"><i class="fas fa-phone text-primary me-2"></i> <strong>Telefone</strong> - pra gente se falar</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="info-card h-100">
                    <div class="info-card-header">
                        <h3><i class="fas fa-robot"></i> O sistema descobre</h3>
                    </div>
                    <div class="info-card-body">
                        <ul class="list-unstyled">
                            <li class="mb-3"><i class="fas fa-network-wired text-primary me-2"></i> <strong>Endereço IP</strong> - de onde você está acessando</li>
                            <li class="mb-3"><i class="fas fa-globe text-primary me-2"></i> <strong>Navegador</strong> - Chrome, Firefox, etc</li>
                            <li class="mb-3"><i class="fas fa-cookie-bite text-primary me-2"></i> <strong>Cookies</strong> - pra lembrar do seu carrinho</li>
                            <li class="mb-3"><i class="fas fa-clock text-primary me-2"></i> <strong>Páginas visitadas</strong> - pra melhorar a loja</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Por que coletamos? -->
        <div class="info-card mb-5">
            <div class="info-card-header">
                <h3><i class="fas fa-question-circle"></i> E pra que serve tudo isso?</h3>
            </div>
            <div class="info-card-body">
                <div class="row">
                    <div class="col-md-3 text-center mb-3">
                        <div class="bg-light rounded-circle p-3 d-inline-block mb-2">
                            <i class="fas fa-shopping-cart fa-2x text-primary"></i>
                        </div>
                        <p><strong>Processar pedidos</strong></p>
                    </div>
                    <div class="col-md-3 text-center mb-3">
                        <div class="bg-light rounded-circle p-3 d-inline-block mb-2">
                            <i class="fas fa-truck fa-2x text-primary"></i>
                        </div>
                        <p><strong>Entregar produtos</strong></p>
                    </div>
                    <div class="col-md-3 text-center mb-3">
                        <div class="bg-light rounded-circle p-3 d-inline-block mb-2">
                            <i class="fas fa-chart-line fa-2x text-primary"></i>
                        </div>
                        <p><strong>Melhorar a loja</strong></p>
                    </div>
                    <div class="col-md-3 text-center mb-3">
                        <div class="bg-light rounded-circle p-3 d-inline-block mb-2">
                            <i class="fas fa-bullhorn fa-2x text-primary"></i>
                        </div>
                        <p><strong>Ofertas especiais</strong></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Compartilhamento -->
        <h2 class="text-center mb-4" style="color: var(--primary);">
            <i class="fas fa-share-alt me-2"></i> Com quem a gente compartilha? <i class="fas fa-share-alt ms-2"></i>
        </h2>

        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="info-card text-center h-100">
                    <div class="info-card-body">
                        <div class="display-2 mb-3">📦</div>
                        <h4>Transportadoras</h4>
                        <p>Seu endereço e telefone vão pra eles (pra entrega chegar!)</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-card text-center h-100">
                    <div class="info-card-body">
                        <div class="display-2 mb-3">💰</div>
                        <h4>PagSeguro</h4>
                        <p>Dados de pagamento vão direto pra eles (super seguros!)</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-card text-center h-100">
                    <div class="info-card-body">
                        <div class="display-2 mb-3">⚖️</div>
                        <h4>Justiça</h4>
                        <p>Se for exigido por lei, compartilhamos com autoridades</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Segurança em destaque -->
        <div class="info-card mb-5" style="border: 2px solid #28a745;">
            <div class="info-card-body p-5">
                <div class="row align-items-center">
                    <div class="col-md-2 text-center">
                        <div class="display-1">🔐</div>
                    </div>
                    <div class="col-md-10">
                        <h3 class="text-success">Seus dados estão protegidos!</h3>
                        <p>Usamos criptografia SSL (aquele cadeado no navegador) e nunca armazenamos dados bancários. Pode comprar tranquila!</p>
                        <div class="d-flex gap-2 mt-3">
                            <span class="badge-custom badge-success">✓ Criptografia SSL</span>
                            <span class="badge-custom badge-success">✓ PagSeguro Seguro</span>
                            <span class="badge-custom badge-success">✓ LGPD Compliance</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seus direitos -->
        <h2 class="text-center mb-4" style="color: var(--primary);">
            <i class="fas fa-hand-peace me-2"></i> Seus direitos (LGPD) <i class="fas fa-hand-peace ms-2"></i>
        </h2>

        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="info-card text-center h-100">
                    <div class="info-card-body">
                        <div class="display-3 mb-2">👀</div>
                        <h5>Ver dados</h5>
                        <p class="text-muted">Pode pedir pra ver tudo que temos sobre você</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-card text-center h-100">
                    <div class="info-card-body">
                        <div class="display-3 mb-2">✏️</div>
                        <h5>Corrigir</h5>
                        <p class="text-muted">Algo errado? Pode pedir correção</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-card text-center h-100">
                    <div class="info-card-body">
                        <div class="display-3 mb-2">🗑️</div>
                        <h5>Excluir</h5>
                        <p class="text-muted">Quer apagar a conta? A gente apaga!</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-card text-center h-100">
                    <div class="info-card-body">
                        <div class="display-3 mb-2">🚫</div>
                        <h5>Parar marketing</h5>
                        <p class="text-muted">Pode pedir pra não receber promoções</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cookies -->
        <div class="info-card mb-5">
            <div class="info-card-header">
                <h3><i class="fas fa-cookie-bite"></i> Sobre os cookies...</h3>
            </div>
            <div class="info-card-body">
                <div class="row">
                    <div class="col-md-8">
                        <p>Usamos cookies pra lembrar do seu carrinho e melhorar sua experiência. Eles não são comestíveis (infelizmente! 🍪), mas são importantes pro site funcionar.</p>
                        <p>Você pode desabilitar no navegador, mas aí algumas coisas podem não funcionar direitinho.</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="display-1">🍪</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contato -->
        <div class="info-card">
            <div class="info-card-header">
                <h3><i class="fas fa-envelope"></i> Fale com a encarregada de dados</h3>
            </div>
            <div class="info-card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <p>Se tiver qualquer dúvida sobre seus dados, fala com a gente!</p>
                        <p><i class="fas fa-envelope text-primary me-2"></i> privacidade@cantinhodaisa.com.br</p>
                        <p><i class="fas fa-phone text-primary me-2"></i> (11) 99999-9999</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="display-1">📧</div>
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