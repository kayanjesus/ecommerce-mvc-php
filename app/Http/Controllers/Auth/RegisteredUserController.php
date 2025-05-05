<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;  // Importar o Crypt
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validação de entrada, incluindo a verificação do CPF hash
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'cpf' => [
                'required',
                'cpf',
                'unique:users,cpf',
                function ($attribute, $value, $fail) {
                    // Gerar o hash do CPF
                    $cpfHash = hash('sha256', $value);

                    // Verificar se o hash do CPF já existe no banco de dados
                    if (User::where('cpf_hash', $cpfHash)->exists()) {
                        $fail('Este CPF já está cadastrado.');
                    }
                }
            ],
        ]);

        // Obter CPF original do request
        $cpfOriginal = $request->cpf;

        // Criptografar o CPF para armazenar de forma segura
        $cpfEncrypted = Crypt::encryptString($cpfOriginal);

        // Gerar o hash fixo do CPF para comparação futura
        $cpfHash = hash('sha256', $cpfOriginal);


        // Criar o usuário com CPF criptografado e o hash do CPF
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'cpf' => $cpfEncrypted,  // Armazenar o CPF criptografado
            'cpf_hash' => $cpfHash,  // Armazenar o hash fixo do CPF para verificação
            'data_nasc' => $request->data_nasc,
            'password' => Hash::make($request->password),
        ]);
        // Adicionar o dd() para verificar os dados antes de salvar
        // dd($user);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
