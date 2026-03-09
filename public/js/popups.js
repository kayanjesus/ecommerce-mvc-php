// ============================================
// SISTEMA DE POP-UPS SUPER SIMPLES
// ============================================

// Criar container para as mensagens
const toastContainer = document.createElement('div');
toastContainer.className = 'toast-container';
document.body.appendChild(toastContainer);

// Função para mostrar mensagens
window.mostrarMensagem = function (mensagem, tipo = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast ${tipo}`;

    const icones = {
        success: '✓',
        error: '✗',
        warning: '⚠',
        info: 'ℹ'
    };

    const titulos = {
        success: 'Sucesso!',
        error: 'Erro!',
        warning: 'Atenção!',
        info: 'Informação'
    };

    toast.innerHTML = `
        <div class="toast-icon">${icones[tipo]}</div>
        <div class="toast-content">
            <div class="toast-title">${titulos[tipo]}</div>
            <div class="toast-message">${mensagem}</div>
        </div>
        <div class="toast-close">✕</div>
    `;

    toastContainer.appendChild(toast);

    setTimeout(() => toast.classList.add('show'), 10);

    toast.querySelector('.toast-close').onclick = () => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    };

    setTimeout(() => {
        if (toast.parentNode) {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }
    }, 5000);
};

// Função para confirmar ações
window.confirmar = function (mensagem, callbackSim, callbackNao) {
    const overlay = document.createElement('div');
    overlay.className = 'modal-overlay';

    const modal = document.createElement('div');
    modal.className = 'modal-container';
    modal.innerHTML = `
        <div class="modal-header">
            <h3>Confirmar</h3>
            <span class="modal-close">✕</span>
        </div>
        <div class="modal-body">
            ${mensagem}
        </div>
        <div class="modal-footer">
            <button class="modal-btn modal-btn-secondary" id="btn-nao">Não</button>
            <button class="modal-btn modal-btn-primary" id="btn-sim">Sim</button>
        </div>
    `;

    overlay.appendChild(modal);
    document.body.appendChild(overlay);
    overlay.style.display = 'flex';

    const fechar = () => {
        overlay.style.display = 'none';
        setTimeout(() => overlay.remove(), 300);
    };

    modal.querySelector('.modal-close').onclick = () => {
        fechar();
        if (callbackNao) callbackNao();
    };

    modal.querySelector('#btn-nao').onclick = () => {
        fechar();
        if (callbackNao) callbackNao();
    };

    modal.querySelector('#btn-sim').onclick = () => {
        fechar();
        if (callbackSim) callbackSim();
    };

    overlay.onclick = (e) => {
        if (e.target === overlay) {
            fechar();
            if (callbackNao) callbackNao();
        }
    };
};

// Função para loading
window.loading = function (mensagem = 'Carregando...') {
    const overlay = document.createElement('div');
    overlay.className = 'modal-overlay';
    overlay.style.backgroundColor = 'rgba(255, 255, 255, 0.8)';

    const div = document.createElement('div');
    div.style.cssText = `
        background: white;
        padding: 30px;
        border-radius: 20px;
        text-align: center;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    `;

    div.innerHTML = `
        <div style="
            width: 50px;
            height: 50px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #9b2a2a;
            border-radius: 50%;
            margin: 0 auto 15px;
            animation: spin 1s linear infinite;
        "></div>
        <div style="color: #666;">${mensagem}</div>
    `;

    overlay.appendChild(div);
    document.body.appendChild(overlay);
    overlay.style.display = 'flex';

    return {
        fechar: () => {
            overlay.style.display = 'none';
            setTimeout(() => overlay.remove(), 300);
        }
    };
};

// Adicionar animação de spin
const style = document.createElement('style');
style.textContent = `
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
`;
document.head.appendChild(style);