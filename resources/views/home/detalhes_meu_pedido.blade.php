<x-app-layout>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalhes do Pedido #') }}{{ $pedido->id_pedido }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-8 lg:p-10 text-gray-900">
                    <h3 class="font-bold text-3xl mb-8 text-indigo-700 border-b-2 border-indigo-200 pb-4">Detalhes do Pedido <span class="text-gray-800">#{{ $pedido->id_pedido }}</span></h3>

                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md shadow-sm" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md shadow-sm" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md shadow-sm" role="alert">
                            <p class="font-bold mb-2">Houve um erro:</p>
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                        <div class="bg-indigo-50 p-6 rounded-lg shadow-md border border-indigo-100">
                            <h4 class="font-semibold text-xl text-indigo-800 mb-4 flex items-center"><i class="fas fa-info-circle mr-3"></i> Informações do Pedido</h4>
                            <p class="mb-2 text-gray-700"><strong>Status:</strong> <span class="font-bold
                                @if($pedido->status === 'pago') text-green-600
                                @elseif($pedido->status === 'pendente') text-yellow-600
                                @elseif($pedido->status === 'cancelado') text-red-600
                                @elseif($pedido->status === 'processando') text-blue-600
                                @elseif(in_array($pedido->status, ['enviado', 'em_transito', 'saiu_para_entrega'])) text-purple-600
                                @elseif($pedido->status === 'entregue') text-indigo-600
                                @elseif($pedido->status === 'reembolso_solicitado') text-orange-600
                                @else text-gray-600 @endif">{{ ucfirst(str_replace('_', ' ', $pedido->status)) }}</span></p>
                            <p class="mb-2 text-gray-700"><strong>Valor Total:</strong> <span class="text-green-700 font-bold text-lg">R$ {{ number_format($pedido->total, 2, ',', '.') }}</span></p>
                            <p class="mb-2 text-gray-700"><strong>Data do Pedido:</strong> {{ \Carbon\Carbon::parse($pedido->created_at)->format('d/m/Y H:i') }}</p>
                            <p class="mb-2 text-gray-700"><strong>Última Atualização:</strong> {{ \Carbon\Carbon::parse($pedido->updated_at)->format('d/m/Y H:i') }}</p>
                            <p class="text-gray-700"><strong>Confirmado por você:</strong> {{ $pedido->confirmado_pelo_cliente ? 'Sim' : 'Não' }}</p>
                        </div>
                        <div class="bg-blue-50 p-6 rounded-lg shadow-md border border-blue-100">
                            <h4 class="font-semibold text-xl text-blue-800 mb-4 flex items-center"><i class="fas fa-user-circle mr-3"></i> Informações do Cliente</h4>
                            <p class="mb-2 text-gray-700"><strong>Comprador:</strong> {{ $pedido->usuario->name ?? 'Usuário Não Encontrado' }}</p>
                            <p class="mb-2 text-gray-700"><strong>Email:</strong> {{ $pedido->usuario->email ?? 'N/A' }}</p>
                            <!-- <p class="mb-2 text-gray-700"><strong>Telefone:</strong> {{ $pedido->usuario->telefone ?? 'Não informado' }}</p> -->
                            <!-- <p class="text-gray-700"><strong>Observações:</strong> {{ $pedido->observacoes ?? 'Nenhuma' }}</p> -->
                        </div>
                    </div>

                    <h4 class="font-bold text-2xl mb-6 text-gray-800 border-b-2 border-gray-200 pb-3">Itens do Pedido</h4>
                    <div class="overflow-x-auto mb-10 shadow-md rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Produto</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Quantidade</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Preço Unitário</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Subtotal</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Sua Avaliação</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse($pedido->itens as $item)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            @if($item->produto)
                                                <div class="flex items-center">
                                                    @php
                                                        $mainImage = $item->produto->imagens->firstWhere('principal', true) ?: $item->produto->imagens->first();
                                                    @endphp
                                                    @if($mainImage)
                                                        <img src="{{ asset($mainImage->caminho) }}" alt="{{ $item->produto->nome_produto }}" class="w-12 h-12 object-cover rounded-md mr-3 shadow-sm">
                                                    @else
                                                        <div class="w-12 h-12 flex items-center justify-center bg-gray-200 rounded-md mr-3 text-gray-500">
                                                            <i class="fas fa-image text-lg"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <p class="font-medium text-gray-800">{{ $item->produto->nome_produto }}</p>
                                                        @if($item->cor || $item->tamanho)
                                                            <small class="block text-gray-500 text-xs">({{ $item->cor->nome ?? '' }}{{ $item->cor && $item->tamanho ? ', ' : '' }}{{ $item->tamanho->nome ?? '' }})</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-gray-500">Produto Indisponível</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $item->quantidade }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">R$ {{ number_format($item->preco_unitario, 2, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">R$ {{ number_format($item->quantidade * $item->preco_unitario, 2, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            @if($item->avaliacao)
                                                <div class="flex items-center text-yellow-500">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= $item->avaliacao->nota)
                                                            <i class="fas fa-star text-base"></i>
                                                        @else
                                                            <i class="far fa-star text-base text-gray-400"></i>
                                                        @endif
                                                    @endfor
                                                    <span class="ml-2 text-gray-700 text-xs">({{ $item->avaliacao->comentario ?? 'Sem comentário' }})</span>
                                                </div>
                                            @else
                                                <span class="text-gray-500 text-xs">Não avaliado</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-600">Nenhum item encontrado para este pedido.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Bloco de Detalhes da Entrega --}}
                    <div class="bg-green-50 p-6 rounded-lg shadow-md border border-green-100 mb-10">
                        <h4 class="font-semibold text-xl text-green-800 mb-4 flex items-center"><i class="fas fa-truck mr-3"></i> Detalhes da Entrega</h4>
                        @if($pedido->entrega)
                            <p class="mb-2 text-gray-700"><strong>Método:</strong> {{ ucfirst($pedido->entrega->metodo_entrega ?? 'Não definido') }}</p>
                            <p class="mb-2 text-gray-700"><strong>Valor do Frete:</strong> R$ {{ number_format($pedido->entrega->valor_entrega ?? 0, 2, ',', '.') }}</p>
                            <p class="mb-2 text-gray-700"><strong>Data de Envio:</strong> {{ $pedido->entrega->data_envio ? \Carbon\Carbon::parse($pedido->entrega->data_envio)->format('d/m/Y H:i') : 'Não enviado' }}</p>
                            <p class="mb-2 text-gray-700"><strong>Data de Entrega Estimada:</strong> {{ $pedido->entrega->data_entrega ? \Carbon\Carbon::parse($pedido->entrega->data_entrega)->format('d/m/Y H:i') : 'Em breve' }}</p>
                            <p class="mb-2 text-gray-700"><strong>Código de Rastreio:</strong> {{ $pedido->entrega->rastreio->codigo_rastreio ?? 'Não disponível' }}</p>
                        @else
                            <p class="text-gray-600">Informações de entrega não disponíveis para este pedido.</p>
                        @endif
                    </div>

                    {{-- Bloco de Detalhes do Pagamento --}}
                    <div class="bg-yellow-50 p-6 rounded-lg shadow-md border border-yellow-100 mb-10">
                        <h4 class="font-semibold text-xl text-yellow-800 mb-4 flex items-center"><i class="fas fa-credit-card mr-3"></i> Detalhes do Pagamento</h4>
                        @if($pedido->pagamentoCheckout)
                            <p class="mb-2 text-gray-700"><strong>Tipo de Pagamento:</strong> {{ ucfirst(str_replace('_', ' ', $pedido->pagamentoCheckout->metodo_pagamento ?? 'N/A')) }}</p>
                            <p class="mb-2 text-gray-700"><strong>Status do Pagamento:</strong> <span class="font-semibold">{{ ucfirst($pedido->pagamentoCheckout->status ?? 'N/A') }}</span></p>
                            <p class="mb-2 text-gray-700"><strong>Código da Transação:</strong> {{ $pedido->pagamentoCheckout->codigo_transacao ?? 'N/A' }}</p>
                            <p class="mb-2 text-gray-700"><strong>Valor Total:</strong> R$ {{ number_format($pedido->total, 2, ',', '.') }}</R$></p>
                            <p class="text-gray-700"><strong>Data do Pagamento:</strong> {{ $pedido->pagamentoCheckout->data_pagamento ? \Carbon\Carbon::parse($pedido->pagamentoCheckout->data_pagamento)->format('d/m/Y H:i') : 'N/A' }}</p>
                        @else
                            <p class="text-gray-600">Informações de pagamento não disponíveis para este pedido.</p>
                        @endif
                    </div>

                    {{-- Bloco de Detalhes do Reembolso --}}
                    <div class="bg-red-50 p-6 rounded-lg shadow-md border border-red-100 mb-10">
                        <h4 class="font-semibold text-xl text-red-800 mb-4 flex items-center"><i class="fas fa-undo-alt mr-3"></i> Detalhes do Reembolso</h4>
                        @if($pedido->reembolso)
                            <p class="mb-2 text-gray-700"><strong>Status do Reembolso:</strong> <span class="font-semibold
                                @if(($pedido->reembolso->status ?? '') === 'pendente') text-yellow-600
                                @elseif(($pedido->reembolso->status ?? '') === 'concluido') text-green-600
                                @elseif(($pedido->reembolso->status ?? '') === 'negado') text-red-600
                                @else text-gray-600 @endif">{{ ucfirst(str_replace('_', ' ', $pedido->reembolso->status ?? 'N/A')) }}</span></p>
                            <p class="mb-2 text-gray-700"><strong>Motivo:</strong> {{ $pedido->reembolso->motivo ?? 'N/A' }}</p>
                            <p class="mb-2 text-gray-700"><strong>Valor Reembolsado:</strong> R$ {{ number_format($pedido->reembolso->valor_reembolsado ?? 0, 2, ',', '.') }}</p>
                            <p class="mb-2 text-gray-700"><strong>Data da Solicitação:</strong> {{ $pedido->reembolso->data_solicitacao ? \Carbon\Carbon::parse($pedido->reembolso->data_solicitacao)->format('d/m/Y H:i') : 'N/A' }}</p>
                            <!-- <p class="text-gray-700"><strong>Data da Conclusão:</strong> {{ $pedido->reembolso->data_conclusao ? \Carbon\Carbon::parse($pedido->reembolso->data_conclusao)->format('d/m/Y H:i') : 'Pendente' }}</p> -->
                            {{-- REMOVIDO: Admin que Processou --}}
                        @else
                            <p class="text-gray-600">Informações de reembolso não disponíveis para este pedido.</p>
                        @endif
                    </div>

                    <div class="mt-8 flex justify-end">
                        <a href="{{ route('home.dashboard', ['show' => 'pedidos']) }}"
                            class="px-6 py-3 bg-gray-200 text-gray-700 rounded-md font-semibold text-base hover:bg-gray-300 transition-colors duration-200">
                            Voltar para Meus Pedidos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
