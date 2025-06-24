<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cantinho da Isa - Painel de Vendas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    {{-- Seu CSS específico da tela 2 --}}
    <link rel="stylesheet" href="{{ asset('css/adm/vendas.css') }}">
    {{-- Adicionado Font Awesome, pois a TELA 1 usava e alguns ícones podem não ter correspondente direto no Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    {{-- Chart.js para os gráficos --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <header class="main-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col ps-4">
                    {{-- Link para a home, como na TELA 1 --}}
                    <h1 class="store-title"><a href="{{ route('home.index') }}" class="text-decoration-none text-white">CANTINHO DA ISA</a></h1>
                </div>
            </div>
        </div>
    </header>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-2 d-flex flex-column h-100">
                    <div class="admin-container ps-2 pe-2 mb-2">
                        <a href="#" class="admin-button">
                            <i class="bi bi-person-circle"></i> {{ Auth::user()->email }}
                        </a>
                    </div>

                    <ul class="nav flex-column sidebar-menu flex-grow-1">
                        <li class="nav-item">
                            <a class="nav-link menu-button" href="{{ route('adm.dashboard') }}">
                                <i class="bi bi-house"></i> Início
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link menu-button" href="{{ route('adm.pedidos') }}">
                                <i class="bi bi-receipt"></i> Pedidos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link menu-button" href="{{ route('adm.pdtestoque') }}">
                                <i class="bi bi-box-seam"></i> Produtos e estoque
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link menu-button" href="{{ route('adm.cdtproduto') }}">
                                <i class="bi bi-plus-circle"></i> Cadastro de produtos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link menu-button" href="{{ route('adm.usercadastrado') }}">
                                <i class="bi bi-people"></i> Usuários cadastrados
                            </a>
                        </li>
                        <li class="nav-item">
                            {{-- Botão de Vendas agora ativo --}}
                            <a class="nav-link menu-button active" href="{{ route('adm.vendas') }}">
                                <i class="bi bi-graph-up-arrow"></i> Vendas
                            </a>
                        </li>
                    </ul>

                    <div class="mt-auto p-3">
                        {{-- Formulário de logout, como na TELA 1 --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="logout-button" style="background: none; border: none;">
                                <i class="bi bi-box-arrow-right"></i> Sair
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content pt-2">
                <div class="header">
                    <h3>Painel de Vendas</h3>
                </div>

                <div class="row mb-3">
                    {{-- Loop para os últimos 3 meses, dados do Laravel --}}
                    @foreach($mesesData as $mes)
                        <div class="col-md-4">
                            <div class="card p-3">
                                <div class="card-body text-center">
                                    {{-- Conteúdo do card de vendas por mês, como na TELA 1 --}}
                                    <h5 class="card-title"><strong>{{ $mes['nome'] }}</strong><br>Total de Recebido</h5>
                                    <p class="card-value">R$ {{ number_format($mes['total_recebido'], 2, ',', '.') }}</p>
                                    <p class="card-text">Total de vendas: {{ $mes['total_vendas'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="month-stats mb-3">
                            <h5 class="mb-3">Recebimento Mensal</h5>
                            <div class="list-group">
                                {{-- Este loop era um exemplo de dados estáticos na TELA 2.
                                     Como a TELA 1 não tinha essa seção específica com dados dinâmicos mensais detalhados (apenas o resumo dos 3 meses),
                                     manterei os exemplos da TELA 2, mas você pode adaptar para usar $mesesData novamente se quiser exibir aqui. --}}
                                @foreach($mesesData as $mes)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>{{ $mes['nome'] }}</span>
                                    <span class="badge rounded-pill bg-primary">R$ {{ number_format($mes['total_recebido'], 2, ',', '.') }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="month-stats">
                            <h5 class="mb-3">Estatísticas</h5>
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="sales-tab" data-bs-toggle="tab"
                                        data-bs-target="#sales" type="button" role="tab"
                                        aria-controls="sales" aria-selected="true">Vendas por Mês</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="revenue-tab" data-bs-toggle="tab"
                                        data-bs-target="#revenue" type="button" role="tab"
                                        aria-controls="revenue" aria-selected="false">Faturamento</button>
                                </li>
                            </ul>
                            <div class="tab-content p-3 border border-top-0 rounded-bottom">
                                <div class="tab-pane fade show active" id="sales" role="tabpanel"
                                    aria-labelledby="sales-tab">
                                    <div class="chart-container">
                                        {{-- Canvas para o gráfico de vendas --}}
                                        <canvas id="grafico-vendas"></canvas>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="revenue" role="tabpanel" aria-labelledby="revenue-tab">
                                    <div class="chart-container">
                                        {{-- Canvas para o gráfico de faturamento --}}
                                        <canvas id="grafico-faturamento"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    {{-- O script de Chart.js foi movido para cá, contendo os dados do Laravel --}}
    <script>
        const ctx1 = document.getElementById('grafico-vendas').getContext('2d');
        const ctx2 = document.getElementById('grafico-faturamento').getContext('2d');

        // Dados passados do Laravel para o JavaScript usando 
        const labelsGraficos = @json($labelsGraficos);
        const dataVendas = @json($dataVendas);
        const dataFaturamento = @json($dataFaturamento);

        const options = {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        font: {
                            size: 14 // Aumentado para melhor visualização em telas maiores
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 14 // Aumentado para melhor visualização em telas maiores
                        }
                    }
                }
            }
        };

        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: labelsGraficos,
                datasets: [{
                    label: 'Vendas',
                    data: dataVendas,
                    backgroundColor: '#8b3e3e',
                    borderRadius: 4
                }]
            },
            options
        });

        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: labelsGraficos,
                datasets: [{
                    label: 'Faturamento',
                    data: dataFaturamento,
                    backgroundColor: '#8b3e3e',
                    borderRadius: 4
                }]
            },
            options
        });
    </script>
</body>

</html>