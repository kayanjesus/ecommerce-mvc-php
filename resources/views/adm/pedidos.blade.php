<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cantinho da Isa\Pedidos</title>
    <link rel="stylesheet" href="{{asset('css/pedidos.css')}}">
</head>
<body>
    <header>
        <h1>CANTINHO DA ISA</h1>
    </header>
</header>  <div class="container">
    <aside class="sidebar">
      <div class="user-info">
        <label for="profile-img" class="profile-icon">
          <i class="fas fa-user"></i>
        </label>
        <input type="file" id="profile-img" accept="image/*" style="display:none">
        <input type="text" id="username" value="Admin@admin.com" />
      </div>
      <nav class="menu">
        <button class="menu-btn">Inicial</button>
        <button class="menu-btn active">Pedidos</button>
        <button class="menu-btn"  a href="index.html">Produtos e estoque</button>
        <button class="menu-btn">Cadastro de produtos</button>
        <button class="menu-btn">Usuários cadastrados</button>
        <button class="menu-btn">Vendas</button>
      </nav>
      <button class="logout">SAIR</button>
    </aside><main class="conteudo">
        
        <main class="content">
            <section class="admin-section">
                
                <div class="sales-record">
                    <p><strong>Data:</strong> 00/00/0000</p>
                    <div class="user-sale">
                        <p><strong>Nome do usuário</strong></p>
                        <ul>
                            <li>Produto 1</li>
                            <li>Total R$ 00,00</li>
                        </ul>
                    </div>
                </div>
            </section>
        </main>
    </div>
    
    <script src="algo isas.js"></script>
</body>
</html>