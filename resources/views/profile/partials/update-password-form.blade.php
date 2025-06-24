<section>
    <header class="form-header">
        <h2 class="form-title">
            {{ __('Atualizar Senha') }}
        </h2>
        <p class="form-description">
            {{ __('Certifique-se de que sua conta esteja usando uma senha longa e aleatória para se manter segura.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="profile-form">
        @csrf
        @method('put')

        <div class="form-group">
            <x-input-label for="current_password" :value="__('Senha Atual')" />
            <x-text-input id="current_password" name="current_password" type="password"
                autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="error-message" />
        </div>

        <div class="form-group">
            <x-input-label for="password" :value="__('Nova Senha')" />
            <x-text-input id="password" name="password" type="password" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="error-message" />
        </div>

        <div class="form-group">
            <x-input-label for="password_confirmation" :value="__('Confirme a Nova Senha')" />
            <x-text-input id="password_confirmation" name="password_confirmation" type="password"
                autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="error-message" />
        </div>

        <div class="form-footer">
            <x-primary-button class="save-button">
                {{ __('Salvar') }}
            </x-primary-button>

            @if (session('status') === 'password-updated')
                <p class="saved-message" x-data="{ show: true }" x-show="show" x-transition
                    x-init="setTimeout(() => show = false, 2000)">
                    {{ __('Salvo.') }}
                </p>
            @endif
        </div>
    </form>
</section>