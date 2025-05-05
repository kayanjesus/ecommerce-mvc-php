<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cantinho da Isa\Vendas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/vendas.css') }}">
</head>
<body>
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>CANTINHO DA ISA</h2>
                <p class="admin-email">admin@admin.com</p>
            </div>
            
            <div class="menu-section">
                <h4>MENU</h4>
                <ul>
                    <li><a href="#"><i class="bi bi-house-door"></i> Inicial</a></li>
                    <li><a href="#"><i class="bi bi-cart"></i> Pedidos</a></li>
                    <li><a href="#"><i class="bi bi-box-seam"></i> Produtos e estoque</a></li>
                    <li><a href="#"><i class="bi bi-plus-circle"></i> Cadastro de produtos</a></li>
                    <li><a href="#"><i class="bi bi-people"></i> Usuários cadastrados</a></li>
                    <li><a href="#"><i class="bi bi-graph-up"></i> Vendas</a></li>
                </ul>
            </div>
            
            <div class="logout-section">
                <a href="#"><i class="bi bi-box-arrow-right"></i> SAIR</a>
            </div>
        </div>
    
        <div class="main-content">
            
            <div class="vertical-stats">
                <div class="stat-item">
                    <h3>Dezembro</h3>
                    <div class="stat-value">50</div>
                </div>
                
                <div class="stat-item">
                    <h3>Novembro</h3>
                    <div class="stat-value">40</div>
                </div>
                
                <div class="stat-item">
                    <h3>Outubro</h3>
                    <div class="stat-value">30</div>
                </div>
                
            </div>
            
            
            <h1>Estatísticas</h1>
            <div class="chart-container">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
    
        
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="..\javascript\vendas.js"></script>
    </body>
    </html>