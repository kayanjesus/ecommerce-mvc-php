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
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'data_nasc' => ['required', 'date'],
            'cpf' => [
                'required',
                'cpf',
                'max:14',
                'unique:users,cpf',
                function ($attribute, $value, $fail) {
                    $cpfHash = hash('sha256', $value);
                    if (User::where('cpf_hash', $cpfHash)->exists()) {
                        $fail('Este CPF já está cadastrado.');
                    }
                }
            ],
        ]);

        // Criptografar apenas o CPF
        $cpfEncrypted = Crypt::encryptString($request->cpf);
        $cpfHash = hash('sha256', $request->cpf);

        // Criar usuário - NÃO criptografar a data!
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'cpf' => $cpfEncrypted,
            'cpf_hash' => $cpfHash,
            'data_nasc' => $request->data_nasc, // Manter o valor original
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
