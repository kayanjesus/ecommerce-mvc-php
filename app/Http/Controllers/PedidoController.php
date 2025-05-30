<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function index()
    {
        $pedidos = Pedido::with(['usuario', 'itens.produto', 'pagamento'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.pedidos', compact('pedidos'));
    }

    public function alterarStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pago,cancelado'
        ]);

        $pedido = Pedido::findOrFail($id);
        $pedido->update(['status' => $request->status]);

        // Atualiza também o status do pagamento
        if($pedido->pagamento) {
            $pedido->pagamento->update([
                'status' => $request->status,
                'data_pagamento' => $request->status == 'pago' ? now() : null
            ]);
        }

        return back()->with('sucesso', 'Status do pedido atualizado com sucesso!');
    }

    public function show($id)
    {
        $pedido = Pedido::with(['usuario', 'itens.produto', 'pagamento'])
            ->findOrFail($id);

        return view('admin.pedido-detalhes', compact('pedido'));
    }
}