<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil - Cantinho da Isa</title>
    <link rel="stylesheet" href="{{ asset('css/perfil-user.css') }}">
    <link rel="stylesheet" href="{{ asset('css/perfil-edit.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="profile-edit-page">
    <div class="edit-profile-container">
        <!-- Header -->
        <div class="profile-header">
            <div class="profile-header-content">
                <div class="profile-avatar">
                    <i class="fas fa-user-edit"></i>
                </div>
                <div class="profile-header-info">
                    <h1>Editar Perfil</h1>
                    <p><i class="fas fa-user me-2"></i>{{ Auth::user()->name }}</p>
                    <p><i class="fas fa-envelope me-2"></i>{{ Auth::user()->email }}</p>
                </div>
            </div>
        </div>

        <!-- Main Layout -->
        <div class="profile-main">
            <!-- Sidebar -->
            <div class="profile-sidebar">
                <div class="nav-section">
                    <div class="nav-section-title">Navegação</div>
                    <button class="nav-item active" data-section="profile-info">
                        <i class="fas fa-user-circle"></i>
                        Informações do Perfil
                    </button>
                    <button class="nav-item" data-section="password">
                        <i class="fas fa-lock"></i>
                        Segurança da Conta
                    </button>
                    <button class="nav-item" data-section="danger-zone">
                        <i class="fas fa-exclamation-triangle"></i>
                        Zona de Perigo
                    </button>
                </div>
                
                <div class="nav-section">
                    <div class="nav-section-title">Voltar Para</div>
                    <a href="{{ route('home.dashboard', ['show' => 'pedidos']) }}" class="nav-item">
                        <i class="fas fa-arrow-left"></i>
                        Voltar ao Dashboard
                    </a>
                    <a href="{{ route('home.dashboard', ['show' => 'pedidos']) }}" class="nav-item">
                        <i class="fas fa-shopping-bag"></i>
                        Meus Pedidos
                    </a>
                    <a href="{{ route('home.dashboard', ['show' => 'favoritos']) }}" class="nav-item">
                        <i class="fas fa-heart"></i>
                        Meus Favoritos
                    </a>
                    <a href="{{ url('/') }}" class="nav-item">
                        <i class="fas fa-home"></i>
                        Voltar à Loja
                    </a>
                </div>
                
                <form method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        Sair da Conta
                    </button>
                </form>
            </div>

            <!-- Content Area -->
            <div class="profile-content">
                <!-- Alerts -->
                @if (session('status') === 'profile-updated')
                    <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        Informações do perfil atualizadas com sucesso!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('status') === 'password-updated')
                    <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        Senha alterada com sucesso!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Atenção!</strong> Por favor, corrija os seguintes erros:
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Profile Information Section -->
<div class="content-body" id="profile-info-section">
    <div class="profile-form-section">
        <div class="section-header">
            <h3><i class="fas fa-user-circle"></i> Informações do Perfil</h3>
            <p>Atualize suas informações pessoais e endereço de e-mail.</p>
        </div>

        <form method="post" action="{{ route('profile.update') }}" id="profile-form">
            @csrf
            @method('patch')

            <div class="form-group">
                <label for="name" class="form-label">
                    Nome Completo <span class="required">*</span>
                </label>
                <input type="text" id="name" name="name" 
                       class="form-input @if(session('status') === 'profile-updated') success @endif"
                       value="{{ old('name', $user->name) }}" 
                       required 
                       autofocus
                       autocomplete="name">
                @error('name')
                    <span class="form-error">{{ $message }}</span>
                @enderror
                @if(session('status') === 'profile-updated')
                    <span class="form-success">Nome atualizado com sucesso!</span>
                @endif
            </div>

            <div class="form-group">
                <label for="cpf" class="form-label">
                    CPF <span class="required">*</span>
                </label>
                <input type="text" id="cpf" name="cpf" 
                       class="form-input @error('cpf') error @endif"
                       value="{{ old('cpf', $user->cpf ?? '') }}" 
                       required 
                       autocomplete="off"
                       placeholder="000.000.000-00"
                       oninput="formatCPF(this)">
                @error('cpf')
                    <span class="form-error">{{ $message }}</span>
                @enderror
                <span class="form-hint">Digite seu CPF no formato: 000.000.000-00</span>
            </div>

            <div class="form-group">
                <label for="data_nasc" class="form-label">
                    Data de Nascimento <span class="required">*</span>
                </label>
                <input type="date" id="data_nasc" name="data_nasc" 
                       class="form-input @error('data_nasc') error @endif"
                       value="{{ old('data_nasc', $user->data_nasc ? \Carbon\Carbon::parse($user->data_nasc)->format('Y-m-d') : '') }}" 
                       required 
                       autocomplete="off">
                @error('data_nasc')
                    <span class="form-error">{{ $message }}</span>
                @enderror
                <span class="form-hint">Selecione sua data de nascimento</span>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">
                    Endereço de E-mail <span class="required">*</span>
                </label>
                <input type="email" id="email" name="email" 
                       class="form-input @if(session('status') === 'profile-updated') success @endif"
                       value="{{ old('email', $user->email) }}" 
                       required 
                       autocomplete="username">
                @error('email')
                    <span class="form-error">{{ $message }}</span>
                @enderror
                @if(session('status') === 'profile-updated')
                    <span class="form-success">E-mail atualizado com sucesso!</span>
                @endif
                
                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                    <div class="verification-message">
                        <p>
                            Seu endereço de e-mail não foi verificado.
                            <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="verification-link">
                                    Clique aqui para reenviar o e-mail de verificação.
                                </button>
                            </form>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <div class="verification-sent">
                                Um novo link de verificação foi enviado para o seu endereço de e-mail.
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="telefone" class="form-label">
                    Telefone
                </label>
                <input type="tel" id="telefone" name="telefone" 
                       class="form-input"
                       value="{{ old('telefone', $user->telefone ?? '') }}" 
                       autocomplete="tel"
                       placeholder="(00) 00000-0000"
                       oninput="formatPhone(this)">
                @error('telefone')
                    <span class="form-error">{{ $message }}</span>
                @enderror
                <span class="form-hint">Digite seu telefone com DDD</span>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Salvar Alterações
                </button>
                <span class="save-status" id="profile-save-status">
                    <i class="fas fa-check"></i>
                    Alterações salvas!
                </span>
            </div>
        </form>
    </div>
