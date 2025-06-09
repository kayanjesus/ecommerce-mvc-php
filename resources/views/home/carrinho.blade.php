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

        .checkout-form {
            display: none;
        }

        .selectable-row:hover {
            background-color: #f5f5f5;
            cursor: pointer;
        }

        .selected-row {
            background-color: #e3f2fd;
        }

        /* Adicione ao seu arquivo CSS */
        #select-all {
            margin-right: 5px;
        }

        [type="checkbox"]:indeterminate+span:before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            width: 10px;
            height: 2px;
            background-color: #26a69a;
            transform: translate(3px, -50%);
        }

        .select-all-label {
            font-weight: normal;
            color: #666;
            font-size: 0.9rem;
        }
    </style>
    <title>Carrinho</title>
</head>

<body>
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

            <form id="checkout-form" class="checkout-form" action="{{ route('pagamento.cep') }}" method="GET">
                <input type="hidden" name="tipo_checkout" id="tipo-checkout" value="selecionados">
                <input type="hidden" name="selected_items" id="selected-items">
            </form>

            <table class="striped">
                <thead>
                    <tr>
                        <th>
                            <label>
                                <input type="checkbox" id="select-all" class="filled-in" />
                                <span>Todos</span>
                            </label>
                        </th>
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
                            <tr class="selectable-row" data-id="{{ $item->id }}">
                                <td>
                                    <label>
                                        <input type="checkbox" class="filled-in item-checkbox" data-id="{{ $item->id }}" />
                                        <span></span>
                                    </label>
                                </td>
                                <td>
                                    {{-- AQUI ESTÁ A MUDANÇA: Use diretamente o atributo 'image' do item do carrinho --}}
                                    @if(isset($item->attributes['image']))
                                        <img src="{{ asset($item->attributes['image']) }}" width="70" class="responsive-img circle">
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
                    <span class="card-title">TOTAL SELECIONADO: <span id="total-selecionado">R$ 0,00</span></span>
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
                    <button id="finalizar-selecionados" class="btn waves-effect waves-light green">
                        Finalizar selecionados <i class="material-icons right">check</i>
                    </button>
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


            const selectAllCheckbox = document.getElementById('select-all');
            const checkboxes = document.querySelectorAll('.item-checkbox'); // Declare checkboxes aqui
            const finalizarBtn = document.getElementById('finalizar-selecionados'); // Declare finalizarBtn aqui
            const totalSelecionado = document.getElementById('total-selecionado'); // Declare totalSelecionado aqui
            const selectedItemsInput = document.getElementById('selected-items'); // Declare selectedItemsInput aqui
            const rows = document.querySelectorAll('.selectable-row'); // Declare rows aqui
            const checkoutForm = document.getElementById('checkout-form'); // Declare checkoutForm aqui

            // Dados do carrinho para cálculo
            const cartItems = @json($itens->mapWithKeys(function ($item) {
                return [$item->id => $item];
            }));

            selectAllCheckbox.addEventListener('change', function () {
                const isChecked = this.checked;

                checkboxes.forEach(checkbox => {
                    checkbox.checked = isChecked;
                    // Dispara o evento change manualmente para atualizar a UI
                    const event = new Event('change');
                    checkbox.dispatchEvent(event);
                });
            });

            // Atualiza o "Selecionar todos" quando itens individuais são alterados
            function updateSelectAllCheckbox() {
                const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
                const someChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);

                selectAllCheckbox.checked = allChecked;
                selectAllCheckbox.indeterminate = someChecked && !allChecked;
            }

            // Modifique a função updateSelection para chamar updateSelectAllCheckbox
            function updateSelection() {
                const selectedIds = [];
                let total = 0;

                checkboxes.forEach(checkbox => {
                    const itemId = checkbox.dataset.id;
                    const row = checkbox.closest('tr');

                    if (checkbox.checked) {
                        selectedIds.push(itemId);
                        row.classList.add('selected-row');

                        // Calcula subtotal
                        const item = cartItems[itemId];
                        total += item.price * item.quantity;
                    } else {
                        row.classList.remove('selected-row');
                    }
                });

                // Atualiza UI
                totalSelecionado.textContent = 'R$ ' + total.toFixed(2).replace('.', ',');
                finalizarBtn.disabled = selectedIds.length === 0;
                selectedItemsInput.value = JSON.stringify(selectedIds);

                // Atualiza o checkbox "Selecionar todos"
                updateSelectAllCheckbox();
            }


            // Inicializa componentes do Materialize
            M.AutoInit();

            // Event listeners
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateSelection);
            });

            // Clica na linha para selecionar
            rows.forEach(row => {
                row.addEventListener('click', (e) => {
                    // Não dispara se clicou em um link ou botão
                    if (e.target.tagName === 'A' || e.target.tagName === 'BUTTON' ||
                        e.target.tagName === 'INPUT' || e.target.closest('a') ||
                        e.target.closest('button') || e.target.closest('input')) {
                        return;
                    }

                    const checkbox = row.querySelector('.item-checkbox');
                    checkbox.checked = !checkbox.checked;
                    checkbox.dispatchEvent(new Event('change'));
                });
            });

            // Finalizar pedido com itens selecionados
            finalizarBtn.addEventListener('click', () => {
                const selectedItems = JSON.parse(selectedItemsInput.value);

                if (selectedItems.length === 0) {
                    M.toast({ html: 'Selecione pelo menos um item para finalizar', classes: 'red' });
                    return;
                }

                // Verifica se os itens selecionados ainda estão no carrinho
                const validItems = selectedItems.filter(itemId => {
                    return cartItems.hasOwnProperty(itemId);
                });

                if (validItems.length === 0) {
                    M.toast({ html: 'Os itens selecionados não estão mais disponíveis', classes: 'red' });
                    return;
                }

                // Atualiza a lista com apenas os itens válidos
                selectedItemsInput.value = JSON.stringify(validItems);

                // Define o tipo de checkout como 'selecionados'
                document.getElementById('tipo-checkout').value = 'selecionados';

                // Submete o formulário
                checkoutForm.submit();
            });

            // Inicializa a seleção
            updateSelection();
        });

    </script>
</body>

</html>