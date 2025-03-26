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
            'cpf' => ['required', 'cpf', 'unique:users,cpf'],  // Validação para garantir CPF único
        ]);

        // Criptografar o CPF
        $cpf_criptografado = Crypt::encryptString($request->cpf);

        // Verificar se já existe um usuário com esse CPF criptografado
        if (User::where('cpf', $cpf_criptografado)->exists()) {
            return redirect()->back()->withErrors(['cpf' => 'Este CPF já está cadastrado.']);
        }

        // Criar o usuário com CPF criptografado
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'cpf' => $cpf_criptografado,  // Armazenar o CPF criptografado
            'data_nasc' => $request->data_nasc,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
