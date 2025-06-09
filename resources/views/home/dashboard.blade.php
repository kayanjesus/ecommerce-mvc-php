<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p>Olá {{ Auth::user()->name }}</p>
                    <br>

                    <div class="mb-8 flex space-x-4">
                        <a href="{{ route('home.dashboard', ['show' => 'pedidos']) }}"
                           class="px-4 py-2 rounded-md font-semibold text-sm
                                  @if($currentView === 'pedidos') bg-indigo-600 text-white @else bg-gray-200 text-gray-700 hover:bg-gray-300 @endif">
                            <i class="fas fa-box mr-2"></i> Meus Pedidos
                        </a>
                        <a href="{{ route('home.dashboard', ['show' => 'favoritos']) }}"
                           class="px-4 py-2 rounded-md font-semibold text-sm
                                  @if($currentView === 'favoritos') bg-indigo-600 text-white @else bg-gray-200 text-gray-700 hover:bg-gray-300 @endif">
                            <i class="fas fa-heart mr-2"></i> Favoritos
                        </a>
                    </div>

                    <div id="content-area">
                        @if($currentView === 'pedidos')
                            <div class="mb-8">
                                <h3 class="font-semibold text-lg text-gray-800 leading-tight mb-4">Seus Pedidos Recentes</h3>

                                @if($pedidos->isEmpty())
                                    <p class="text-gray-600">Você ainda não fez nenhum pedido.</p>
                                @else
                                    <div class="space-y-6">
                                        @foreach($pedidos as $pedido)
                                            <div class="border rounded-lg p-4 bg-gray-50 shadow-sm">
                                                <div class="flex justify-between items-center mb-3">
                                                    <h4 class="font-bold text-md text-gray-800">Pedido #{{ $pedido->id_pedido }}</h4>
                                                    <span class="text-sm text-gray-600">
                                                        Data: {{ \Carbon\Carbon::parse($pedido->created_at)->format('d/m/Y H:i') }}
                                                    </span>
                                                </div>
                                                <p class="mb-2">
                                                    Status do Pedido:
                                                    <span class="font-semibold
                                                                 @if($pedido->status === 'pago') text-green-600
                                                                 @elseif($pedido->status === 'pendente') text-yellow-600
                                                                 @elseif($pedido->status === 'cancelado') text-red-600
                                                                 @else text-gray-600 @endif">
                                                        {{ ucfirst($pedido->status) }}
                                                    </span>
                                                </p>
                                                <p class="mb-2 font-bold text-gray-800">
                                                    Valor Total: R$ {{ number_format($pedido->total, 2, ',', '.') }}
                                                </p>

                                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                                                    @forelse($pedido->itens as $item)
                                                        <div class="flex items-center space-x-3 border p-3 rounded-md bg-white">
                                                            @if($item->produto && $item->produto->imagens->isNotEmpty())
                                                                @php
                                                                    $mainImage = $item->produto->imagens->firstWhere('principal', true) ?: $item->produto->imagens->first();
                                                                @endphp
                                                                @if($mainImage)
                                                                    <img src="{{ asset($mainImage->caminho) }}"
                                                                        alt="{{ $item->produto->nome_produto ?? 'Produto' }}"
                                                                        class="w-16 h-16 object-cover rounded-md">
                                                                @else
                                                                    <div class="w-16 h-16 flex items-center justify-center bg-gray-200 rounded-md">
                                                                        <i class="fas fa-image text-gray-500"></i>
                                                                    </div>
                                                                @endif
                                                            @else
                                                                <div class="w-16 h-16 flex items-center justify-center bg-gray-200 rounded-md">
                                                                    <i class="fas fa-image text-gray-500"></i>
                                                                </div>
                                                            @endif
                                                            <div>
                                                                <p class="font-medium text-gray-800">
                                                                    {{ $item->produto->nome_produto ?? 'Produto Indisponível' }}
                                                                </p>
                                                                <p class="text-sm text-gray-600">Qtd: {{ $item->quantidade }} | R$
                                                                    {{ number_format($item->preco_unitario, 2, ',', '.') }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    @empty
                                                        <p class="text-gray-600 col-span-full">Nenhum item encontrado para este pedido.</p>
                                                    @endforelse
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @elseif($currentView === 'favoritos')
                            <div class="mb-8">
                                <h3 class="font-semibold text-lg text-gray-800 leading-tight mb-4">Meus Favoritos</h3>

                                @if($favoritos->isEmpty())
                                    <div class="text-center p-8 bg-blue-100 border border-blue-200 rounded-lg">
                                        <p class="text-blue-800 font-semibold mb-2">Seus favoritos estão vazios!</p>
                                        <p class="text-blue-600">Aproveite nossas promoções e adicione produtos que você amou aqui!</p>
                                        <a href="{{ url('/') }}" class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md transition duration-300">
                                            <i class="fas fa-arrow-left mr-2"></i> Continuar comprando
                                        </a>
                                    </div>
                                @else
                                    <p class="text-gray-600 mb-4">Você tem {{ $favoritos->count() }} iten(s) favoritado(s)</p>

                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                        @foreach ($favoritos as $item)
                                            <div class="border rounded-lg p-4 bg-white shadow-md flex items-center space-x-4">
                                                <img src="{{ asset($item->attributes->image) }}" alt="{{ $item->name }}"
                                                     class="w-20 h-20 object-cover rounded-md flex-shrink-0">
                                                <div class="flex-grow">
                                                    <p class="font-semibold text-gray-800 text-lg">{{ $item->name }}</p>
                                                    <p class="text-gray-700 text-sm">R$ {{ number_format($item->price, 2, ',', '.') }}</p>
                                                </div>
                                                <div class="flex flex-col space-y-2">
                                                    <form action="{{ route('home.removefavoritos') }}" method="POST" class="inline-block">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $item->id }}">
                                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-full shadow-md transition duration-300" title="Remover dos favoritos">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                 </div>   
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="mt-8 flex justify-center space-x-4">
                                        <a href="{{ url('/') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md transition duration-300">
                                            <i class="fas fa-arrow-left mr-2"></i> Continuar comprando
                                        </a>
                                        <a href="{{ route('home.limparfavoritos') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-md transition duration-300">
                                            <i class="fas fa-times-circle mr-2"></i> Limpar favoritos
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>