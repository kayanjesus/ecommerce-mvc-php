<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avaliar Pedido #{{ $pedido->id_pedido }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        .star-rating .fa-star {
            cursor: pointer;
            color: #d1d5db;
            transition: all 0.2s ease;
        }
        .star-rating .fa-star.active,
        .star-rating .fa-star:hover,
        .star-rating:hover .fa-star.active {
            color: #fbbf24;
            transform: scale(1.1);
        }
        .star-rating .fa-star:hover ~ .fa-star {
            color: #d1d5db;
        }
        .product-card {
            transition: all 0.3s ease;
            border-left: 4px solid #9b2a2a;
        }
        .product-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div class="min-h-screen py-8 px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Avaliar Pedido</h1>
                        <p class="text-gray-600 mt-2">Pedido #{{ $pedido->id_pedido }} • {{ $pedido->data_pedido->format('d/m/Y') }}</p>
                        @if($itensParaAvaliar->count() > 1)
                            <p class="text-sm text-gray-500 mt-1">
                                <i class="fas fa-box-open mr-1"></i>
                                {{ $itensParaAvaliar->count() }} produtos para avaliar
                            </p>
                        @endif
                    </div>
                    <a href="{{ route('cliente.pedidos.verDetalhesPedido', $pedido->id_pedido) }}" 
                       class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Voltar ao pedido
                    </a>
                </div>
            </div>

            <!-- Messages -->
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 rounded-lg p-4 mb-6 flex items-center" role="alert">
                    <i class="fas fa-check-circle text-green-500 mr-3 text-xl"></i>
                    <div>
                        <p class="font-semibold">{{ session('success') }}</p>
                    </div>
                </div>
            @endif
            
            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 rounded-lg p-4 mb-6 flex items-center" role="alert">
                    <i class="fas fa-exclamation-circle text-red-500 mr-3 text-xl"></i>
                    <div>
                        <p class="font-semibold">{{ session('error') }}</p>
                    </div>
                </div>
            @endif
            
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 rounded-lg p-4 mb-6" role="alert">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-exclamation-triangle text-red-500 mr-3 text-xl"></i>
                        <p class="font-semibold">Atenção</p>
                    </div>
                    <ul class="mt-2 list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('cliente.pedidos.avaliar.salvar', $pedido->id_pedido) }}" method="POST" id="avaliacaoForm">
                @csrf
                
                <!-- Progress Indicator -->
                @if($itensParaAvaliar->count() > 1)
                    <div class="mb-8 bg-white rounded-xl shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Progresso da Avaliação</h2>
                        <div class="flex items-center">
                            <div class="flex-1">
                                <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div id="progressBar" class="h-full bg-blue-600 transition-all duration-500" style="width: 0%"></div>
                                </div>
                            </div>
                            <span id="progressText" class="ml-4 text-sm font-medium text-gray-700">0/{{ $itensParaAvaliar->count() }}</span>
                        </div>
                    </div>
                @endif

                <!-- Products List -->
                <div class="space-y-6 mb-8">
                    @foreach($itensParaAvaliar as $index => $item)
                        <div class="product-card bg-white rounded-xl shadow-sm p-6">
                            <!-- Product Header -->
                            <div class="flex items-start mb-6 pb-6 border-b">
                                @if($item->produto && $item->produto->imagens->isNotEmpty())
                                    @php
                                        $mainImage = $item->produto->imagens->firstWhere('principal', true) ?: $item->produto->imagens->first();
                                    @endphp
                                    @if($mainImage)
                                        <img src="{{ asset($mainImage->caminho) }}" 
                                             alt="{{ $item->produto->nome_produto }}" 
                                             class="w-24 h-24 object-cover rounded-lg mr-4">
                                    @else
                                        <div class="w-24 h-24 flex items-center justify-center bg-gray-100 rounded-lg mr-4 text-gray-400">
                                            <i class="fas fa-image text-3xl"></i>
                                        </div>
                                    @endif
                                @else
                                    <div class="w-24 h-24 flex items-center justify-center bg-gray-100 rounded-lg mr-4 text-gray-400">
                                        <i class="fas fa-image text-3xl"></i>
                                    </div>
                                @endif
                                
                                <div class="flex-1">
                                    <h3 class="text-xl font-semibold text-gray-900">{{ $item->produto->nome_produto ?? 'Produto Desconhecido' }}</h3>
                                    <div class="flex flex-wrap gap-4 mt-2 text-sm text-gray-600">
                                        <span class="inline-flex items-center">
                                            <i class="fas fa-hashtag mr-2"></i>
                                            Item #{{ $item->id_item }}
                                        </span>
                                        <span class="inline-flex items-center">
                                            <i class="fas fa-box mr-2"></i>
                                            Quantidade: {{ $item->quantidade }}
                                        </span>
                                        <span class="inline-flex items-center">
                                            <i class="fas fa-tag mr-2"></i>
                                            R$ {{ number_format($item->preco_unitario, 2, ',', '.') }}
                                        </span>
                                        @if($item->cor || $item->tamanho)
                                            <span class="inline-flex items-center">
                                                <i class="fas fa-palette mr-2"></i>
                                                {{ $item->cor->nome ?? '' }}{{ $item->cor && $item->tamanho ? ' • ' : '' }}{{ $item->tamanho->nome ?? '' }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="avaliacoes[{{ $index }}][id_item]" value="{{ $item->id_item }}">

                            <!-- Rating -->
                            <div class="mb-6">
                                <label class="block text-gray-900 font-medium mb-3">
                                    <i class="fas fa-star mr-2 text-yellow-500"></i>
                                    Sua avaliação
                                </label>
                                <div class="flex flex-col sm:flex-row sm:items-center gap-6">
                                    <div class="star-rating flex gap-1" id="star-rating-{{ $item->id_item }}" data-item-id="{{ $item->id_item }}">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="fa-solid fa-star text-3xl" data-value="{{ $i }}" title="{{ $i }} estrela{{ $i > 1 ? 's' : '' }}"></i>
                                        @endfor
                                    </div>
                                    <div>
                                        <input type="hidden" name="avaliacoes[{{ $index }}][nota]" id="nota_{{ $item->id_item }}" value="0" required>
                                        <div id="rating-text-{{ $item->id_item }}" class="text-gray-500 text-sm">
                                            Selecione uma nota
                                        </div>
                                        @error("avaliacoes.$index.nota")
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Comment -->
                            <div>
                                <label for="comentario_{{ $item->id_item }}" class="block text-gray-900 font-medium mb-3">
                                    <i class="fas fa-comment mr-2 text-blue-500"></i>
                                    Comentário (opcional)
                                </label>
                                <textarea name="avaliacoes[{{ $index }}][comentario]" 
                                          id="comentario_{{ $item->id_item }}" 
                                          rows="3" 
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none" 
                                          placeholder="Compartilhe sua experiência com este produto..."></textarea>
                                <div class="flex justify-between items-center mt-2">
                                    <p class="text-sm text-gray-500">Sua avaliação ajuda outros clientes</p>
                                    <span id="char-count-{{ $item->id_item }}" class="text-sm text-gray-400">0/500</span>
                                </div>
                                @error("avaliacoes.$index.comentario")
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Submit -->
                <div class="bg-white rounded-xl shadow-sm p-6 sticky bottom-6 border border-gray-200">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <p class="text-gray-900 font-medium">
                                <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                                Avaliações importantes
                            </p>
                            <p class="text-sm text-gray-600 mt-1">
                                Sua avaliação será publicada e ajudará outros clientes
                            </p>
                        </div>
                        <button type="submit" 
                                id="submitBtn"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-8 rounded-lg transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 inline-flex items-center justify-center min-w-[200px]">
                            <i class="fas fa-paper-plane mr-3"></i>
                            Enviar Avaliações
                            @if($itensParaAvaliar->count() > 1)
                                <span class="ml-2 bg-blue-800 text-xs px-2 py-1 rounded-full">
                                    {{ $itensParaAvaliar->count() }}
                                </span>
                            @endif
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const items = document.querySelectorAll('.product-card');
            const progressBar = document.getElementById('progressBar');
            const progressText = document.getElementById('progressText');
            const submitBtn = document.getElementById('submitBtn');
            let ratedItems = 0;
            
            // Rating labels
            const ratingLabels = {
                1: "Péssimo",
                2: "Ruim", 
                3: "Regular",
                4: "Bom",
                5: "Excelente"
            };

            // Initialize progress
            updateProgress();

            // Star rating functionality
            document.querySelectorAll('.star-rating').forEach(function(ratingContainer) {
                const itemId = ratingContainer.dataset.itemId;
                const hiddenInput = document.getElementById('nota_' + itemId);
                const stars = ratingContainer.querySelectorAll('.fa-star');
                const ratingText = document.getElementById('rating-text-' + itemId);

                stars.forEach(function(star) {
                    star.addEventListener('click', function() {
                        const value = parseInt(this.dataset.value);
                        hiddenInput.value = value;
                        
                        // Update stars
                        stars.forEach(function(s, i) {
                            s.classList.toggle('active', i < value);
                        });
                        
                        // Update rating text
                        ratingText.textContent = ratingLabels[value];
                        ratingText.className = 'font-medium text-yellow-600 text-sm';
                        
                        // Update progress
                        updateProgress();
                    });

                    // Hover effect
                    star.addEventListener('mouseover', function() {
                        const hoverValue = parseInt(this.dataset.value);
                        stars.forEach(function(s, i) {
                            s.classList.toggle('active', i < hoverValue);
                        });
                    });

                    star.addEventListener('mouseout', function() {
                        const currentValue = parseInt(hiddenInput.value);
                        stars.forEach(function(s, i) {
                            s.classList.toggle('active', i < currentValue);
                        });
                    });
                });
            });

            // Character counter for comments
            document.querySelectorAll('textarea').forEach(function(textarea) {
                const itemId = textarea.id.split('_')[1];
                const charCount = document.getElementById('char-count-' + itemId);
                
                textarea.addEventListener('input', function() {
                    const length = this.value.length;
                    charCount.textContent = `${length}/500`;
                    
                    if (length > 450) {
                        charCount.classList.remove('text-gray-400');
                        charCount.classList.add('text-yellow-600');
                    } else if (length > 495) {
                        charCount.classList.remove('text-yellow-600');
                        charCount.classList.add('text-red-500');
                    } else {
                        charCount.classList.remove('text-yellow-600', 'text-red-500');
                        charCount.classList.add('text-gray-400');
                    }
                });
            });

            // Progress update function
            function updateProgress() {
                if (!progressBar) return;
                
                const allHiddenInputs = document.querySelectorAll('input[type="hidden"][name*="[nota]"]');
                ratedItems = Array.from(allHiddenInputs).filter(input => parseInt(input.value) > 0).length;
                
                const totalItems = allHiddenInputs.length;
                const percentage = totalItems > 0 ? Math.round((ratedItems / totalItems) * 100) : 0;
                
                progressBar.style.width = percentage + '%';
                progressText.textContent = `${ratedItems}/${totalItems} avaliados`;
                
                // Update submit button state
                if (ratedItems === totalItems && totalItems > 0) {
                    submitBtn.innerHTML = '<i class="fas fa-check-circle mr-3"></i>Todos avaliados! Enviar';
                    submitBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                    submitBtn.classList.add('bg-green-600', 'hover:bg-green-700');
                } else {
                    submitBtn.innerHTML = '<i class="fas fa-paper-plane mr-3"></i>Enviar Avaliações' + 
                        (totalItems > 1 ? `<span class="ml-2 bg-blue-800 text-xs px-2 py-1 rounded-full">${totalItems}</span>` : '');
                    submitBtn.classList.remove('bg-green-600', 'hover:bg-green-700');
                    submitBtn.classList.add('bg-blue-600', 'hover:bg-blue-700');
                }
            }

            // Form validation
            document.getElementById('avaliacaoForm').addEventListener('submit', function(e) {
                const unratedItems = document.querySelectorAll('input[type="hidden"][name*="[nota]"]:not([value]), input[type="hidden"][name*="[nota]"][value="0"]');
                
                if (unratedItems.length > 0) {
                    e.preventDefault();
                    
                    // Highlight unrated items
                    unratedItems.forEach(input => {
                        const card = input.closest('.product-card');
                        card.style.borderLeftColor = '#ef4444';
                        card.classList.add('animate-pulse');
                        
                        setTimeout(() => {
                            card.classList.remove('animate-pulse');
                        }, 2000);
                    });
                    
                    // Scroll to first unrated
                    const firstUnrated = unratedItems[0].closest('.product-card');
                    firstUnrated.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    
                    // Show alert
                    alert(`Você ainda tem ${unratedItems.length} produto${unratedItems.length > 1 ? 's' : ''} sem avaliação. Por favor, avalie todos os produtos antes de enviar.`);
                }
            });

            // Initialize existing ratings (in case of form errors)
            document.querySelectorAll('input[type="hidden"][name*="[nota]"]').forEach(function(input) {
                if (parseInt(input.value) > 0) {
                    const itemId = input.id.split('_')[1];
                    const stars = document.querySelectorAll(`#star-rating-${itemId} .fa-star`);
                    const ratingText = document.getElementById(`rating-text-${itemId}`);
                    const value = parseInt(input.value);
                    
                    stars.forEach(function(s, i) {
                        s.classList.toggle('active', i < value);
                    });
                    
                    if (ratingText) {
                        ratingText.textContent = ratingLabels[value];
                        ratingText.className = 'font-medium text-yellow-600 text-sm';
                    }
                }
            });
            
            // Initialize character counters
            document.querySelectorAll('textarea').forEach(textarea => {
                textarea.dispatchEvent(new Event('input'));
            });
        });
    </script>
</body>
</html>