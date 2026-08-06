<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $dataNascimento = Carbon::create(
                $request->year,
                $request->month,
                $request->day
            )->format('Y-m-d');

            $request->merge(['data_nasc' => $dataNascimento]);
        } catch (\Exception $e) {
            return back()->withInput()->withErrors([
                'data_nasc' => 'A data de nascimento informada é inválida. Verifique dia, mês e ano.'
            ]);
        }

        // CPF limpo (só dígitos) — usado tanto para o hash de unicidade
        // quanto para gravar no campo criptografado.
        $cpfLimpo = preg_replace('/[^0-9]/', '', (string) $request->cpf);
        $cpfHash = $cpfLimpo ? hash('sha256', $cpfLimpo) : null;

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'cpf' => ['required', 'string', 'regex:/^\d{3}\.\d{3}\.\d{3}-\d{2}$/'],
            'data_nasc' => ['required', 'date', 'before_or_equal:' . Carbon::now()->subYears(18)->format('Y-m-d')],
        ]);

        // Checagem de unicidade de CPF feita à parte, contra cpf_hash
        // (determinístico) e não contra a coluna 'cpf' (criptografada,
        // não-determinística — 'unique' nunca funcionaria nela).
        if ($cpfHash && User::where('cpf_hash', $cpfHash)->exists()) {
            return back()->withInput()->withErrors([
                'cpf' => 'Este CPF já está cadastrado.'
            ]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'cpf' => $cpfLimpo,
            'cpf_hash' => $cpfLimpo,
            'data_nasc' => $request->data_nasc,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('home.index', absolute: false));
    }
}
