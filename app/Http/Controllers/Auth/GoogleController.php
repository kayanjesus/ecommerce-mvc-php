<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redireciona o usuário para a tela de consentimento do Google.
     */
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Recebe o retorno do Google, cria/vincula o usuário e loga.
     */
    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            Log::error('Erro ao autenticar com Google: ' . $e->getMessage());
            return redirect()->route('login')->with('erro', 'Não foi possível conectar com o Google. Tente novamente.');
        }

        // 1. Já existe usuário vinculado a esse google_id?
        $user = User::where('google_id', $googleUser->getId())->first();

        if (!$user) {
            // 2. Já existe uma conta com esse e-mail (cadastrada por senha)?
            //    Se sim, vincula a conta do Google a ela em vez de duplicar.
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                $user->google_id = $googleUser->getId();
                $user->avatar = $googleUser->getAvatar();
                $user->save();
            } else {
                // 3. Não existe: cria um novo usuário.
                // Sem senha (nullable) e sem CPF ainda — o usuário completa
                // esses dados depois, antes de finalizar uma compra.
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => null,
                    'access_level' => 'user',
                    // O Google já validou este e-mail, não precisamos pedir
                    // verificação de novo.
                    'email_verified_at' => now(),
                ]);
            }
        }

        Auth::login($user, remember: true);

        if ($user->access_level === 'admin') {
            return redirect()->route('adm.dashboard');
        }

        return redirect()->intended(route('home.index'));
    }
}
