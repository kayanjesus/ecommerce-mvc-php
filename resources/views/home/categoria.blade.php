
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Cantinho da Isa | Cadastro de Produtos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/produto.css') }}" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

@extends('layouts.cabecario')

@section('content')

  <main>
        <aside class="filtros">
            <h3>Filtros</h3>
            {{-- Action do formulário de filtros: usa id_categoria e 0 como fallback --}}
            <form id="filter-form" action="{{ route('home.categoria', ['id_categoria' => $categoriaSelecionada->id_categoria ?? 0]) }}" method="GET">
                {{-- Manter o termo de busca atual se ele veio da URL --}}
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif

                <div class="categoria">
                    <h4>Categorias</h4>
                    @foreach($todasCategorias as $cat)
                        <label>
                            <input type="checkbox" name="categorias[]" value="{{ $cat->id_categoria }}"
                                {{ in_array($cat->id_categoria, request('categorias', [])) ? 'checked' : '' }}>
                            {{ $cat->nome_categoria }}
                        </label><br>
                    @endforeach
                </div>

                <div class="categoria">
                    <h4>Cor</h4>
                    @foreach($cores as $cor)
                        <label>
                            <input type="checkbox" name="cores[]" value="{{ $cor->id_cor }}"
                                {{ in_array($cor->id_cor, request('cores', [])) ? 'checked' : '' }}>
                            {{ $cor->nome }}
                        </label><br>
                    @endforeach
                </div>

                <div class="categoria">
                    <h4>Marca</h4>
                    @foreach($marcas as $marca)
                        <label>
                            <input type="checkbox" name="marcas[]" value="{{ $marca }}"
                                {{ in_array($marca, request('marcas', [])) ? 'checked' : '' }}>
                            {{ $marca }}
                        </label><br>
                    @endforeach
                </div>

                <div class="categoria">
                    <h4>Tamanho</h4>
                    @foreach($tamanhos as $tamanho)
                        <label>
                            <input type="checkbox" name="tamanhos[]" value="{{ $tamanho->id_tamanho }}"
                                {{ in_array($tamanho->id_tamanho, request('tamanhos', [])) ? 'checked' : '' }}>
                            {{ $tamanho->nome }}
                        </label>
                    @endforeach
                </div>

                <div class="categoria">
                    <h4>Modelos</h4>
                    @foreach($generos as $genero)
                        <label>
                            <input type="checkbox" name="generos[]" value="{{ $genero }}"
                                {{ in_array($genero, request('generos', [])) ? 'checked' : '' }}>
                            {{ $genero }}
                        </label><br>
                    @endforeach
                </div>
            </form>

            <button type="button" class="remover" id="remover-filtros-btn">Remover filtros</button>
        </aside>

        <div class="container-produtos-main">
            {{-- Exibe o nome da categoria selecionada --}}
            <h2>Produtos da Categoria: {{ $categoriaSelecionada ? $categoriaSelecionada->nome_categoria : 'Todos os Produtos' }}</h2>

            @if ($search)
                <p>Resultados para: "{{ $search }}"</p>
            @endif

            <section class="produtos">
                @forelse ($produtos as $produto)
                    <div class="produto">
                        <a href="{{ route('home.details', $produto->slug) }}">
                            @php
                                $mainImage = $produto->imagens->firstWhere('principal', true);
                                $displayImage = $mainImage ? asset($mainImage->caminho) : ($produto->imagens->first()->caminho ?? 'https://placehold.co/200x200/cccccc/333333?text=Sem+Imagem');
                            @endphp
                            <img src="{{ $displayImage }}"
                                alt="{{ $produto->nome_produto }}" class="imagem-best-seller" />
                        </a>
                        <h4>{{ $produto->nome_produto }}</h4>
                        <h4>{{ Str::limit($produto->variacao ?? $produto->descricao ?? '', 25) }}</h4>
                        <p class="preco">R${{ number_format($produto->preco, 2, ',', '.') }}</p>
                        <a href="{{ route('home.details', $produto->slug) }}" class="comprar-link">Comprar</a>
                    </div>
                @empty
                    <p class="text-center py-4 text-gray-600">Não foi possível encontrar nenhum produto com os filtros selecionados.</p>
                @endforelse
            </section>

            {{-- Links de paginação, mantendo os filtros aplicados --}}
            {{ $produtos->appends(request()->except('page'))->links() }}
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterForm = document.getElementById('filter-form');

            if (filterForm) {
                const checkboxes = filterForm.querySelectorAll('input[type="checkbox"]');
                checkboxes.forEach(function(checkbox) {
                    checkbox.addEventListener('change', function() {
                        filterForm.submit();
                    });
                });
            }

            const removerFiltrosBtn = document.getElementById('remover-filtros-btn');
            if (removerFiltrosBtn) {
                removerFiltrosBtn.addEventListener('click', function() {
                    window.location.href = "{{ route('home.categoria', ['id_categoria' => $categoriaSelecionada->id_categoria ?? 0]) }}";
                });
            }
        });
    </script>
@endsection
