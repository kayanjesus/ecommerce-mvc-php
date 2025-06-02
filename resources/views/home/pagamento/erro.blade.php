

    <div class="container py-5">
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
                            <a href="{{ route('home.carrinho') }}" class="btn btn-outline-secondary">
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
