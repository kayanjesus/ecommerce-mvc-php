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
                <li><a href="{{ route('paginas.quem-somos') }}">Quem Somos</a></li>
                <li><a href="{{ route('paginas.politica-privacidade') }}">Política de Privacidade</a></li>
                <li><a href="{{ route('paginas.troca-devolucao') }}">Troca e Devolução</a></li>
                <li><a href="{{ route('paginas.politica-entrega') }}">Política de Entrega</a></li>
                <li><a href="{{ route('paginas.politica-pagamento') }}">Política de Pagamento</a></li>
                <li><a href="{{ route('paginas.ajuda') }}">Ajuda</a></li>
            </ul>
        </div>
        <div class="footer-column">
            <h3>Atendimento</h3>
            <p><i class="fas fa-phone me-2"></i>(11) 99999-9999</p>
            <p><i class="fas fa-envelope me-2"></i>contato@cantinhodaisa.com.br</p>
            <p><i class="fab fa-whatsapp me-2" style="color: #25D366;"></i> (11) 99999-9999</p>
            <p><i class="far fa-clock me-2"></i>Segunda a Sexta<br>12h às 18h</p>
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
    <div class="text-center p-3" style="background-color: #f5f5f5; border-top: 1px solid #ddd;">
        <p class="mb-0">© {{ date('Y') }} Cantinho da Isa. Todos os direitos reservados.</p>
    </div>
</footer>