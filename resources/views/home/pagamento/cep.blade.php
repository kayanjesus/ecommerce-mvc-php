@extends('layouts.footer') {{-- ESTE É O NOVO TOPO DO SEU ARQUIVO --}}

@section('content') {{-- TUDO ABAIXO SERÁ O CONTEÚDO ESPECÍFICO DESTA PÁGINA --}}


<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cantinho da Isa - Carrinho</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/pagamento.css') }}" />
    <!-- CSS dos popups -->
    <link rel="stylesheet" href="{{ asset('css/popups.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <header class="topo">
        <div class="logo">
            <a class="navbar-brand" href="{{ route('home.index') }}">
                <img src="{{ asset('img/logo/ft_logo.webp') }}" alt="Logo Cantinho da Isa" />
            </a>
        </div>
        <div class="barra-progresso">
            <div class="progress-line"></div>
            <div class="etapa ativo" data-step="1"> <span class="texto-etapa">Carrinho</span>
                <div class="bolinha"></div>
            </div>
            <div class="etapa" data-step="2"> <span class="texto-etapa">Pagamento</span>
                <div class="bolinha"></div>
            </div>
            <div class="etapa" data-step="3"> <span class="texto-etapa">Confirmação</span>
                <div class="bolinha"></div>
            </div>
        </div>
    </header>

    <div class="linha-branca"></div>

    <div class="frete-banner">
        Frete Grátis - Sul e Sudeste a partir de R$250, demais regiões a partir de R$390
    </div>

    <main class="container">
        {{-- As mensagens vão aparecer como popups --}}

        {{-- Adicione uma mensagem se o carrinho estiver vazio --}}
        @if($itens->isEmpty())
            <section class="carrinho"> {{-- Nova section para estilizar --}}
                <h3>Carrinho de Compras Está Vazio</h3> {{-- Título --}}
                <a href="{{ route('home.index') }}" class="continuar">Continuar Comprando</a> {{-- Botão --}}
            </section>
        @else

            <section class="carrinho">
                @foreach($itens as $item)
                    <div class="item">
                        @if($item->attributes->image)
                            <img src="{{ asset($item->attributes->image) }}" alt="{{ $item->name }}" loading="lazy">
                        @else
                            <div class="placeholder-img">
                                <i class="fas fa-camera text-muted"></i>
                            </div>
                        @endif

                        <div class="detalhes">
                            <p><strong>{{ $item->name }}</strong></p>
                            @if(isset($item->cor))
                                <p>Cor: {{ $item->cor->nome_cor }}</p>
                            @endif
                            @if(isset($item->tamanho))
                                <p>Tamanho: {{ $item->tamanho->nome_tamanho }}</p>
                            @endif
                            <p>Quantidade: {{ $item->quantity }}</p>
                            <p class="preco">R$ {{ number_format($item->price, 2, ',', '.') }}</p>
                        </div>

                        <form action="{{ route('home.removecarrinho') }}" method="POST" id="form-remove-{{ $loop->index }}"
                            style="display: inline;">
                            @csrf
                            <input type="hidden" name="id" value="{{ $item->id }}">
                            <button type="button" class="remover"
                                onclick="confirmarRemoverItem('{{ $loop->index }}', '{{ $item->name }}')">
                                <i class="fa-solid fa-trash" style="color: #5c0000;"></i>
                            </button>
                        </form>
                    </div>
                @endforeach

                <div class="total">
                    <strong>Total: R$ {{ number_format($total, 2, ',', '.') }}</strong>
                </div>
            </section>
        @endif

        <section class="entrega">
            <h3>Informações de Entrega</h3>
            @if(!$itens->isEmpty())
                <form action="{{ route('pagamento.salvar-endereco') }}" method="POST" id="form-endereco">
                    @csrf

                    <div class="form-group">
                        <input type="text" name="cep" id="cep" placeholder="CEP" value="{{ old('cep') }}" required>
                        <div class="alert-cep invalid-feedback"></div>
                    </div>

                    <div class="form-group">
                        <input type="text" name="rua" id="rua" placeholder="Rua" value="{{ old('rua') }}" required>
                    </div>

                    <div class="lado-a-lado">
                        <div class="form-group-half">
                            <input type="text" name="bairro" id="bairro" placeholder="Bairro" value="{{ old('bairro') }}"
                                required>
                        </div>
                        <div class="form-group-half">
                            <input type="text" name="numero" id="numero" placeholder="Número" value="{{ old('numero') }}"
                                required>
                        </div>
                    </div>

                    <div class="lado-a-lado">
                        <div class="form-group-half">
                            <input type="text" name="cidade" id="cidade" placeholder="Cidade" value="{{ old('cidade') }}"
                                required>
                        </div>
                        <div class="form-group-half">
                            <input type="text" name="estado" id="estado" placeholder="Estado" value="{{ old('estado') }}"
                                required>
                        </div>
                    </div>

                    <div class="form-group">
                        <input type="text" name="complemento" id="complemento" placeholder="Complemento"
                            value="{{ old('complemento') }}">
                    </div>

                    <button type="submit" class="continuar">
                        Continuar <i class="fas fa-arrow-right"></i>
                    </button>
                </form>
            @else
                <p>Adicione itens ao carrinho para informar o endereço de entrega.</p>
            @endif
        </section>
    </main>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Script dos popups -->
    <script src="{{ asset('js/popups.js') }}"></script>

    <script>
        // ============================================
        // FUNÇÕES DE CONFIRMAÇÃO
        // ============================================

        // Confirmar remoção de item do carrinho
        function confirmarRemoverItem(index, nomeProduto) {
            confirmar(
                `Deseja remover "${nomeProduto}" do carrinho?`,
                function () {
                    const load = loading('Removendo item...');
                    setTimeout(function () {
                        document.getElementById('form-remove-' + index).submit();
                    }, 500);
                }
            );
        }

        // ============================================
        // FUNÇÃO PARA ENVIAR ENDEREÇO (SEM CONFIRMAÇÃO)
        // ============================================

        function enviarEndereco() {
            // Validações básicas
            const cep = document.getElementById('cep').value.replace(/\D/g, '');
            const rua = document.getElementById('rua').value.trim();
            const bairro = document.getElementById('bairro').value.trim();
            const numero = document.getElementById('numero').value.trim();
            const cidade = document.getElementById('cidade').value.trim();
            const estado = document.getElementById('estado').value.trim();

            let erro = '';

            if (cep.length !== 8) {
                erro = 'CEP inválido. Digite um CEP com 8 dígitos.';
            } else if (!rua) {
                erro = 'Por favor, preencha o nome da rua.';
            } else if (!bairro) {
                erro = 'Por favor, preencha o bairro.';
            } else if (!numero) {
                erro = 'Por favor, preencha o número.';
            } else if (!cidade) {
                erro = 'Por favor, preencha a cidade.';
            } else if (!estado || estado.length !== 2) {
                erro = 'Por favor, preencha o estado com 2 letras (ex: SP, RJ).';
            }

            if (erro) {
                mostrarMensagem(erro, 'warning');
                return;
            }

            // Se passou na validação, mostra loading e envia
            const load = loading('Processando endereço...');
            setTimeout(function () {
                document.getElementById('form-endereco').submit();
            }, 500);
        }

        // ============================================
        // FUNÇÕES EXISTENTES (MANTIDAS)
        // ============================================

        $(document).ready(function () {
            // Formatação automática do CEP
            $('#cep').on('input', function () {
                let value = $(this).val().replace(/\D/g, '');
                if (value.length > 5) {
                    value = value.substring(0, 5) + '-' + value.substring(5, 8);
                }
                $(this).val(value.substring(0, 9));
            });

            // Busca CEP via API
            $('#cep').on('blur', function () {
                const cep = $(this).val().replace(/\D/g, '');
                const $feedback = $('.alert-cep');

                if (cep.length !== 8) {
                    $feedback.text('CEP deve conter 8 dígitos').addClass('text-danger').show();
                    return;
                }

                $(this).prop('disabled', true);
                $feedback.hide().removeClass('text-danger text-success');

                // Adiciona spinner
                const $spinner = $('<span class="spinner-border spinner-border-sm ms-2" role="status"></span>');
                $(this).after($spinner);

                $.ajax({
                    url: "{{ route('pagamento.buscar-cep') }}",
                    method: 'GET',
                    data: { cep: cep },
                    dataType: 'json',
                    success: function (response) {
                        if (response.cep_data) {
                            $('#rua').val(response.cep_data.rua);
                            $('#bairro').val(response.cep_data.bairro);
                            $('#cidade').val(response.cep_data.cidade);
                            $('#estado').val(response.cep_data.estado);
                            $('#numero').focus();

                            // Mostra mensagem de sucesso
                            mostrarMensagem('CEP encontrado com sucesso!', 'success');

                            $feedback.text('CEP encontrado!').removeClass('text-danger').addClass('text-success').show();
                        } else {
                            const erro = response.error || 'CEP não encontrado';
                            mostrarMensagem(erro, 'error');
                            $feedback.text(erro).removeClass('text-success').addClass('text-danger').show();
                        }
                    },
                    error: function (xhr) {
                        const msg = xhr.responseJSON?.error || 'Erro ao buscar CEP';
                        mostrarMensagem(msg, 'error');
                        $feedback.text(msg).removeClass('text-success').addClass('text-danger').show();
                    },
                    complete: function () {
                        $('#cep').prop('disabled', false);
                        $spinner.remove();
                    }
                });
            });
        });

        // ============================================
        // LÓGICA DA BARRA DE PROGRESSO (MANTIDA)
        // ============================================

        document.addEventListener('DOMContentLoaded', function () {
            const totalSteps = 3;
            const currentStep = 1;
            const progressBar = document.querySelector('.progress-line');
            const progress = ((currentStep - 1) / (totalSteps - 1)) * 100;
            const lineWidth = (progress / 100) * 94;
            progressBar.style.width = lineWidth + '%';
        });
    </script>
</body>

</html>

@endsection {{-- FIM DO CONTEÚDO ESPECÍFICO DESTA PÁGINA --}}