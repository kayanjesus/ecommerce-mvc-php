<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        .color-preview {
            width: 15px;
            height: 15px;
            display: inline-block;
            margin-right: 5px;
            border-radius: 50%;
        }

        .quantity-input {
            width: 50px;
            text-align: center;
        }
    </style>
    <title>Carrinho</title>
</head>

<body>
    <!-- Mensagens de status -->
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
                <span class="card-title">Aviso</span>
                <p>{{ $mensagem }}</p>
            </div>
        </div>
    @endif

    @if ($mensagem = Session::get('erro'))
        <div class="card red">
            <div class="card-content white-text">
                <span class="card-title">Erro</span>
                <p>{{ $mensagem }}</p>
            </div>
        </div>
    @endif

    @if ($itens->count() == 0)
        <div class="card orange">
            <div class="card-content white-text">
                <span class="card-title">Seu carrinho está vazio!</span>
                <p>Aproveite nossas promoções!</p>
            </div>
        </div>
        <div class="row container center">
            <a href="{{ '/' }}" class="btn waves-effect waves-light blue">
                Continuar comprando<i class="material-icons right">arrow_back</i>
            </a>
        </div>
    @else
        <div class="container">
            <h5>Seu carrinho possui {{ $itens->count() }} produtos</h5>

            <table class="striped">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Nome</th>
                        <th>Cor</th>
                        <th>Tamanho</th>
                        <th>Quantidade</th>
                        <th>Preço</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($itens as $item)
                            <tr>
                                <td>
                                    @php
                                        $produto = App\Models\Produto::find(explode('-', $item->id)[0]);
                                        $mainImage = $produto && $produto->imagens->isNotEmpty()
                                            ? ($produto->imagens->where('principal', true)->first() ?? $produto->imagens->first())
                                            : null;
                                    @endphp

                                    @if($mainImage)
                                        <img src="{{ asset($mainImage->caminho) }}" width="70" class="responsive-img circle">
                                    @else
                                        <i class="material-icons">image</i>
                                    @endif
                                </td>

                                <td>{{ $item->name }}</td>

                                <td>
                                    @if(isset($item->attributes['cor_id']))
                                        @php $cor = App\Models\Cor::find($item->attributes['cor_id']); @endphp
                                        <span class="color-preview" style="background-color: {{ $cor->codigo_hex ?? '#ccc' }}"></span>
                                        {{ $cor->nome ?? 'N/A' }}
                                    @else
                                        <span class="color-preview" style="background-color: #ccc"></span>
                                        N/A
                                    @endif
                                </td>

                                <td>
                                    {{ isset($item->attributes['tamanho_id'])
                        ? (App\Models\Tamanho::find($item->attributes['tamanho_id'])->nome ?? 'N/A')
                        : 'N/A' }}
                                </td>

                                <td>
                                    <form action="{{ route('home.atualizacarrinho') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $item->id }}">
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1"
                                            class="quantity-input">
                                    </form>
                                </td>

                                <td>R$ {{ number_format($item->price, 2, ',', '.') }}</td>

                                <td>
                                    <form action="{{ route('home.removecarrinho') }}" method="POST" style="display: inline;">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $item->id }}">
                                        <button type="submit" class="btn-floating waves-effect waves-light red">
                                            <i class="material-icons">delete</i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="card orange">
                <div class="card-content white-text">
                    <span class="card-title">TOTAL: R$ {{ number_format(\Cart::getTotal(), 2, ',', '.') }}</span>
                    <p>Pague em até 6x sem juros!</p>
                </div>
            </div>

            <div class="row center">
                <div class="col s12 m4">
                    <a href="{{ '/' }}" class="btn waves-effect waves-light blue">
                        Continuar comprando<i class="material-icons right">arrow_back</i>
                    </a>
                </div>
                <div class="col s12 m4">
                    <a href="{{ route('home.limparcarrinho') }}" class="btn waves-effect waves-light blue">
                        Limpar carrinho<i class="material-icons right">clear</i>
                    </a>
                </div>


                <div class="col s12 m4">
                    <a href="{{ route('pagamento.cep') }}" class="btn waves-effect waves-light green">Finalizar pedido<i
                            class="material-icons right">check</i>
                    </a>
                </div>

            </div>
        </div>
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Atualiza quantidade quando o input muda
            document.querySelectorAll('.quantity-input').forEach(input => {
                input.addEventListener('change', function () {
                    this.closest('form').submit();
                });
            });

            // Inicializa componentes do Materialize
            M.AutoInit();
        });
    </script>
</body>

</html>