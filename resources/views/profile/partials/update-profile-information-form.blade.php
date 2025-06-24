<section>
    <header class="form-header">
        <h2 class="form-title">
            {{ __('Informações do Perfil') }}
        </h2>
        <p class="form-description">
            {{ __("Atualize suas informações de perfil e endereço de e-mail.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="profile-form">
        @csrf
        @method('patch')

        <div class="form-group">
            <x-input-label for="name" :value="__('Nome')" />
            <x-text-input id="name" name="name" type="text" :value="old('name', $user->name)" required autofocus
                autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="error-message" />
        </div>

        <div class="form-group">
            <x-input-label for="email" :value="__('E-mail')" />
            <x-text-input id="email" name="email" type="email" :value="old('email', $user->email)" required
                autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="error-message" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div class="email-verification-message">
                    <p>
                        {{ __('Seu endereço de e-mail não foi verificado.') }}
                        <button form="send-verification" class="verification-link">
                            {{ __('Clique aqui para reenviar o e-mail de verificação.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="verification-sent">
                            {{ __('Um novo link de verificação foi enviado para o seu endereço de e-mail.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="form-footer">
            <x-primary-button class="save-button">
                {{ __('Salvar') }}
            </x-primary-button>

            @if (session('status') === 'profile-updated')
                <p class="saved-message" x-data="{ show: true }" x-show="show" x-transition
                    x-init="setTimeout(() => show = false, 2000)">
                    {{ __('Salvo.') }}
                </p>
            @endif
        </div>
    </form>
</section>