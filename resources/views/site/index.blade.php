<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOME</title>
    <link rel="stylesheet" href="{{ asset('css/indexphp.css') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
</head>

<body>
    <nav>
        <ul id="menu-h">
            <li><a href="index.php">Home <i class="bi bi-house-door-fill"></i> </a></li>
            <li><a href="sobre.html">Sobre <i class="bi bi-file-earmark-person"></i></a></li>
            <li><a href="links.html">Links <i class="bi bi-chat-left-heart-fill"></i></a></li>
            <li><a href="{{ route('register') }}">Entrar <i class="bi bi-person-circle"></i></a></li>
        </ul>
    </nav>

    <section id="hero">
        <div class="hero">
            <div class="overlay"></div>
            <div class="content">
                <h1>Bem vindo a LOVETEC</h1>
                <p>A sua locadora de veículos preferida.</p>
                <a href="php/tela-de-login.php" class="btn">Alugar</a>
            </div>
        </div>
    </section>

  <!--  <section id="cards">
        <div class="card-container">
            <div class="card">
                <img src="img/onix-preto.png" alt="">
                <div class="card-content">
                    <h3>Onix Preto</h3>
                    <p>Pelo volante do Onix 2025 você tem controle total sobre o carro, podendo acionar a velocidade de
                        cruzeiro, o assistente de partida em aclivee câmera de ré; além disso, você ainda navega por
                        diversas outras funções integradas pelo MyLink e pelo app myChevrolet.</p>
                    <a href="php/tela-de-login.php" class="btn">Alugar</a>
                </div>
            </div>
            <div class="card">
                <img src="img/onix-vermelho.png" alt="">
                <div class="card-content">
                    <h3>Onix Vermelho</h3>
                    <p>Pelo volante do Onix 2025 você tem controle total sobre o carro, podendo acionar a velocidade de
                        cruzeiro, o assistente de partida em aclivee câmera de ré; além disso, você ainda navega por
                        diversas outras funções integradas pelo MyLink e pelo app myChevrolet.</p>
                    <a href="php/tela-de-login.php" class="btn">Alugar</a>
                </div>
            </div>
            <div class="card">
                <img src="img/onix-amarelo.png" alt="">
                <div class="card-content">
                    <h3>Onix Amarelo</h3>
                    <p>Pelo volante do Onix 2025 você tem controle total sobre o carro, podendo acionar a velocidade de
                        cruzeiro, o assistente de partida em aclivee câmera de ré; além disso, você ainda navega por
                        diversas outras funções integradas pelo MyLink e pelo app myChevrolet.</p>
                    <a href="php/tela-de-login.php" class="btn">Alugar</a>
                </div>
            </div>
        </div>
    </section> -->


</body>

</html>
<?php ?>