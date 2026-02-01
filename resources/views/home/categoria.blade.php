
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

                {{-- FILTRO DE CATEGORIAS (SANFONA) --}}
                <div class="categoria">
                    <div class="filter-header">
                        <h4>Categorias</h4>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="filter-content">
                        @foreach($todasCategorias as $cat)
                            <label>
                                <input type="checkbox" name="categorias[]" value="{{ $cat->id_categoria }}"
                                    {{ in_array($cat->id_categoria, request('categorias', [])) ? 'checked' : '' }}>
                                {{ $cat->nome_categoria }}
                            </label><br>
                        @endforeach
                    </div>
                </div>

                {{-- FILTRO DE CORES (SANFONA) --}}
                <div class="categoria">
                    <div class="filter-header">
                        <h4>Cores</h4>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="filter-content">
                        @foreach($cores as $cor)
                            <label>
                                <input type="checkbox" name="cores[]" value="{{ $cor->id_cor }}"
                                    {{ in_array($cor->id_cor, request('cores', [])) ? 'checked' : '' }}>
                                {{ $cor->nome }}
                            </label><br>
                        @endforeach
                    </div>
                </div>

                {{-- FILTRO DE MARCAS (SANFONA) --}}
                <div class="categoria">
                    <div class="filter-header">
                        <h4>Marcas</h4>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="filter-content">
                        @foreach($marcas as $marca)
                            <label>
                                <input type="checkbox" name="marcas[]" value="{{ $marca }}"
                                    {{ in_array($marca, request('marcas', [])) ? 'checked' : '' }}>
                                {{ $marca }}
                            </label><br>
                        @endforeach
                    </div>
                </div>

                {{-- FILTRO DE TAMANHOS (SANFONA) --}}
                <div class="categoria">
                    <div class="filter-header">
                        <h4>Tamanhos</h4>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="filter-content">
                        @foreach($tamanhos as $tamanho)
                            <label>
                                <input type="checkbox" name="tamanhos[]" value="{{ $tamanho->id_tamanho }}"
                                    {{ in_array($tamanho->id_tamanho, request('tamanhos', [])) ? 'checked' : '' }}>
                                {{ $tamanho->nome }}
                            </label><br>
                        @endforeach
                    </div>
                </div>

                {{-- FILTRO DE GÊNEROS (SANFONA) --}}
                <div class="categoria">
                    <div class="filter-header">
                        <h4>Gêneros</h4>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="filter-content">
                        @foreach($generos as $genero)
                            <label>
                                <input type="checkbox" name="generos[]" value="{{ $genero }}"
                                    {{ in_array($genero, request('generos', [])) ? 'checked' : '' }}>
                                {{ $genero }}
                            </label><br>
                        @endforeach
                    </div>
                </div>

            </form>

            <button type="button" class="remover" id="remover-filtros-btn">Remover filtros</button>
        </aside>

        <div class="container-produtos-main">
            {{-- Exibe o nome da categoria selecionada --}}
            <h2>Produtos da Categoria: {{ $categoriaSelecionada ? $categoriaSelecionada->nome_categoria : 'Todos os Produtos' }}</h2>

            @if (isset($search) && $search)
                <p>Resultados para: "{{ $search }}"</p>
            @endif

            <section class="produtos">
                <div class="retangulos-best-seller">
                    @forelse ($produtos as $produto)
                        {{-- O seu produto no index usa a classe retangulo --}}
                        <div class="retangulo">
                            <a href="{{ route('home.details', $produto->slug) }}">
                                @php
                                    $mainImage = $produto->imagens->firstWhere('principal', true);
                                    // Adicionando fallback para evitar erro caso não tenha imagens
                                    $displayImage = $mainImage ? asset($mainImage->caminho) : ($produto->imagens->first()->caminho ?? 'https://placehold.co/200x200/cccccc/333333?text=Sem+Imagem');
                                @endphp
                                <img src="{{ $displayImage }}"
                                    alt="{{ $produto->nome_produto }}" class="imagem-best-seller" />
                            </a>
                            {{-- Substituindo <h4> e <p> por <span> com as classes do index --}}
                            <span class="Descricao">{{ $produto->nome_produto }}</span>
                            <span class="Descricao">{{ Str::limit($produto->variacao ?? $produto->descricao ?? '', 25) }}</span>
                            <span class="Precinho">R$ {{ number_format($produto->preco, 2, ',', '.') }}</span>
                            <button>
                                <a href="{{ route('home.details', $produto->slug) }}" class="comprar-link">Comprar</a>
                            </button>
                        </div>
                    @empty
                        <p class="text-center py-4 text-gray-600" style="grid-column: 1 / -1;">
                            Não foi possível encontrar nenhum produto com os filtros selecionados.
                        </p>
                    @endforelse
                </div>
            </section>

            {{-- Links de paginação, mantendo os filtros aplicados --}}
            {{ $produtos->appends(request()->except('page'))->links() }}
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterForm = document.getElementById('filter-form');
            const removerFiltrosBtn = document.getElementById('remover-filtros-btn');

            // 1. Lógica para submeter o formulário ao selecionar o checkbox
            if (filterForm) {
                const checkboxes = filterForm.querySelectorAll('input[type="checkbox"]');
                checkboxes.forEach(function(checkbox) {
                    checkbox.addEventListener('change', function() {
                        filterForm.submit();
                    });
                });
            }

            // 2. Lógica para o botão remover filtros
            if (removerFiltrosBtn) {
                removerFiltrosBtn.addEventListener('click', function() {
                    window.location.href = "{{ route('home.categoria', ['id_categoria' => $categoriaSelecionada->id_categoria ?? 0]) }}";
                });
            }
            
            // 3. Lógica para expandir/colapsar os filtros (Accordion)
            const filterHeaders = document.querySelectorAll('.filter-header');
            filterHeaders.forEach(header => {
                header.addEventListener('click', () => {
                    const content = header.nextElementSibling;
                    const icon = header.querySelector('i');
                    
                    header.parentNode.classList.toggle('active'); // Adiciona/remove a classe 'active' no elemento pai (.categoria)
                    
                    if (header.parentNode.classList.contains('active')) {
                        content.style.maxHeight = content.scrollHeight + "px";
                        icon.classList.replace('fa-chevron-down', 'fa-chevron-up');
                    } else {
                        content.style.maxHeight = '0';
                        icon.classList.replace('fa-chevron-up', 'fa-chevron-down');
                    }
                });
            });

            // Opcional: Expandir categorias que já têm filtros aplicados ao carregar
            const categorias = document.querySelectorAll('.categoria');
            categorias.forEach(cat => {
                const checkboxes = cat.querySelectorAll('input[type="checkbox"]');
                const isChecked = Array.from(checkboxes).some(cb => cb.checked);
                
                if (isChecked) {
                    const header = cat.querySelector('.filter-header');
                    const content = cat.querySelector('.filter-content');
                    const icon = cat.querySelector('i');
                    
                    cat.classList.add('active');
                    content.style.maxHeight = content.scrollHeight + "px";
                    icon.classList.replace('fa-chevron-down', 'fa-chevron-up');
                }
            });
        });
    </script>
@endsection
