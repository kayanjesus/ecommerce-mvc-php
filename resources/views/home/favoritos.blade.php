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
    <style>
        .favoritos-container {
            padding: 20px;
        }

        .product-image {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 4px;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .total-card {
            margin-top: 20px;
        }

        .buttons-container {
            margin-top: 30px;
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .empty-favorites {
            text-align: center;
            padding: 40px 0;
        }
    </style>
    <title>Meus Favoritos</title>
</head>

<body>
    <div class="favoritos-container">
        <!-- Mensagens de feedback -->
        @if ($mensagem = Session::get('sucesso'))
            <div class="card green darken-1">
                <div class="card-content white-text">
                    <span class="card-title">Sucesso!</span>
                    <p>{{ $mensagem }}</p>
                </div>
            </div>
        @endif

        @if ($mensagem = Session::get('aviso'))
            <div class="card blue">
                <div class="card-content white-text">
                    <span class="card-title">Informação</span>
                    <p>{{ $mensagem }}</p>
                </div>
            </div>
        @endif

        @if ($mensagem = Session::get('erro'))
            <div class="card red">
                <div class="card-content white-text">
                    <span class="card-title">Atenção</span>
                    <p>{{ $mensagem }}</p>
                </div>
            </div>
        @endif

        <!-- Conteúdo principal -->
        @if ($itens->count() == 0)
            <div class="empty-favorites">
                <div class="card orange">
                    <div class="card-content white-text">
                        <span class="card-title">Seus favoritos estão vazios!</span>
                        <p>Aproveite nossas promoções!</p>
                    </div>
                </div>
                <div class="buttons-container">
                    <a href="{{ '/' }}" class="btn waves-effect waves-light blue">
                        <i class="material-icons left">arrow_back</i> Continuar comprando
                    </a>
                </div>
            </div>
        @else
            <h4 class="center-align">Meus Favoritos</h4>
            <p class="center-align grey-text">Você tem {{ $itens->count() }} iten(s) favoritado(s)</p>

            <div class="responsive-table">
                <table class="highlight">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Nome</th>
                            <th>Preço</th>
                            <th>Ações</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($itens as $item)
                            <tr>
                                <td>
                                    <img src="{{ asset($item->attributes->image) }}" alt="{{ $item->name }}"
                                        class="product-image">
                                </td>
                                <td>{{ $item->name }}</td>
                                <td>R$ {{ number_format($item->price, 2, ',', '.') }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <form action="{{ route('home.removefavoritos') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $item->id }}">
                                            <button type="submit" class="btn-floating waves-effect waves-light red tooltipped"
                                                data-position="top" data-tooltip="Remover">
                                                <i class="material-icons">delete</i>
                                            </button>
                                        </form>
                                        <!-- <a href="{{ route('home.details', $item->id) }}"
                                                    class="btn-floating waves-effect waves-light blue tooltipped" data-position="top"
                                                    data-tooltip="Ver detalhes">
                                                    <i class="material-icons">visibility</i>
                                                </a> -->
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="total-card card orange">
                <div class="card-content white-text">
                    <div class="row valign-wrapper">
                        <div class="col s8">
                            <span class="card-title">Total</span>
                            <p>Pague em até 6x sem juros!</p>
                        </div>
                        <div class="col s4 right-align">
                            <span class="card-title">R$ {{ number_format($total, 2, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="buttons-container">
                <a href="{{ '/' }}" class="btn waves-effect waves-light blue">
                    <i class="material-icons left">arrow_back</i> Continuar comprando
                </a>

                <a href="{{ route('home.limparfavoritos') }}" class="btn waves-effect waves-light red">
                    <i class="material-icons left">clear_all</i> Limpar favoritos
                </a>
            </div>
        @endif
    </div>

    <script>
        // Inicializa tooltips
        document.addEventListener('DOMContentLoaded', function () {
            var elems = document.querySelectorAll('.tooltipped');
            M.Tooltip.init(elems);
        });
    </script>
</body>

</html>