</div>

                <!-- Password Section -->
                <div class="content-body" id="password-section" style="display: none;">
                    <div class="profile-form-section">
                        <div class="section-header">
                            <h3><i class="fas fa-lock"></i> Segurança da Conta</h3>
                            <p>Altere sua senha para manter sua conta segura.</p>
                        </div>

                        <form method="post" action="{{ route('password.update') }}" id="password-form">
                            @csrf
                            @method('put')

                            <div class="form-group">
                                <label for="current_password" class="form-label">
                                    Senha Atual <span class="required">*</span>
                                </label>
                                <input type="password" id="current_password" name="current_password" 
                                       class="form-input"
                                       required 
                                       autocomplete="current-password">
                                @error('current_password')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password" class="form-label">
                                    Nova Senha <span class="required">*</span>
                                </label>
                                <input type="password" id="password" name="password" 
                                       class="form-input @if(session('status') === 'password-updated') success @endif"
                                       required 
                                       autocomplete="new-password">
                                @error('password')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                                
                                <div class="password-strength" id="password-strength">
                                    <div class="strength-bar">
                                        <div class="strength-fill"></div>
                                    </div>
                                    <div class="strength-text">Força da senha: <span id="strength-text">fraca</span></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation" class="form-label">
                                    Confirmar Nova Senha <span class="required">*</span>
                                </label>
                                <input type="password" id="password_confirmation" name="password_confirmation" 
                                       class="form-input @if(session('status') === 'password-updated') success @endif"
                                       required 
                                       autocomplete="new-password">
                                @error('password_confirmation')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                                @if(session('status') === 'password-updated')
                                    <span class="form-success">Senha alterada com sucesso!</span>
                                @endif
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-key"></i>
                                    Alterar Senha
                                </button>
                                <span class="save-status" id="password-save-status">
                                    <i class="fas fa-check"></i>
                                    Senha alterada!
                                </span>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Danger Zone Section -->
                <div class="content-body" id="danger-zone-section" style="display: none;">
                    <div class="profile-form-section">
                        <div class="danger-zone">
                            <h3><i class="fas fa-exclamation-triangle"></i> Zona de Perigo</h3>
                            <p>
                                Uma vez que sua conta for excluída, todos os seus recursos e dados serão permanentemente apagados. 
                                Esta ação não pode ser desfeita. Por favor, tenha certeza antes de prosseguir.
                            </p>
                            
                            <button type="button" class="btn btn-danger" id="delete-account-btn">
                                <i class="fas fa-trash-alt"></i>
                                Excluir Minha Conta
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <div class="modal-overlay" id="delete-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-exclamation-triangle"></i> Confirmar Exclusão da Conta</h3>
            </div>
            <div class="modal-body">
                <p class="modal-text">
                    <strong>Tem certeza de que deseja excluir sua conta?</strong><br><br>
                    Esta ação é permanente e irreversível. Todos os seus dados, pedidos, favoritos e configurações serão permanentemente removidos.
                </p>
                
                <form method="post" action="{{ route('profile.destroy') }}" id="delete-account-form">
                    @csrf
                    @method('delete')
                    
                    <div class="form-group">
                        <label for="delete-password" class="form-label">
                            Digite sua senha para confirmar:
                        </label>
                        <input type="password" id="delete-password" name="password" 
                               class="form-input" 
                               required 
                               placeholder="Sua senha atual">
                        @error('password')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="modal-actions">
                        <button type="button" class="btn btn-outline" id="cancel-delete">
                            Cancelar
                        </button>
                        <button type="submit" class="btn btn-danger">
                            Sim, Excluir Minha Conta
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Configurações
    const CONFIG = {
        AUTO_HIDE_ALERTS: 5000,
        RESTORE_BUTTON_TIMEOUT: 5000,
        MIN_PASSWORD_LENGTH: 8
    };

    // Gerenciador de estado
    const StateManager = {
        activeSection: 'profile-info',
        
        init() {
            this.setActiveSection('profile-info');
        },
        
        setActiveSection(sectionId) {
            this.activeSection = sectionId;
            this.updateUI();
        },
        
        updateUI() {
            // Esta função será implementada por outros módulos
        }
    };

    // Navegação por seções
    const SectionNavigator = {
        init() {
            this.navItems = document.querySelectorAll('.nav-item[data-section]');
            this.sections = {
                'profile-info': document.getElementById('profile-info-section'),
                'password': document.getElementById('password-section'),
                'danger-zone': document.getElementById('danger-zone-section')
            };
            
            this.bindEvents();
            this.showSection('profile-info');
        },
        
        bindEvents() {
            this.navItems.forEach(item => {
                item.addEventListener('click', (e) => this.handleNavClick(e));
            });
        },
        
        handleNavClick(event) {
            const sectionId = event.currentTarget.dataset.section;
            this.setActiveNav(sectionId);
            this.showSection(sectionId);
            StateManager.setActiveSection(sectionId);
        },
        
        setActiveNav(sectionId) {
            this.navItems.forEach(nav => nav.classList.remove('active'));
            const activeNav = Array.from(this.navItems).find(nav => nav.dataset.section === sectionId);
            if (activeNav) activeNav.classList.add('active');
        },
        
        showSection(sectionId) {
            Object.values(this.sections).forEach(section => {
                if (section) section.style.display = 'none';
            });
            
            if (this.sections[sectionId]) {
                this.sections[sectionId].style.display = 'block';
            }
        }
    };

    // Validador de força de senha
    const PasswordStrengthChecker = {
        levels: [
            { min: 0, max: 2, label: 'fraca', color: 'var(--danger)' },
            { min: 3, max: 4, label: 'média', color: 'var(--warning)' },
            { min: 5, max: 5, label: 'forte', color: 'var(--success)' }
        ],
        
        init() {
            this.passwordInput = document.getElementById('password');
            this.strengthBar = document.querySelector('#password-strength .strength-fill');
            this.strengthText = document.getElementById('strength-text');
            
            if (this.passwordInput && this.strengthBar && this.strengthText) {
                this.bindEvents();
            }
        },
        
        bindEvents() {
            this.passwordInput.addEventListener('input', () => this.checkStrength());
        },
        
        checkStrength() {
            const password = this.passwordInput.value;
            const strength = this.calculateStrength(password);
            this.updateDisplay(strength);
        },
        
        calculateStrength(password) {
            let score = 0;
            
            // Comprimento mínimo
            if (password.length >= CONFIG.MIN_PASSWORD_LENGTH) score++;
            
            // Letras minúsculas
            if (/[a-z]/.test(password)) score++;
            
            // Letras maiúsculas
            if (/[A-Z]/.test(password)) score++;
            
            // Números
            if (/[0-9]/.test(password)) score++;
            
            // Caracteres especiais
            if (/[^A-Za-z0-9]/.test(password)) score++;
            
            return score;
        },
        
        updateDisplay(score) {
            const level = this.getStrengthLevel(score);
            const widthPercent = (score / 5) * 100;
            
            // Atualizar barra
            this.strengthBar.style.width = `${widthPercent}%`;
            this.strengthBar.style.backgroundColor = level.color;
            
            // Atualizar texto
            this.strengthText.textContent = level.label;
            
            // Atualizar classes
            this.updateStrengthClasses(level.label);
        },
        
        getStrengthLevel(score) {
            return this.levels.find(level => 
                score >= level.min && score <= level.max
            ) || this.levels[0];
        },
        
        updateStrengthClasses(label) {
            const container = this.passwordInput.parentElement;
            
            // Remover todas as classes de força
            container.classList.remove('strength-weak', 'strength-medium', 'strength-strong');
            
            // Adicionar classe apropriada
            if (label === 'fraca') {
                container.classList.add('strength-weak');
            } else if (label === 'média') {
                container.classList.add('strength-medium');
            } else if (label === 'forte') {
                container.classList.add('strength-strong');
            }
        }
    };

    // Gerenciador de mensagens de sucesso
    const SuccessMessageManager = {
        init() {
            this.setupProfileMessages();
            this.setupPasswordMessages();
        },
        
        setupProfileMessages() {
            @if(session('status') === 'profile-updated')
                this.showMessage('profile-save-status');
            @endif
        },
        
        setupPasswordMessages() {
            @if(session('status') === 'password-updated')
                this.showMessage('password-save-status');
            @endif
        },
        
        showMessage(elementId) {
            const element = document.getElementById(elementId);
            if (element) {
                element.classList.add('show');
                setTimeout(() => {
                    element.classList.remove('show');
                }, CONFIG.AUTO_HIDE_ALERTS);
            }
        }
    };

    // Modal de exclusão de conta
    const DeleteAccountModal = {
        init() {
            this.modal = document.getElementById('delete-modal');
            this.deleteBtn = document.getElementById('delete-account-btn');
            this.cancelBtn = document.getElementById('cancel-delete');
            this.form = document.getElementById('delete-account-form');
            
            if (this.modal && this.deleteBtn) {
                this.bindEvents();
            }
        },
        
        bindEvents() {
            this.deleteBtn.addEventListener('click', () => this.open());
            this.cancelBtn.addEventListener('click', () => this.close());
            this.modal.addEventListener('click', (e) => this.handleOutsideClick(e));
            this.form.addEventListener('submit', (e) => this.handleSubmit(e));
        },
        
        open() {
            this.modal.classList.add('show');
        },
        
        close() {
            this.modal.classList.remove('show');
        },
        
        handleOutsideClick(event) {
            if (event.target === this.modal) {
                this.close();
            }
        },
        
        handleSubmit(event) {
            const password = document.getElementById('delete-password').value;
            
            if (!this.validatePassword(password)) {
                event.preventDefault();
                alert('Por favor, digite sua senha para confirmar a exclusão da conta.');
                return false;
            }
            
            if (!this.confirmDeletion()) {
                event.preventDefault();
                return false;
            }
            
            return true;
        },
        
        validatePassword(password) {
            return password && password.trim() !== '';
        },
        
        confirmDeletion() {
            return confirm(
                'ATENÇÃO: Esta ação é PERMANENTE e IRREVERSÍVEL!\n\n' +
                'Tem certeza ABSOLUTA que deseja excluir sua conta?'
            );
        }
    };

    // Gerenciador de feedback de formulários
    const FormSubmissionManager = {
        init() {
            this.forms = document.querySelectorAll('form');
            this.bindEvents();
        },
        
        bindEvents() {
            this.forms.forEach(form => {
                form.addEventListener('submit', (e) => this.handleFormSubmit(e));
            });
        },
        
        handleFormSubmit(event) {
            const form = event.currentTarget;
            const submitBtn = form.querySelector('button[type="submit"]');
            
            if (submitBtn) {
                this.showLoadingState(submitBtn);
            }
        },
        
        showLoadingState(button) {
            const originalHTML = button.innerHTML;
            
            // Mostrar estado de carregamento
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processando...';
            button.disabled = true;
            
            // Restaurar após timeout (para caso de erro)
            setTimeout(() => {
                this.restoreButton(button, originalHTML);
            }, CONFIG.RESTORE_BUTTON_TIMEOUT);
        },
        
        restoreButton(button, originalHTML) {
            button.innerHTML = originalHTML;
            button.disabled = false;
        }
    };

    // Gerenciador de alertas
    const AlertManager = {
        init() {
            this.alerts = document.querySelectorAll('.alert');
            this.setupAutoHide();
        },
        
        setupAutoHide() {
            this.alerts.forEach(alert => {
                setTimeout(() => {
                    this.hideAlert(alert);
                }, CONFIG.AUTO_HIDE_ALERTS);
            });
        },
        
        hideAlert(alert) {
            try {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            } catch (error) {
                console.warn('Não foi possível fechar o alerta:', error);
            }
        }
    };

    // Inicialização do sistema
    class ProfileEditApp {
        constructor() {
            this.modules = [
                StateManager,
                SectionNavigator,
                PasswordStrengthChecker,
                SuccessMessageManager,
                DeleteAccountModal,
                FormSubmissionManager,
                AlertManager
            ];
        }
        
        init() {
            console.log('Iniciando aplicação de edição de perfil...');
            
            this.modules.forEach(module => {
                try {
                    if (typeof module.init === 'function') {
                        module.init();
                        console.log(`✓ ${module.constructor.name} inicializado`);
                    }
                } catch (error) {
                    console.error(`Erro ao inicializar ${module.constructor.name}:`, error);
                }
            });
            
            // Configurações adicionais
            this.setupDateInputs();
            this.setupFormValidations();
        }
        
        setupDateInputs() {
            // Configurar inputs do tipo date
            const dateInputs = document.querySelectorAll('input[type="date"]');
            dateInputs.forEach(input => {
                // Adicionar placeholder personalizado para navegadores antigos
                if (!input.value) {
                    input.style.color = '#999';
                }
                
                input.addEventListener('change', function() {
                    this.style.color = this.value ? '' : '#999';
                });
            });
        }
        
        setupFormValidations() {
            // Validação do CPF
            const cpfInput = document.getElementById('cpf');
            if (cpfInput) {
                cpfInput.addEventListener('input', this.formatCPF);
                cpfInput.addEventListener('blur', this.validateCPFFormat);
            }
            
            // Validação do telefone
            const phoneInput = document.getElementById('telefone');
            if (phoneInput) {
                phoneInput.addEventListener('input', this.formatPhone);
            }
        }
        
        formatCPF(event) {
            let value = event.target.value.replace(/\D/g, '');
            
            if (value.length > 11) {
                value = value.substring(0, 11);
            }
            
            // Formatação: 000.000.000-00
            if (value.length > 9) {
                value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
            } else if (value.length > 6) {
                value = value.replace(/(\d{3})(\d{3})(\d{1,3})/, '$1.$2.$3');
            } else if (value.length > 3) {
                value = value.replace(/(\d{3})(\d{1,3})/, '$1.$2');
            }
            
            event.target.value = value;
        }
        
        validateCPFFormat(event) {
            const cpf = event.target.value.replace(/\D/g, '');
            
            if (cpf.length === 11) {
                if (!this.isValidCPF(cpf)) {
                    event.target.classList.add('error');
                    this.showError(event.target, 'CPF inválido');
                } else {
                    event.target.classList.remove('error');
                    this.removeError(event.target);
                }
            } else if (cpf.length > 0) {
                event.target.classList.add('error');
                this.showError(event.target, 'CPF deve ter 11 dígitos');
            }
        }
        
        formatPhone(event) {
            let value = event.target.value.replace(/\D/g, '');
            
            if (value.length > 11) {
                value = value.substring(0, 11);
            }
            
            // Formatação: (00) 00000-0000 para celular ou (00) 0000-0000 para fixo
            if (value.length > 10) {
                value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
            } else if (value.length > 6) {
                value = value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
            } else if (value.length > 2) {
                value = value.replace(/(\d{2})(\d{1,4})/, '($1) $2');
            } else if (value.length > 0) {
                value = value.replace(/(\d{1,2})/, '($1');
            }
            
            event.target.value = value;
        }
        
        isValidCPF(cpf) {
            // Remove caracteres não numéricos
            cpf = cpf.replace(/\D/g, '');
            
            // CPF deve ter 11 dígitos
            if (cpf.length !== 11) return false;
            
            // Verifica se todos os dígitos são iguais
            if (/^(\d)\1{10}$/.test(cpf)) return false;
            
            // Validação dos dígitos verificadores
            let sum = 0;
            let remainder;
            
            // Primeiro dígito verificador
            for (let i = 1; i <= 9; i++) {
                sum += parseInt(cpf.substring(i - 1, i)) * (11 - i);
            }
            
            remainder = (sum * 10) % 11;
            if (remainder === 10 || remainder === 11) remainder = 0;
            if (remainder !== parseInt(cpf.substring(9, 10))) return false;
            
            // Segundo dígito verificador
            sum = 0;
            for (let i = 1; i <= 10; i++) {
                sum += parseInt(cpf.substring(i - 1, i)) * (12 - i);
            }
            
            remainder = (sum * 10) % 11;
            if (remainder === 10 || remainder === 11) remainder = 0;
            if (remainder !== parseInt(cpf.substring(10, 11))) return false;
            
            return true;
        }
        
        showError(input, message) {
            let errorElement = input.nextElementSibling;
            
            // Verificar se já existe um elemento de erro
            if (!errorElement || !errorElement.classList.contains('form-error')) {
                errorElement = document.createElement('span');
                errorElement.className = 'form-error';
                input.parentNode.insertBefore(errorElement, input.nextSibling);
            }
            
            errorElement.textContent = message;
        }
        
        removeError(input) {
            const errorElement = input.nextElementSibling;
            if (errorElement && errorElement.classList.contains('form-error')) {
                errorElement.remove();
            }
        }
    }

    // Inicializar a aplicação
    const app = new ProfileEditApp();
    app.init();
});
    </script>
</body>
</html>