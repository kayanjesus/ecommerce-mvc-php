<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pedido;
use App\Models\Entrega;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ConfirmDeliveredOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pedidos:confirmar-entregues';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Confirma automaticamente pedidos como "entregues" se não forem confirmados em 3 dias após o envio.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando a confirmação automática de pedidos entregues...');

        // Busca pedidos que foram 'enviados' (ou 'em_transito', 'saiu_para_entrega')
        // e que não foram confirmados como 'entregues' pelo cliente
        // e cuja data de envio é há 3 dias ou mais
        $pedidosParaConfirmar = Pedido::whereIn('status', ['enviado', 'em_transito', 'saiu_para_entrega'])
            ->whereHas('entrega', function ($query) {
                $query->whereNotNull('data_envio')
                    ->whereNull('data_entrega') // Apenas se ainda não tiver uma data de entrega
                    ->whereDate('data_envio', '<=', Carbon::now()->subDays(3));
            })
            ->with('entrega')
            ->get();

        if ($pedidosParaConfirmar->isEmpty()) {
            $this->info('Nenhum pedido para confirmar automaticamente neste momento.');
            return Command::SUCCESS;
        }

        $count = 0;
        foreach ($pedidosParaConfirmar as $pedido) {
            DB::beginTransaction();
            try {
                $pedido->status = 'entregue';
                $pedido->save();

                if ($pedido->entrega) {
                    $pedido->entrega->data_entrega = Carbon::now();
                    $pedido->entrega->save();
                } else {
                    // Caso de fallback: se por algum motivo a entrega não existe (improvável com whereHas)
                    Log::warning("Comando: Pedido #{$pedido->id_pedido} elegível para confirmação, mas sem registro de entrega. Criando um novo.");
                    Entrega::create([
                        'id_pedido' => $pedido->id_pedido,
                        'metodo_entrega' => 'auto-confirmado',
                        'valor_entrega' => $pedido->total > 0 ? 0.00 : 0.00, // Defina um valor padrão ou calcule
                        'data_envio' => $pedido->created_at, // Pode precisar de uma lógica melhor
                        'data_entrega' => Carbon::now(),
                    ]);
                }

                DB::commit();
                $this->info("Pedido #{$pedido->id_pedido} confirmado como 'entregue' automaticamente.");
                $count++;
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Erro ao confirmar automaticamente o pedido #{$pedido->id_pedido}: " . $e->getMessage());
                $this->error("Erro ao processar pedido #{$pedido->id_pedido}. Veja os logs para mais detalhes.");
            }
        }

        $this->info("{$count} pedidos confirmados automaticamente como entregues.");
        return Command::SUCCESS;
    }
}
