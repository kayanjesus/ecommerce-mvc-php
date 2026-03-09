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
<style>
        /* Estilo para o olhinho da senha */
        .password-container {
            position: relative;
        }
        
        .password-container input {
            padding-right: 45px !important;
        }
        
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #999;
            transition: color 0.3s;
            font-size: 1.2rem;
            z-index: 10;
        }
        
        .password-toggle:hover {
            color: #9b2a2a;
        }
        
        /* Modal de exclusão */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        
        .modal-overlay.show {
            display: flex;
        }
        
        .modal-content {
            background: white;
            border-radius: 8px;
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .modal-header {
            padding: 15px 20px;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-body {
            padding: 20px;
        }
        
        .modal-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 20px;
        }
    </style>
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
                                       class="form-input"
                                       value="{{ old('name', $user->name) }}" 
                                       required>
                            </div>

                            <div class="form-group">
                                <label for="cpf" class="form-label">
                                    CPF <span class="required">*</span>
                                </label>
                                <input type="text" id="cpf" name="cpf" 
                                       class="form-input"
                                       value="{{ old('cpf', $user->cpf ?? '') }}" 
                                       required 
                                       placeholder="000.000.000-00">
                            </div>

                            <div class="form-group">
                                <label for="data_nasc" class="form-label">
                                    Data de Nascimento <span class="required">*</span>
                                </label>
                                <input type="date" id="data_nasc" name="data_nasc" 
                                       class="form-input"
                                       value="{{ old('data_nasc', $user->data_nasc ? \Carbon\Carbon::parse($user->data_nasc)->format('Y-m-d') : '') }}" 
                                       required>
                            </div>

                            <div class="form-group">
                                <label for="email" class="form-label">
                                    Endereço de E-mail <span class="required">*</span>
                                </label>
                                <input type="email" id="email" name="email" 
                                       class="form-input"
                                       value="{{ old('email', $user->email) }}" 
                                       required>
                            </div>

                            <div class="form-group">
                                <label for="telefone" class="form-label">
                                    Telefone
                                </label>
                                <input type="tel" id="telefone" name="telefone" 
                                       class="form-input"
                                       value="{{ old('telefone', $user->telefone ?? '') }}" 
                                       placeholder="(00) 00000-0000">
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i>
                                    Salvar Alterações
                                </button>
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
                                <div class="password-container">
                                    <input type="password" id="current_password" name="current_password" 
                                           class="form-input" required>
                                    <i class="fas fa-eye password-toggle" onclick="togglePassword('current_password', this)"></i>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password" class="form-label">
                                    Nova Senha <span class="required">*</span>
                                </label>
                                <div class="password-container">
                                    <input type="password" id="password" name="password" 
                                           class="form-input" required>
                                    <i class="fas fa-eye password-toggle" onclick="togglePassword('password', this)"></i>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation" class="form-label">
                                    Confirmar Nova Senha <span class="required">*</span>
                                </label>
                                <div class="password-container">
                                    <input type="password" id="password_confirmation" name="password_confirmation" 
                                           class="form-input" required>
                                    <i class="fas fa-eye password-toggle" onclick="togglePassword('password_confirmation', this)"></i>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-key"></i>
                                    Alterar Senha
                                </button>
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
                            
                            <button type="button" class="btn btn-danger" onclick="abrirModalExclusao()">
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
                <button type="button" class="btn-close" onclick="fecharModalExclusao()"></button>
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
                        <div class="password-container">
                            <input type="password" id="delete-password" name="password" 
                                   class="form-input" required 
                                   placeholder="Sua senha atual">
                            <i class="fas fa-eye password-toggle" onclick="togglePassword('delete-password', this)"></i>
                        </div>
                    </div>
                    
                    <div class="modal-actions">
                        <button type="button" class="btn btn-outline" onclick="fecharModalExclusao()">
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
        // ============================================
        // FUNÇÃO DO OLHINHO (TOGGLE PASSWORD)
        // ============================================
        function togglePassword(inputId, icon) {
            const input = document.getElementById(inputId);
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // ============================================
        // MODAL DE EXCLUSÃO
        // ============================================
        const modal = document.getElementById('delete-modal');

        function abrirModalExclusao() {
            modal.classList.add('show');
        }

        function fecharModalExclusao() {
            modal.classList.remove('show');
        }

        // Fechar modal ao clicar fora
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                fecharModalExclusao();
            }
        });

        // ============================================
        // NAVEGAÇÃO ENTRE SEÇÕES (SIMPLES)
        // ============================================
        document.addEventListener('DOMContentLoaded', function() {
            const navItems = document.querySelectorAll('.nav-item[data-section]');
            const sections = {
                'profile-info': document.getElementById('profile-info-section'),
                'password': document.getElementById('password-section'),
                'danger-zone': document.getElementById('danger-zone-section')
            };

            function showSection(sectionId) {
                // Esconde todas
                Object.values(sections).forEach(section => {
                    if (section) section.style.display = 'none';
                });
                
                // Mostra a selecionada
                if (sections[sectionId]) {
                    sections[sectionId].style.display = 'block';
                }

                // Atualiza classe active
                navItems.forEach(item => {
                    if (item.dataset.section === sectionId) {
                        item.classList.add('active');
                    } else {
                        item.classList.remove('active');
                    }
                });
            }

            navItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    showSection(this.dataset.section);
                });
            });

            // Mostra seção inicial
            showSection('profile-info');
        });
    </script>
</body>
</html>