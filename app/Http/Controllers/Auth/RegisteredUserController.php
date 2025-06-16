<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
// IMPORTANTE: Use o seu novo Form Request
use App\Http\Requests\Auth\RegisterRequest; // <-- AQUI!
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
    public function store(RegisterRequest $request): RedirectResponse // <-- Use o RegisterRequest aqui
    {
        // O Form Request já fez a validação e a limpeza dos dados (prepareForValidation)
        // $request->validated() contém os dados validados.
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'cpf' => $request->cpf, // O cast vai criptografar
            'cpf_hash' => $request->cpf, // O cast vai hashear
            'data_nasc' => $request->data_nasc,
            'password' => Hash::make($request->password),
            
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}