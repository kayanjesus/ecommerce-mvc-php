<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <title>Carrinho</title>
</head>

<body>

    @if ($mensagem = Session::get('sucesso'))


        <div class="card green darken-1">
            <div class="card-content white-text">
                <span class="card-title">Parabens!</span>
                <p>{{ $mensagem }}</p>
            </div>
        </div>
    @endif

    @if ($mensagem = Session::get('aviso'))


        <div class="card blue">
            <div class="card-content white-text">
                <span class="card-title">Tudo bem!</span>
                <p>{{ $mensagem }}</p>
            </div>
        </div>

    @endif



    @if ($itens->count() == 0)

        <div class="card orange">
            <div class="card-content white-text">
                <span class="card-title">Seu carrinho esta vazio!</span>
                <p>Aproveite nossas promoções!</p>
            </div>
        </div>
        <div class="row container center">

            <a href="{{ '/' }}" class="btn waves-effect waves-light blue">Continuar comprando<i
                    class="material-icons right">arrow_back</i></a>
        </div>

    @else

        <h5>Seu carrinho possui {{ $itens->count() }} produtos. </h5>
        <table class="striped">
            <thead>
                <tr>
                    <th></th> <!-- foto do produto -->
                    <th>Nome</th>
                    <th>cor</th>
                    <th>tamanho</th>
                    <th>Quantidade</th>
                    <th>Preço</th>
                    <th></th> <!-- botão apagar/editar-->
                </tr>
            </thead>

            <tbody>
                @foreach ($itens as $item)
                    <tr>
                        <td><img src="{{ $item->attributes->image }}" alt="" width="70" class="responsive-img circle"></td>
                        <td>{{ $item->name }}</td>
                        <td>R$ {{ number_format($item->price, 2, ',', '.') }}</td>

                        {{-- BTN atualizar --}}
                        <form action="{{ route('home.atualizacarrinho') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{ $item->id }}">
                            <td><input style="width: 40px; font-weight:900;" min="1" class="white center" type="number"
                                    name="quantity" value="{{ $item->quantity }}"></td>
                            <td><button class="btn-floating waves-effect waves-light orange"><i
                                        class="material-icons">refresh</i></button>
                        </form>

                        {{-- BTN remover --}}
                        <form action="{{ route('home.removecarrinho') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{ $item->id }}">
                            <button class="btn-floating waves-effect waves-light red"><i
                                    class="material-icons">delete</i></button>
                        </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="card orange">
            <div class="card-content white-text">
                <span class="card-title">TOTAL R$ {{ number_format(\Cart::getTotal(), 2, ',', '.') }}</span>
                <p>Pague em 6x sem juros!</p>
            </div>
        </div>

        <div class="row container center">

            <a href="{{ '/' }}" class="btn waves-effect waves-light blue">Continuar comprando<i
                    class="material-icons right">arrow_back</i></a>

            <a href="{{ route('home.limparcarrinho') }}" class="btn waves-effect waves-light blue">Limpar carrinho<i
                    class="material-icons right">clear</i></a>

            <button class="btn waves-effect waves-light green">Finalizar pedido<i
                    class="material-icons right">check</i></button>
        </div>
    @endif





</body>

</html>