<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cantinho da Isa\Pedidos</title>
    <link rel="stylesheet" href="{{asset('css/adm/pedidos.css')}}">
    <link rel=" stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>

<body>
    <header>
        <a href="{{ route('home.index') }}" class="botao-link">
            CANTINHO DA ISA
        </a>


    </header>

    <div class="container">
        <aside class="sidebar">
            <div class="user-info">
                <label for="profile-img" class="profile-icon">
                    <i class="fas fa-user"></i>
                </label>
                <!-- type="file" CASO FOR COLOCAR FOTO PERFIL -->
                <input type="text" id="profile-img" accept="image/*" style="display:none">
                <input type="text" id="username" value="{{ Auth::user()->email }}" readonly />
            </div>
            <nav class="menu">
                <a href="{{ route('adm.dashboard') }}">
                    <button class="menu-btn">Inicial</button>
                </a>
                <a href="{{ route('adm.pedidos') }}">
                    <button class="menu-btn active">Pedidos</button>
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
                    <button class="menu-btn">Vendas</button>
                </a>
            </nav>
            <form method="POST" class="logout" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout">SAIR</button>
            </form>

        </aside>
        </aside>
        <main class="conteudo">
            <section class="admin-section">
                @foreach($pedidos as $pedido)
                    <div class="sales-record">
                        <p class="data-pedido">
                            <strong>Data:</strong> {{ $pedido->created_at->format('d/m/Y') }}
                            <span class="hora-pedido">Horário: {{ $pedido->created_at->format('H:i') }}</span>
                        </p>

                        <div class="user-sale">
                            <p>
                                <strong>{{ $pedido->usuario->name }}</strong>
                                <span
                                    class="badge bg-{{ $pedido->status == 'pago' ? 'success' : ($pedido->status == 'cancelado' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($pedido->status) }}
                                </span>
                            </p>

                            <ul>
                                @foreach($pedido->itens as $item)
                                    <li>
                                        {{ $item->produto->nome_produto }} -
                                        {{ $item->quantidade }} x R$ {{ number_format($item->preco_unitario, 2, ',', '.') }}
                                        @if($item->cor)
                                            (Cor: {{ $item->cor }})
                                        @endif
                                        @if($item->tamanho)
                                            (Tamanho: {{ $item->tamanho }})
                                        @endif
                                    </li>
                                @endforeach
                            </ul>

                            <div class="d-flex justify-content-between align-items-center">
                                <p class="total-pedido mb-0"><strong>Total:</strong> R$
                                    {{ number_format($pedido->total, 2, ',', '.') }}</p>

                                <div class="btn-group">
                                    @if($pedido->status == 'pendente')
                                        <form action="{{ route('adm.pedidos.alterar-status', $pedido->id_pedido) }}"
                                            method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="pago">
                                            <button type="submit" class="btn btn-sm btn-success">Marcar como Pago</button>
                                        </form>
                                        <form action="{{ route('adm.pedidos.alterar-status', $pedido->id_pedido) }}"
                                            method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="cancelado">
                                            <button type="submit" class="btn btn-sm btn-danger">Cancelar</button>
                                        </form>
                                    @endif
                                </div>
                            </div>

                            <!-- Endereço de entrega -->
                            <div class="mt-2">
                                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#endereco{{ $pedido->id_pedido }}">
                                    Ver endereço de entrega
                                </button>
                                <div class="collapse mt-2" id="endereco{{ $pedido->id_pedido }}">
                                    <div class="card card-body">
                                        @php
                                            $endereco = json_decode($pedido->endereco_entrega, true);
                                        @endphp
                                        <p class="mb-1"><strong>Endereço:</strong> {{ $endereco['rua'] }},
                                            {{ $endereco['numero'] }}</p>
                                        @if(!empty($endereco['complemento']))
                                            <p class="mb-1"><strong>Complemento:</strong> {{ $endereco['complemento'] }}</p>
                                        @endif
                                        <p class="mb-1"><strong>Bairro:</strong> {{ $endereco['bairro'] }}</p>
                                        <p class="mb-1"><strong>Cidade/UF:</strong>
                                            {{ $endereco['cidade'] }}/{{ $endereco['estado'] }}</p>
                                        <p class="mb-0"><strong>CEP:</strong> {{ $endereco['cep'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Paginação -->
                <div class="mt-4">
                    {{ $pedidos->links() }}
                </div>
            </section>
        </main>

    </div>

    <script src="algo isas.js"></script>
</body>

</html>