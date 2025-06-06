import './bootstrap';
import mask from '@alpinejs/mask'


import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.plugin(mask)


Alpine.start();


window.Echo.channel('admin-pedidos')
    .listen('.pedido.status.updated', (e) => {
        console.log('Evento de atualização de pedido recebido:', e);
        // Encontre o card do pedido e atualize o status
        const pedidoCard = document.querySelector(`.pedido-card .pedido-id:contains("#${e.pedido_id}")`).closest('.pedido-card');
        if (pedidoCard) {
            // Atualiza o badge de status do pedido
            const statusBadge = pedidoCard.querySelector('.badge.status-' + pedidoCard.querySelector('.badge.status-').className.split(' ')[1]);
            statusBadge.className = `badge status-${e.status_pedido}`;
            statusBadge.textContent = e.status_pedido.charAt(0).toUpperCase() + e.status_pedido.slice(1);

            // Atualiza o badge de status do pagamento (se existir)
            let pagamentoBadge = pedidoCard.querySelector('.badge.bg-info');
            if (!pagamentoBadge && e.status_pagamento !== 'N/A') {
                // Se não existe, cria um novo (ou ajuste sua lógica)
                const header = pedidoCard.querySelector('.pedido-header');
                pagamentoBadge = document.createElement('span');
                pagamentoBadge.classList.add('badge', 'bg-info', 'ms-2');
                header.appendChild(pagamentoBadge);
            }
            if (pagamentoBadge) {
                pagamentoBadge.textContent = `Pagamento: ${e.status_pagamento.charAt(0).toUpperCase() + e.status_pagamento.slice(1)}`;
            }

            // Você pode precisar re-renderizar partes do card ou a página inteira
            // dependendo da complexidade das alterações.
            // Para uma atualização simples, reloaded da página pode ser mais fácil:
            // location.reload();
            // Ou, se a mudança for significativa, um alerta para o admin
            alert(`Status do Pedido #${e.pedido_id} alterado para ${e.status_pedido} (Pagamento: ${e.status_pagamento})!`);
            location.reload(); // Recarrega a página para refletir as mudanças
        }
    });