<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
// IMPORTANTE: Use o seu novo Form Request
// use App\Http\Requests\Auth\RegisterRequest; // <-- AQUI!
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
        // --- INÍCIO DA LÓGICA PARA JUNTAR A DATA DE NASCIMENTO ---
        try {
            // Tenta criar uma data válida a partir dos inputs
            $dataNascimento = Carbon::create(
                $request->year,
                $request->month,
                $request->day
            )->format('Y-m-d'); // Formata para 'AAAA-MM-DD'

            // Adiciona a data combinada ao request para validação e salvamento
            $request->merge(['data_nasc' => $dataNascimento]);

        } catch (\Exception $e) {
            // Se a data for inválida (ex: 31 de Fevereiro), adiciona um erro e volta
            return back()->withInput()->withErrors([
                'data_nasc' => 'A data de nascimento informada é inválida. Verifique dia, mês e ano.'
            ]);
        }
        // --- FIM DA LÓGICA PARA JUNTAR A DATA DE NASCIMENTO ---


        // --- VALIDAÇÃO DOS DADOS ---
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            // Adicione a validação para o CPF
            'cpf' => ['required', 'string', 'unique:' . User::class, 'regex:/^\d{3}\.\d{3}\.\d{3}-\d{2}$/'],
            // Valide 'data_nasc' que agora existe no request
            'data_nasc' => ['required', 'date', 'before_or_equal:' . Carbon::now()->subYears(18)->format('Y-m-d')], // Exemplo: exige maior de 18 anos
        ]);

        // --- CRIAÇÃO DO USUÁRIO ---
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'cpf' => str_replace(['.', '-'], '', $request->cpf), // Remove formatação do CPF antes de salvar
            'data_nasc' => $request->data_nasc, // Usa o campo 'data_nasc' que combinamos
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('home.index', absolute: false));
    }
}