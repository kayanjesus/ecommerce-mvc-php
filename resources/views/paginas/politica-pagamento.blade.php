@extends('layouts.cabecario')

@section('content')
<link rel="stylesheet" href="{{ asset('css/paginas-institucionais.css') }}">

<div class="institutional-page">
    <div class="container py-5">
        <div class="page-header">
            <h1>💳 Política de Pagamento 💳</h1>
            <div class="header-decoration"></div>
            <p class="lead text-muted">Aqui você escolhe como pagar. A gente só quer ver você feliz! 🥰</p>
        </div>

        <!-- Banner de desconto -->
        <div class="info-card mb-5">
            <div class="info-card-body p-5 text-center" style="background: linear-gradient(135deg, #32BCAD 0%, #1f8a7c 100%); color: white;">
                <div class="display-1 mb-3">⚡</div>
                <h2 class="mb-3 text-white">PIX: 5% DE DESCONTO!</h2>
                <p class="fs-4 mb-0 text-white">Pagou à vista via PIX, ganhou desconto na hora! 🎉</p>
            </div>
        </div>

        <!-- Cardápio de pagamentos -->
        <h2 class="text-center mb-4" style="color: var(--primary);">
            <i class="fas fa-credit-card me-2"></i> Escolha seu sabor de pagamento <i class="fas fa-credit-card ms-2"></i>
        </h2>

        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="info-card h-100">
                    <div class="info-card-body text-center">
                        <div class="display-1 mb-3">
                            <i class="fa-brands fa-pix" style="color: #32BCAD;"></i>
                        </div>
                        <h3 class="mb-3">PIX</h3>
                        <div class="bg-light p-3 rounded mb-3">
                            <p class="fs-3 fw-bold text-success mb-0">5% OFF</p>
                        </div>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Confirmação em minutos</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Pagamento via QR Code</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Liberação imediata do pedido</li>
                        </ul>
                        <div class="mt-3">
                            <span class="badge-custom badge-success">MAIS RÁPIDO</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="info-card h-100">
                    <div class="info-card-body text-center">
                        <div class="display-1 mb-3">
                            <i class="fas fa-credit-card" style="color: #9b2a2a;"></i>
                        </div>
                        <h3 class="mb-3">Cartão de Crédito</h3>
                        <div class="bg-light p-3 rounded mb-3">
                            <p class="fs-3 fw-bold text-primary mb-0">Até 6x sem juros</p>
                        </div>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Parcelas a partir de R$30</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Aceitamos todas as bandeiras</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Confirmação em até 1 dia útil</li>
                        </ul>
                        <div class="mt-3">
                            <span class="badge-custom badge-info">MAIS PARCELADO</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="info-card h-100">
                    <div class="info-card-body text-center">
                        <div class="display-1 mb-3">
                            <i class="fas fa-barcode" style="color: #333;"></i>
                        </div>
                        <h3 class="mb-3">Boleto Bancário</h3>
                        <div class="bg-light p-3 rounded mb-3">
                            <p class="fs-3 fw-bold text-primary mb-0">Sem juros</p>
                        </div>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Pagamento em qualquer banco</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Vence em 3 dias úteis</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Confirmação em até 3 dias</li>
                        </ul>
                        <div class="mt-3">
                            <span class="badge-custom badge-warning">MAIS TRADICIONAL</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabela de parcelamento divertida -->
        <div class="info-card mb-5">
            <div class="info-card-header">
                <h3><i class="fas fa-calculator"></i> Como funciona o parcelamento?</h3>
            </div>
            <div class="info-card-body">
                <p class="text-center mb-4">Fizemos uma tabelinha pra você visualizar melhor! 📊</p>
                <div class="table-responsive">
                    <table class="table table-custom">
                        <thead>
                            <tr>
                                <th>Valor da Compra</th>
                                <th>Parcelas</th>
                                <th>Juros</th>
                                <th>Valor da Parcela</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Até R$ 180</strong></td>
                                <td>3x</td>
                                <td><span class="badge-custom badge-success">SEM JUROS</span></td>
                                <td>A partir de R$ 60</td>
                            </tr>
                            <tr>
                                <td><strong>De R$ 180 a R$ 360</strong></td>
                                <td>6x</td>
                                <td><span class="badge-custom badge-success">SEM JUROS</span></td>
                                <td>A partir de R$ 30</td>
                            </tr>
                            <tr>
                                <td><strong>Acima de R$ 360</strong></td>
                                <td>6x</td>
                                <td><span class="badge-custom badge-success">SEM JUROS</span></td>
                                <td>Menos de R$ 60 por mês</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p class="text-muted mt-3 text-center"><i class="fas fa-info-circle me-2"></i> Parcelas mínimas de R$30,00</p>
            </div>
        </div>

        <!-- Segurança -->
        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="info-card h-100">
                    <div class="info-card-header">
                        <h3><i class="fas fa-shield-alt"></i> Segurança de Dados</h3>
                    </div>
                    <div class="info-card-body">
                        <div class="text-center mb-4">
                            <img src="{{ asset('img/pagseguro.webp') }}" alt="PagSeguro" style="max-width: 150px;">
                        </div>
                        <p>Seus dados estão protegidos com a gente! 🔒</p>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-lock text-primary me-2"></i> Criptografia SSL de ponta a ponta</li>
                            <li class="mb-2"><i class="fas fa-lock text-primary me-2"></i> PagSeguro processa os pagamentos</li>
                            <li class="mb-2"><i class="fas fa-lock text-primary me-2"></i> Nunca armazenamos seus dados bancários</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="info-card h-100">
                    <div class="info-card-header">
                        <h3><i class="fas fa-clock"></i> Prazos de Confirmação</h3>
                    </div>
                    <div class="info-card-body">
                        <div class="timeline-mini">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-success text-white rounded-circle p-2 me-3">⚡</div>
                                <div>
                                    <h5 class="mb-0">PIX</h5>
                                    <p class="text-muted mb-0">Confirma em minutos! É o nosso queridinho</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary text-white rounded-circle p-2 me-3">💳</div>
                                <div>
                                    <h5 class="mb-0">Cartão</h5>
                                    <p class="text-muted mb-0">Até 1 dia útil (geralmente é mais rápido)</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-warning text-white rounded-circle p-2 me-3">📄</div>
                                <div>
                                    <h5 class="mb-0">Boleto</h5>
                                    <p class="text-muted mb-0">Até 3 dias úteis após o pagamento</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Problemas com pagamento -->
        <div class="info-card mb-5">
            <div class="info-card-header">
                <h3><i class="fas fa-exclamation-circle"></i> E se der problema?</h3>
            </div>
            <div class="info-card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center p-3">
                            <div class="display-3 mb-2">💳</div>
                            <h5>Cartão negado?</h5>
                            <p class="text-muted">Pode ser limite ou dados errados. Tenta de novo ou escolhe outra forma!</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-3">
                            <div class="display-3 mb-2">📅</div>
                            <h5>Boleto venceu?</h5>
                            <p class="text-muted">Gera outro no dashboard! Boletos vencidos não são aceitos.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-3">
                            <div class="display-3 mb-2">❌</div>
                            <h5>Pagou e não confirmou?</h5>
                            <p class="text-muted">Calma! Chama a gente no WhatsApp que a gente resolve rapidinho.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reembolsos -->
        <div class="info-card">
            <div class="info-card-header">
                <h3><i class="fas fa-undo-alt"></i> Política de Reembolso</h3>
            </div>
            <div class="info-card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <p>Se precisar de reembolso, devolvemos o dinheiro assim:</p>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fa-brands fa-pix text-success me-2"></i> <strong>PIX:</strong> Estorno na conta em até 24h úteis</li>
                            <li class="mb-2"><i class="fas fa-credit-card text-primary me-2"></i> <strong>Cartão:</strong> Estorno na fatura em até 2 ciclos</li>
                            <li class="mb-2"><i class="fas fa-barcode text-warning me-2"></i> <strong>Boleto:</strong> Transferência em até 10 dias úteis</li>
                        </ul>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="display-1">💰</div>
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