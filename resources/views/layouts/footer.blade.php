<link rel="stylesheet" href="{{asset('css/home.css')}}">


@yield('content')

<footer>
    <section class="top-footer">
        <h3>Cantinho da Isa</h3>
        <p>Crianças crescem rápido, não é mesmo? Em pouco tempo, as roupinhas vão ficando mais curtas, e é preciso
            renovar os guarda-roupas. Aqui no Cantinho da Isa, temos o melhor vestuário para os pequenos, e com os
            menores preços. Venha conferir. </p>
    </section>
    <div class="footer-container">
        <div class="footer-column">
            <h3>Institucional</h3>
            <ul>
                <li><a href="#">Quem Somos</a></li>
                <li><a href="#">Política de Privacidade</a></li>
                <li><a href="#">Troca e Devolução</a></li>
                <li><a href="#">Política de Entrega</a></li>
                <li><a href="#">Política de Pagamento</a></li>
                <li><a href="#">Ajuda</a></li>
            </ul>
        </div>
        <div class="footer-column">
            <h3>Atendimento</h3>
            <p>( xx ) xxxx-xxxx</p>
            <p>De segunda-feira a sexta-feira:<br>12h ás 18h</p>
        </div>
        <div class="footer-column">
            <h3>Compre Seguro</h3>
            <p>Suas compras são processadas com segurança através do <strong>PagSeguro</strong>, garantindo proteção
                total de seus dados e tranquilidade nas transações.</p>
            <ul class="payment-methods">
                <li><img src="{{ asset('img/pagseguro.webp') }}" alt="PagSeguro"></li>
                <li><img src="{{ asset('img/mastercard.webp') }}" alt="Mastercard"></li>
                <li><img src="{{ asset('img/pix.webp') }}" alt="Pix"></li>
            </ul>
        </div>


    </div>
</footer>