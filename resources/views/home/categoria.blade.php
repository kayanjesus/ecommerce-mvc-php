<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Cantinho da Isa | {{ $categoriaSelecionada ? $categoriaSelecionada->nome_categoria : 'Produtos' }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/produto.css') }}" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

@extends('layouts.cabecario')

@section('content')

<main>
    <aside class="filtros">
        <div class="filtros-header">
            <h3><i class="fas fa-filter"></i> Filtros</h3>
            <div class="filtros-count" id="filtros-count">0</div>
        </div>
        
        <form id="filter-form" action="{{ route('home.categoria', ['id_categoria' => $categoriaSelecionada->id_categoria ?? 0]) }}" method="GET">
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif

            <!-- FILTRO DE CATEGORIAS -->
<div class="categoria">
    <div class="filter-header">
        <h4><i class="fas fa-tags"></i> Categorias</h4>
        <i class="fas fa-chevron-down"></i>
    </div>
    <div class="filter-content">
        <div class="filter-grid">
            @foreach($todasCategorias as $cat)
                <label class="checkbox-label">
                    <input type="checkbox" name="categorias[]" value="{{ $cat->id_categoria }}"
                        {{ in_array($cat->id_categoria, request('categorias', [])) ? 'checked' : '' }}>
                    <span class="checkmark"></span>
                    <span class="label-text">{{ $cat->nome_categoria }}</span>
                </label>
            @endforeach
        </div>
    </div>
</div>

<!-- FILTRO DE CORES -->
<div class="categoria">
    <div class="filter-header">
        <h4><i class="fas fa-palette"></i> Cores</h4>
        <i class="fas fa-chevron-down"></i>
    </div>
    <div class="filter-content">
        <div class="color-filter-grid">
            @foreach($cores as $cor)
                @php
                  $isDark = \App\Helpers\ColorHelper::isDark($cor->codigo_hex ?? '#CCCCCC');
                 @endphp
             <label class="color-checkbox-label" title="{{ $cor->nome }}">
             <input type="checkbox" name="cores[]" value="{{ $cor->id_cor }}"
                {{ in_array($cor->id_cor, request('cores', [])) ? 'checked' : '' }}>
             <span class="color-circle" style="background-color: {{ $cor->codigo_hex ?? '#CCCCCC' }}; border: 2px solid {{ $isDark ? '#333' : '#ddd' }};">
              <span class="color-checkmark {{ $isDark ? 'light' : 'dark' }}">
                    <i class="fas fa-check"></i>
              </span>
             </span>
             <span class="color-name">{{ $cor->nome }}</span>
             </label>
            @endforeach
        </div>
    </div>
</div>


<!-- FILTRO DE MARCAS -->
<div class="categoria">
    <div class="filter-header">
        <h4><i class="fas fa-copyright"></i> Marcas</h4>
        <i class="fas fa-chevron-down"></i>
    </div>
    <div class="filter-content">
        <div class="filter-grid">
            @foreach($marcas as $marca)
                <label class="checkbox-label">
                    <input type="checkbox" name="marcas[]" value="{{ $marca }}"
                        {{ in_array($marca, request('marcas', [])) ? 'checked' : '' }}>
                    <span class="checkmark"></span>
                    <span class="label-text">{{ $marca }}</span>
                </label>
            @endforeach
        </div>
    </div>
</div>

<!-- FILTRO DE TAMANHOS -->
<div class="categoria">
    <div class="filter-header">
        <h4><i class="fas fa-ruler"></i> Tamanhos</h4>
        <i class="fas fa-chevron-down"></i>
    </div>
    <div class="filter-content">
        <div class="size-filter-grid">
            @foreach($tamanhos as $tamanho)
                <label class="size-checkbox-label">
                    <input type="checkbox" name="tamanhos[]" value="{{ $tamanho->id_tamanho }}"
                        {{ in_array($tamanho->id_tamanho, request('tamanhos', [])) ? 'checked' : '' }}>
                    <span class="size-circle">
                        {{ $tamanho->nome }}
                    </span>
                </label>
            @endforeach
        </div>
    </div>
</div>

<!-- FILTRO DE GÊNEROS -->
<div class="categoria">
    <div class="filter-header">
        <h4><i class="fas fa-venus-mars"></i> Gêneros</h4>
        <i class="fas fa-chevron-down"></i>
    </div>
    <div class="filter-content">
        <div class="filter-grid">
            @foreach($generos as $genero)
                <label class="checkbox-label">
                    <input type="checkbox" name="generos[]" value="{{ $genero }}"
                        {{ in_array($genero, request('generos', [])) ? 'checked' : '' }}>
                    <span class="checkmark"></span>
                    <span class="label-text">{{ $genero }}</span>
                </label>
            @endforeach
        </div>
    </div>
</div>

            <div class="filtros-actions">
                <button type="submit" class="btn-aplicar">
                    <i class="fas fa-check"></i> Aplicar Filtros
                </button>
                <button type="button" class="btn-remover" id="remover-filtros-btn">
                    <i class="fas fa-times"></i> Remover Todos
                </button>
            </div>
        </form>
    </aside>

    <div class="container-produtos-main">
        <div class="page-header">
            <div class="breadcrumb">
                <a href="{{ route('home.index') }}">Home</a>
                <i class="fas fa-chevron-right"></i>
                <span>{{ $categoriaSelecionada ? $categoriaSelecionada->nome_categoria : 'Todos os Produtos' }}</span>
            </div>
            
            <h1 class="page-title">
                <i class="fas fa-box"></i>
                {{ $categoriaSelecionada ? $categoriaSelecionada->nome_categoria : 'Todos os Produtos' }}
                <span class="produtos-count">({{ $produtos->total() }} produtos)</span>
            </h1>
            
            @if (isset($search) && $search)
                <div class="search-results">
                    <p><i class="fas fa-search"></i> Resultados para: <strong>"{{ $search }}"</strong></p>
                </div>
            @endif
        </div>

        <section class="produtos-section">
            @if($produtos->count() > 0)
                <div class="produtos-grid">
                    @foreach ($produtos as $produto)
                        <div class="produto-card">
                            <div class="produto-image-container">
                                <a href="{{ route('home.details', $produto->slug) }}" class="produto-link">
                                    @php
                                        $mainImage = $produto->imagens->firstWhere('principal', true);
                                        $displayImage = $mainImage ? asset($mainImage->caminho) : ($produto->imagens->first()->caminho ?? 'https://placehold.co/400x400/e6e6e6/999999?text=Sem+Imagem');
                                    @endphp
                                    <img src="{{ $displayImage }}" 
                                         alt="{{ $produto->nome_produto }}" 
                                         class="produto-image" />
                                    <div class="produto-overlay">
                                        <span class="overlay-text">Ver Detalhes</span>
                                    </div>
                                </a>
                            </div>
                            
                            <div class="produto-info">
                                <h3 class="produto-nome">{{ $produto->nome_produto }}</h3>
                                <p class="produto-descricao">{{ Str::limit($produto->variacao ?? $produto->descricao ?? '', 30) }}</p>
                                
                                <div class="produto-preco">
                                    <span class="preco">R$ {{ number_format($produto->preco, 2, ',', '.') }}</span>
                                </div>
                                
                                <a href="{{ route('home.details', $produto->slug) }}" class="btn-comprar">
                                    <i class="fas fa-shopping-cart"></i> Comprar Agora
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                
                
@if($produtos->hasPages())
<div class="pagination-wrapper">
    <div class="pagination-info">
        Mostrando {{ $produtos->firstItem() ?? 0 }} a {{ $produtos->lastItem() ?? 0 }} 
        de {{ $produtos->total() }} produtos
    </div>
    
    <div class="pagination-container">
        {{ $produtos->appends(request()->except('page'))->links() }}
    </div>
    
    @if($produtos->total() > 12)
    <div class="pagination-per-page">
        <label for="perPage">Itens por página:</label>
        <select id="perPage" onchange="window.location.href = updateQueryStringParam('per_page', this.value)">
            <option value="12" {{ request('per_page', 12) == 12 ? 'selected' : '' }}>12</option>
            <option value="24" {{ request('per_page') == 24 ? 'selected' : '' }}>24</option>
            <option value="48" {{ request('per_page') == 48 ? 'selected' : '' }}>48</option>
        </select>
    </div>
    @endif
</div>
@endif
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <h3>Nenhum produto encontrado</h3>
                    <p>Não encontramos produtos com os filtros selecionados.</p>
                    <a href="{{ route('home.categoria', ['id_categoria' => 0]) }}">
                        <button type="button" class="btn-primary" id="limpar-filtros-btn">
                         <i class="fas fa-redo"></i> Limpar Filtros
                        </button>
                    </a>
                </div>
            @endif
        </section>
    </div>
</main>

<script>
// Função para atualizar parâmetro na URL
function updateQueryStringParam(key, value) {
    const url = new URL(window.location.href);
    url.searchParams.set(key, value);
    url.searchParams.delete('page'); // Remove página atual ao mudar quantidade
    return url.toString();
}

// Atualizar quantidade por página
document.addEventListener('DOMContentLoaded', function() {
    const perPageSelect = document.getElementById('perPage');
    if (perPageSelect) {
        perPageSelect.addEventListener('change', function() {
            window.location.href = updateQueryStringParam('per_page', this.value);
        });
    }
});


document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filter-form');
    const removerFiltrosBtn = document.getElementById('remover-filtros-btn');
    const limparFiltrosBtn = document.getElementById('limpar-filtros-btn');
    const filtrosCount = document.getElementById('filtros-count');
    
    // Função para contar filtros ativos
    function contarFiltrosAtivos() {
        const checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');
        const filtrosAtivos = checkboxes.length;
        filtrosCount.textContent = filtrosAtivos;
        filtrosCount.style.display = filtrosAtivos > 0 ? 'flex' : 'none';
    }
    
    // Contar filtros ao carregar
    contarFiltrosAtivos();
    
    // Atualizar contador quando checkboxes mudam
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', contarFiltrosAtivos);
    });
    
    // Submeter formulário com debounce para evitar múltiplos submits rápidos
    let submitTimeout;
    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            clearTimeout(submitTimeout);
            submitTimeout = setTimeout(() => {
                filterForm.submit();
            }, 300);
        });
    });
    
    // Botão remover filtros
    if (removerFiltrosBtn) {
        removerFiltrosBtn.addEventListener('click', function() {
            window.location.href = "{{ route('home.categoria', ['id_categoria' => $categoriaSelecionada->id_categoria ?? 0]) }}";
        });
    }
    
    // Botão limpar filtros (para empty state)
    if (limparFiltrosBtn) {
        limparFiltrosBtn.addEventListener('click', function() {
            window.location.href = "{{ route('home.categoria', ['id_categoria' => $categoriaSelecionada->id_categoria ?? 0]) }}";
        });
    }
    
    // Lógica para expandir/colapsar filtros
    const filterHeaders = document.querySelectorAll('.filter-header');
    filterHeaders.forEach(header => {
        header.addEventListener('click', () => {
            const categoria = header.parentElement;
            const content = header.nextElementSibling;
            const icon = header.querySelector('i.fa-chevron-down, i.fa-chevron-up');
            
            categoria.classList.toggle('active');
            
            if (categoria.classList.contains('active')) {
                content.style.maxHeight = content.scrollHeight + "px";
                icon.classList.replace('fa-chevron-down', 'fa-chevron-up');
            } else {
                content.style.maxHeight = '0';
                icon.classList.replace('fa-chevron-up', 'fa-chevron-down');
            }
        });
    });
    
    // Expandir categorias com filtros ativos
    const categorias = document.querySelectorAll('.categoria');
    categorias.forEach(cat => {
        const checkboxes = cat.querySelectorAll('input[type="checkbox"]');
        const isChecked = Array.from(checkboxes).some(cb => cb.checked);
        
        if (isChecked) {
            const header = cat.querySelector('.filter-header');
            const content = cat.querySelector('.filter-content');
            const icon = cat.querySelector('i.fa-chevron-down, i.fa-chevron-up');
            
            cat.classList.add('active');
            content.style.maxHeight = content.scrollHeight + "px";
            if (icon) icon.classList.replace('fa-chevron-down', 'fa-chevron-up');
        }
    });
});
</script>
@endsection