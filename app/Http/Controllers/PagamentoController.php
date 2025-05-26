<?php



namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Endereco;
use App\Models\Pedido;
use App\Models\Produto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Darryldecode\Cart\Facades\CartFacade as Cart;


class PagamentoController extends Controller
{
    public function cep(Request $request)
    {
        // Se já tiver endereço, redireciona para pagamento
        if (session()->has('endereco_entrega')) {
            return redirect()->route('pagamento.revisao');
        }

        $itens = Cart::getContent();
        $total = Cart::getTotal();

        return view('home.pagamento.cep', compact('itens', 'total'));
    }


    public function buscarCep(Request $request)
    {
        $request->validate(['cep' => 'required|string|min:8|max:9']);

        try {
            $cep = preg_replace('/[^0-9]/', '', $request->cep);
            $response = Http::get("https://viacep.com.br/ws/{$cep}/json/");

            if ($response->failed() || isset($response->json()['erro'])) {
                return response()->json([
                    'error' => 'CEP não encontrado ou inválido'
                ], 404);
            }

            $data = $response->json();

            return response()->json([
                'cep_data' => [
                    'cep' => $data['cep'] ?? '',
                    'rua' => $data['logradouro'] ?? '',
                    'bairro' => $data['bairro'] ?? '',
                    'cidade' => $data['localidade'] ?? '',
                    'estado' => $data['uf'] ?? ''
                ]
            ]);

        } catch (\Exception $e) {
            Log::error("Erro ao buscar CEP: " . $e->getMessage());
            return response()->json([
                'error' => 'Erro ao consultar o serviço de CEP'
            ], 500);
        }
    }

    public function salvarEndereco(Request $request)
    {
        $request->validate([
            'cep' => 'required|string|size:9',
            'rua' => 'required|string|max:255',
            'bairro' => 'required|string|max:255',
            'numero' => 'required|string|max:20',
            'cidade' => 'required|string|max:255',
            'estado' => 'required|string|size:2',
            'complemento' => 'nullable|string|max:255'
        ]);

        $endereco = [
            'cep' => $request->cep,
            'rua' => $request->rua,
            'bairro' => $request->bairro,
            'numero' => $request->numero,
            'cidade' => $request->cidade,
            'estado' => $request->estado,
            'complemento' => $request->complemento,
            'id_usuario' => Auth::id()
        ];

        // Salva na sessão para usar nas próximas etapas
        session(['endereco_entrega' => $endereco]);

        // Salva no banco
        Endereco::updateOrCreate(
            ['id_usuario' => Auth::id()],
            $endereco
        );

        return redirect()->route('pagamento.revisao');
    }
    private function buscarDadosCep($cep)
    {
        $cep = preg_replace('/[^0-9]/', '', $cep);

        if (strlen($cep) != 8) {
            return null;
        }

        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->get("https://viacep.com.br/ws/{$cep}/json/");
            $data = json_decode($response->getBody(), true);

            if (isset($data['erro']) || empty($data['logradouro'])) {
                return null;
            }

            return [
                'cep' => $data['cep'] ?? '',
                'rua' => $data['logradouro'] ?? '',
                'bairro' => $data['bairro'] ?? '',
                'cidade' => $data['localidade'] ?? '',
                'estado' => $data['uf'] ?? ''
            ];
        } catch (\Exception $e) {
            \Log::error("Erro ao buscar CEP: " . $e->getMessage());
            return null;
        }
    }


    public function revisao()
    {
        // Verifica se tem endereço na sessão
        if (!session()->has('endereco_entrega')) {
            return redirect()->route('pagamento.cep')->with('erro', 'Por favor, informe o endereço de entrega');
        }

        $itens = Cart::getContent();
        $total = Cart::getTotal();
        $endereco = session('endereco_entrega');

        return view('home.pagamento.revisao', compact('itens', 'total', 'endereco'));
    }

    public function finalizar(Request $request)
    {
        $request->validate([
            'metodo_pagamento' => 'required|in:pix,cartao'
        ]);

        // Aqui você implementaria a lógica de pagamento
        // Por enquanto, vamos apenas redirecionar para uma página de sucesso

        return redirect()->route('pagamento.finalizar')
            ->with('sucesso', 'Pedido realizado com sucesso!');
    }
}

