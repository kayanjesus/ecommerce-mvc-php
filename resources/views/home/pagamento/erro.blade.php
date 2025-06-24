<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erro no Pagamento</title>
    <!-- Inclua seus CSS globais ou do layout principal se aplicável -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css"
        xintegrity="sha512-jnSuA4Ss2PnkZvQoKD8RbWdcOJTqMtngh5/+c3qgRwfdkjGtxK/0myefKCycqff1JzOCXQFF6qXGCuWTiCXlJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white">
                        <h4><i class="fas fa-exclamation-triangle"></i> Ocorreu um erro</h4>
                    </div>
                    <div class="card-body">
                        <h5 class="text-danger">Não foi possível processar seu pedido</h5>
                        <p class="mb-4">Por favor, tente novamente ou entre em contato com nosso suporte.</p>

                        @if(session('erro'))
                            <div class="alert alert-warning">
                                <strong>Detalhes do erro:</strong>
                                <p>{{ session('erro') }}</p>
                            </div>
                        @endif

                        <div class="d-flex justify-content-between mt-4">
                            {{-- CORREÇÃO AQUI: Use o nome correto da sua rota de carrinho 'carrinho.index' --}}
                            <a href="{{ route('home.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Voltar ao carrinho
                            </a>
                            <a href="{{ route('home.index') }}" class="btn btn-primary">
                                <i class="fas fa-home"></i> Ir para a página inicial
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Inclua seus scripts JS globais ou do layout principal se aplicável -->
</body>

</html>