<section class="delete-account-section">
    <header class="form-header">
        <h2 class="form-title danger-title">
            {{ __('Deletar Conta') }}
        </h2>
        <p class="form-description">
            {{ __('Uma vez que sua conta for excluída, todos os seus recursos e dados serão permanentemente apagados. Por favor, insira sua senha para confirmar que deseja excluir sua conta permanentemente.') }}
        </p>
    </header>

    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="delete-account-button">
        {{ __('Deletar Conta') }}
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="delete-modal-form">
            @csrf
            @method('delete')

            <h2 class="modal-title">
                {{ __('Tem certeza de que deseja excluir sua conta?') }}
            </h2>

            <p class="modal-description">
                {{ __('Uma vez que sua conta for excluída, todos os seus recursos e dados serão permanentemente apagados. Por favor, insira sua senha para confirmar que deseja excluir sua conta permanentemente.') }}
            </p>

            <div class="password-input-group">
                <x-input-label for="password" value="{{ __('Senha') }}" class="sr-only" />
                <x-text-input id="password" name="password" type="password" placeholder="{{ __('Senha') }}" />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="error-message" />
            </div>

            <div class="modal-buttons">
                <button type="button" x-on:click="$dispatch('close')" class="cancel-button">
                    {{ __('Cancelar') }}
                </button>

                <button type="submit" class="confirm-delete-button">
                    {{ __('Deletar Conta') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>