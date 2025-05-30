@foreach($notificacoes as $notificacao)
<div class="notificacao {{ $notificacao->read_at ? 'lida' : 'nao-lida' }}" data-id="{{ $notificacao->id }}">
    <div class="notificacao-conteudo">
        <p class="notificacao-texto">{{ $notificacao->data['message'] }}</p>
        <small class="notificacao-tempo">{{ $notificacao->created_at->diffForHumans() }}</small>
    </div>
    @if(!$notificacao->read_at)
    <button class="btn-notificacao-lida" data-id="{{ $notificacao->id }}">
        <i class="fas fa-check"></i>
    </button>
    @endif
</div>
@endforeach

@if($notificacoes->isEmpty())
<div class="sem-notificacoes">
    <i class="fas fa-bell-slash"></i>
    <p>Nenhuma notificação no momento</p>
</div>
@endif