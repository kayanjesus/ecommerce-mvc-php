<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Entrega - Cantinho da Isa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/cep.css') }}">
</head>

<body>

    <!-- Logo e Navegação -->
    <nav class="navbar bg-white border-bottom">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home.index') }}">
                <img src="{{ asset('img/logo/ft_logo.png') }}" alt="Cantinho da Isa">
            </a>
        </div>
    </nav>

    <!-- Etapas -->
    <div class="etapas">
        <div></div>
        <div style="opacity: 0.5;"></div>
        <div style="opacity: 0.5;"></div>
    </div>

    <!-- Barra de Frete -->
    <div class="barra-info">
        Frete Grátis - Sul e Sudeste a partir de R$250, demais regiões a partir de R$399
    </div>

    <!-- Mensagens -->
    @if(session('erro'))
        <div class="alert alert-danger text-center">
            {{ session('erro') }}
        </div>
    @endif

    <!-- Conteúdo Principal -->
    <div class="container py-4">
        <div class="row">

            <!-- Carrinho -->
            <div class="col-md-6 mb-4">
                <div class="border p-3 rounded shadow-sm">
                    @foreach($itens as $item)
                        <div class="d-flex mb-3">
                            <img src="{{ $item->attributes->image }}" class="produto-img me-3" alt="{{ $item->name }}">
                            <div class="flex-grow-1">
                                <p class="mb-1">
                                    <strong>{{ $item->name }}</strong><br>
                                    @if($item->attributes->cor)
                                        Cor: {{ $item->attributes->cor }}<br>
                                    @endif
                                    @if($item->attributes->tamanho)
                                        Tamanho: {{ $item->attributes->tamanho }}<br>
                                    @endif
                                    Quantidade: {{ $item->quantity }}
                                </p>
                                <p class="mb-0">R$ {{ number_format($item->price, 2, ',', '.') }}</p>
                            </div>
                        </div>
                    @endforeach

                    <div class="text-end total mt-3">Total: R$ {{ number_format($total, 2, ',', '.') }}</div>
                </div>
            </div>

            <!-- Formulário de Entrega -->
            <div class="col-md-6">
                <div class="border p-3 rounded shadow-sm formulario-entrega">
                    <h5 class="mb-3">Informações de Entrega</h5>
                    <form action="{{ route('pagamento.salvar-endereco') }}" method="POST">
                        @csrf

                        <div class="mb-2">
                            <input type="text" class="form-control" name="cep" id="cep" placeholder="CEP"
                                value="{{ old('cep') }}" required>
                            <small class="text-muted">Digite seu CEP para autopreencher</small>
                        </div>

                        <div class="mb-2">
                            <input type="text" class="form-control" name="rua" id="rua" placeholder="Rua"
                                value="{{ old('rua') }}" required>
                        </div>

                        <div class="row mb-2">
                            <div class="col-8">
                                <input type="text" class="form-control" name="bairro" id="bairro" placeholder="Bairro"
                                    value="{{ old('bairro') }}" required>
                            </div>
                            <div class="col-4">
                                <input type="text" class="form-control" name="numero" id="numero" placeholder="Número"
                                    value="{{ old('numero') }}" required>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-8">
                                <input type="text" class="form-control" name="cidade" id="cidade" placeholder="Cidade"
                                    value="{{ old('cidade') }}" required>
                            </div>
                            <div class="col-4">
                                <input type="text" class="form-control" name="estado" id="estado" placeholder="Estado"
                                    value="{{ old('estado') }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <input type="text" class="form-control" name="complemento" id="complemento"
                                placeholder="Complemento" value="{{ old('complemento') }}">
                        </div>

                        <button type="submit" class="btn btn-continuar">Continuar</button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // Busca CEP ao sair do campo
            $('#cep').on('blur', function () {
                var cep = $(this).val().replace(/\D/g, '');

                if (cep.length !== 8) {
                    alert('CEP deve conter 8 dígitos');
                    return;
                }

                // Mostra loading
                $(this).prop('disabled', true).after('<span class="loading">Buscando...</span>');

                $.ajax({
                    url: "{{ route('pagamento.buscar-cep') }}",
                    method: 'GET',
                    data: { cep: cep },
                    dataType: 'json',
                    success: function (response) {
                        if (response.cep_data) {
                            $('#rua').val(response.cep_data.rua).prop('readonly', false);
                            $('#bairro').val(response.cep_data.bairro).prop('readonly', false);
                            $('#cidade').val(response.cep_data.cidade).prop('readonly', false);
                            $('#estado').val(response.cep_data.estado).prop('readonly', false);
                            $('#numero').focus();
                        }
                    },
                    error: function (xhr) {
                        var msg = xhr.responseJSON?.error || 'Erro ao buscar CEP';
                        alert(msg);
                    },
                    complete: function () {
                        $('#cep').prop('disabled', false);
                        $('.loading').remove();
                    }
                });
            });

            // Formata CEP automaticamente
            $('#cep').on('input', function () {
                var value = $(this).val().replace(/\D/g, '');
                if (value.length > 5) {
                    value = value.substring(0, 5) + '-' + value.substring(5, 8);
                }
                $(this).val(value.substring(0, 9));
            });
        });
    </script>
</body>

</html>