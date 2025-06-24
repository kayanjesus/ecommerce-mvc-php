<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avaliar Pedido #{{ $pedido->id_pedido }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Custom styles for stars */
        .star-rating .fa-star {
            cursor: pointer;
            color: #ccc; /* Default star color */
        }
        .star-rating .fa-star.active {
            color: #ffc107; /* Active star color (yellow/gold) */
        }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0 bg-gray-100">
        <div class="w-full sm:max-w-4xl mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <div class="flex justify-between items-center mb-6 border-b pb-4">
                <h1 class="text-2xl font-bold text-gray-800">Avaliar Pedido #{{ $pedido->id_pedido }}</h1>
                <a href="{{ route('cliente.pedidos.verDetalhesPedido', $pedido->id_pedido) }}" class="flex items-center text-blue-600 hover:text-blue-800">
                    <i class="fas fa-arrow-left mr-2"></i> Voltar aos Detalhes do Pedido
                </a>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded" role="alert">
                    {{ session('error') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded" role="alert">
                    <p class="font-bold">Houve um erro com suas avaliações:</p>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('cliente.pedidos.avaliar.salvar', $pedido->id_pedido) }}" method="POST">
                @csrf
                @foreach($itensParaAvaliar as $index => $item)
                    <div class="bg-gray-50 p-6 rounded-lg shadow-sm mb-6">
                        <div class="flex items-center mb-4">
                            @if($item->produto && $item->produto->imagens->isNotEmpty())
                                @php
                                    $mainImage = $item->produto->imagens->firstWhere('principal', true) ?: $item->produto->imagens->first();
                                @endphp
                                @if($mainImage)
                                    <img src="{{ asset($mainImage->caminho) }}" alt="{{ $item->produto->nome_produto }}" class="w-20 h-20 object-cover rounded-md mr-4">
                                @else
                                    <div class="w-20 h-20 flex items-center justify-center bg-gray-200 rounded-md mr-4 text-gray-500">
                                        <i class="fas fa-image text-3xl"></i>
                                    </div>
                                @endif
                            @else
                                <div class="w-20 h-20 flex items-center justify-center bg-gray-200 rounded-md mr-4 text-gray-500">
                                    <i class="fas fa-image text-3xl"></i>
                                </div>
                            @endif
                            <div>
                                <h2 class="text-xl font-semibold text-gray-700">{{ $item->produto->nome_produto ?? 'Produto Desconhecido' }}</h2>
                                <p class="text-gray-600">Quantidade: {{ $item->quantidade }} | Preço Unitário: R$ {{ number_format($item->preco_unitario, 2, ',', '.') }}</p>
                                @if($item->cor || $item->tamanho)
                                    <p class="text-sm text-gray-500">({{ $item->cor->nome ?? '' }}{{ $item->cor && $item->tamanho ? ', ' : '' }}{{ $item->tamanho->nome ?? '' }})</p>
                                @endif
                            </div>
                        </div>

                        <input type="hidden" name="avaliacoes[{{ $index }}][id_item]" value="{{ $item->id_item }}">

                        <div class="mb-4">
                            <label for="nota_{{ $item->id_item }}" class="block text-gray-700 text-sm font-bold mb-2">Sua Avaliação (Estrelas):</label>
                            <div class="star-rating" id="star-rating-{{ $item->id_item }}" data-item-id="{{ $item->id_item }}">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="fa-solid fa-star text-2xl" data-value="{{ $i }}"></i>
                                @endfor
                            </div>
                            <input type="hidden" name="avaliacoes[{{ $index }}][nota]" id="nota_{{ $item->id_item }}" value="0" required>
                            @error("avaliacoes.$index.nota")
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="comentario_{{ $item->id_item }}" class="block text-gray-700 text-sm font-bold mb-2">Comentário (Opcional):</label>
                            <textarea name="avaliacoes[{{ $index }}][comentario]" id="comentario_{{ $item->id_item }}" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline resize-y" placeholder="Compartilhe sua experiência com o produto."></textarea>
                            @error("avaliacoes.$index.comentario")
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                @endforeach

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                        Enviar Avaliações
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.star-rating').forEach(function(ratingContainer) {
                const itemId = ratingContainer.dataset.itemId;
                const hiddenInput = document.getElementById('nota_' + itemId);
                const stars = ratingContainer.querySelectorAll('.fa-star');

                stars.forEach(function(star) {
                    star.addEventListener('click', function() {
                        const value = parseInt(this.dataset.value);
                        hiddenInput.value = value;

                        stars.forEach(function(s, i) {
                            if (i < value) {
                                s.classList.add('active');
                            } else {
                                s.classList.remove('active');
                            }
                        });
                    });

                    // Optional: Add hover effect
                    star.addEventListener('mouseover', function() {
                        const value = parseInt(this.dataset.value);
                        stars.forEach(function(s, i) {
                            if (i < value) {
                                s.classList.add('active');
                            } else {
                                s.classList.remove('active');
                            }
                        });
                    });

                    ratingContainer.addEventListener('mouseleave', function() {
                        const currentValue = parseInt(hiddenInput.value);
                        stars.forEach(function(s, i) {
                            if (i < currentValue) {
                                s.classList.add('active');
                            } else {
                                s.classList.remove('active');
                            }
                        });
                    });

                    // Set initial state if a value is already present (e.g., after validation error)
                    const initialValue = parseInt(hiddenInput.value);
                    if (initialValue > 0) {
                        stars.forEach(function(s, i) {
                            if (i < initialValue) {
                                s.classList.add('active');
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>
