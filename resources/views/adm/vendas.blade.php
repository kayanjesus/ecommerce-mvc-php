<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Dashboard de Vendas</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="{{asset('css/adm/vendas.css')}}">
    <link rel=" stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>

<body>
    <header>
        <a href="{{ route('home.index') }}" class="botao-link">
            CANTINHO DA ISA
        </a>
    </header>

    <div class="main-container">
        <aside class="sidebar">
            <div class="user-info">
                <i class="fas fa-user"></i>
                <input type="text" value="{{ Auth::user()->email }}" readonly />
            </div>
            <nav class="menu">
                <a href="{{ route('adm.dashboard') }}">
                    <button class="menu-btn">Inicial</button>
                </a>
                <a href="{{ route('adm.pedidos') }}">
                    <button class="menu-btn">Pedidos</button>
                </a>
                <a href="{{ route('adm.pdtestoque') }}">
                    <button class="menu-btn">Produtos e estoque</button>
                </a>
                <a href="{{ route('adm.cdtproduto') }}">
                    <button class="menu-btn">Cadastro de produtos</button>
                </a>
                <a href="{{ route('adm.usercadastrado') }}">
                    <button class="menu-btn">Usuários cadastrados</button>
                </a>
                <a href="{{ route('adm.vendas') }}">
                    <button class="menu-btn active">Vendas</button>
                </a>
            </nav>
            <form method="POST" class="logout" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout">SAIR</button>
            </form>
        </aside>
        <div class="main-content">
            <div class="hover">
                <div class="card">
                    <div>
                        <strong>Dezembro</strong><br>Total de Recebido
                    </div>
                    <div>
                        <h2>3.000,00</h2>
                        <span>Total de vendas: 50</span>
                    </div>
                </div>
                <div class="card">
                    <div>
                        <strong>Novembro</strong><br>Total de Recebido
                    </div>
                    <div>
                        <h2>2.000,00</h2>
                        <span>Total de vendas: 30</span>
                    </div>
                </div>
                <div class="card">
                    <div>
                        <strong>Outubro</strong><br>Total de Recebido
                    </div>
                    <div>
                        <h2>1.000,00</h2>
                        <span>Total de vendas: 21</span>
                    </div>
                </div>
            </div>

            <div class="estatisticas">
                <h3>Estatísticas</h3>
                <p>Vendas de cada mês</p>
                <canvas id="grafico-vendas"></canvas>
                <h3>Estatísticas</h3>
                <p>Faturamento de cada mês</p>
                <canvas id="grafico-faturamento"></canvas>
            </div>
        </div>
    </div>

    <script>
        const ctx1 = document.getElementById('grafico-vendas').getContext('2d');
        const ctx2 = document.getElementById('grafico-faturamento').getContext('2d');

        const options = {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        font: {
                            size: 23 // aumenta os números do eixo Y
                        }
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: {
                        font: {
                            size: 23 // aumenta os nomes dos meses no eixo X
                        }
                    }
                }
            }
        };

        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio'],
                datasets: [{
                    label: 'Vendas',
                    data: [12, 19, 25, 30, 45],
                    backgroundColor: '#8b3e3e',
                    borderRadius: 4
                }]
            },
            options
        });

        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio'],
                datasets: [{
                    label: 'Faturamento',
                    data: [10, 15, 18, 25, 31],
                    backgroundColor: '#8b3e3e',
                    borderRadius: 4
                }]
            },
            options
        });



    </script>
</body>

</html>