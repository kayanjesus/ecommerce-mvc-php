document.addEventListener('DOMContentLoaded', function() {
    // =============================================
    // CARROSSEL DE IMAGENS DO PRODUTO
    // =============================================
    const initCarousel = () => {
        const track = document.querySelector('.carousel-track');
        const slides = Array.from(document.querySelectorAll('.carousel-slide'));
        const thumbnails = Array.from(document.querySelectorAll('.thumbnail'));
        const nextButton = document.querySelector('.carousel-button.next');
        const prevButton = document.querySelector('.carousel-button.prev');
        
        if (!track || slides.length === 0) return;
        
        let currentIndex = 0;
        let autoRotateInterval;
        const autoRotateDelay = 5000; // 5 segundos

        // Função para atualizar a posição do carrossel
        const updateCarousel = () => {
            track.style.transform = `translateX(-${currentIndex * 100}%)`; // Corrigido
            updateThumbnails();
            updateButtonStates();
        };

        // Atualiza as miniaturas ativas
        const updateThumbnails = () => {
            thumbnails.forEach((thumb, index) => {
                thumb.classList.toggle('active', index === currentIndex);
            });
        };

        // Atualiza o estado dos botões
        const updateButtonStates = () => {
            if (prevButton) prevButton.disabled = currentIndex === 0;
            if (nextButton) nextButton.disabled = currentIndex === slides.length - 1;
        };

        // Navega para o próximo slide
        const nextSlide = () => {
            if (currentIndex < slides.length - 1) {
                currentIndex++;
            } else {
                currentIndex = 0;
            }
            updateCarousel();
        };

        // Navega para o slide anterior
        const prevSlide = () => {
            if (currentIndex > 0) {
                currentIndex--;
            } else {
                currentIndex = slides.length - 1;
            }
            updateCarousel();
        };

        // Inicia a rotação automática
        const startAutoRotate = () => {
            autoRotateInterval = setInterval(nextSlide, autoRotateDelay);
        };

        // Para a rotação automática
        const stopAutoRotate = () => {
            clearInterval(autoRotateInterval);
        };

        // Event listeners
        if (nextButton) nextButton.addEventListener('click', () => {
            nextSlide();
            stopAutoRotate();
            startAutoRotate();
        });

        if (prevButton) prevButton.addEventListener('click', () => {
            prevSlide();
            stopAutoRotate();
            startAutoRotate();
        });

        thumbnails.forEach((thumbnail, index) => {
            thumbnail.addEventListener('click', () => {
                currentIndex = index;
                updateCarousel();
                stopAutoRotate();
                startAutoRotate();
            });
        });

        // Pausa ao interagir
        track.addEventListener('mouseenter', stopAutoRotate);
        track.addEventListener('mouseleave', startAutoRotate);

        // Inicializa
        updateCarousel();
        startAutoRotate();
    };

    // =============================================
    // SELEÇÃO DE TAMANHO
    // =============================================
    const initSizeSelection = () => {
        const sizeButtons = document.querySelectorAll('.size-buttons button');
        
        sizeButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove a seleção de todos os botões
                sizeButtons.forEach(btn => {
                    btn.classList.remove('selected');
                    btn.style.backgroundColor = '';
                    btn.style.color = '';
                });
                
                // Adiciona seleção ao botão clicado
                this.classList.add('selected');
                this.style.backgroundColor = '#e63946';
                this.style.color = 'white';
            });
        });
    };

    // =============================================
    // FAVORITOS
    // =============================================
    const initWishlist = () => {
        const wishlistCheckboxes = document.querySelectorAll('.wishlist-option input[type="checkbox"]');
        
        wishlistCheckboxes.forEach(checkbox => {
            const label = checkbox.nextElementSibling;
            
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    label.innerHTML = '<i class="fas fa-heart"></i> Adicionado aos favoritos';
                    label.style.color = '#e63946';
                } else {
                    label.innerHTML = '<i class="far fa-heart"></i> Adicionar aos favoritos';
                    label.style.color = '#333';
                }
            });
        });
    };

    // =============================================
    // CONTROLE DE QUANTIDADE
    // =============================================
    const initQuantityControls = () => {
        const quantityControls = document.querySelectorAll('.quantity-controls');
        
        quantityControls.forEach(control => {
            const minusBtn = control.querySelector('.quantity-minus');
            const plusBtn = control.querySelector('.quantity-plus');
            const input = control.querySelector('input');
            
            if (minusBtn && plusBtn && input) {
                minusBtn.addEventListener('click', () => {
                    let value = parseInt(input.value);
                    if (value > 1) {
                        input.value = value - 1;
                    }
                });
                
                plusBtn.addEventListener('click', () => {
                    let value = parseInt(input.value);
                    input.value = value + 1;
                });
                
                input.addEventListener('change', () => {
                    if (parseInt(input.value) < 1) {
                        input.value = 1;
                    }
                });
            }
        });
    };

    // =============================================
    // AVALIAÇÃO POR ESTRELAS
    // =============================================
    const initStarRating = () => {
        const ratingContainers = document.querySelectorAll('.rating');
        
        ratingContainers.forEach(container => {
            const stars = container.querySelectorAll('.star');
            
            stars.forEach((star, index) => {
                star.addEventListener('click', () => {
                    // Atualiza todas as estrelas
                    stars.forEach((s, i) => {
                        if (i <= index) {
                            s.textContent = '★';
                            s.style.color = 'gold';
                        } else {
                            s.textContent = '☆';
                            s.style.color = '#ccc';
                        }
                    });
                    
                    // Aqui você pode adicionar código para enviar a avaliação
                    console.log('Avaliação:', index + 1);
                });
            });
        });
    };

    // =============================================
    // INTERAÇÕES DE PAGAMENTO
    // =============================================
    const initPaymentOptions = () => {
        // Aqui você pode adicionar lógica para manipular os métodos de pagamento
        console.log('Opções de pagamento inicializadas');
        
        // Exemplo: Destacar método de pagamento selecionado
        const paymentOptions = document.querySelectorAll('.payment-option');
        
        paymentOptions.forEach(option => {
            option.addEventListener('click', function() {
                paymentOptions.forEach(opt => {
                    opt.style.borderColor = '#ddd';
                    opt.style.backgroundColor = '';
                });
                
                this.style.borderColor = '#e63946';
                this.style.backgroundColor = '#ffeeee';
            });
        });
    };

    // =============================================
    // MENU MOBILE (se aplicável)
    // =============================================
    const initMobileMenu = () => {
        const menuToggle = document.querySelector('.menu-toggle');
        const mainNav = document.querySelector('.main-nav');
        
        if (menuToggle && mainNav) {
            menuToggle.addEventListener('click', function() {
                mainNav.classList.toggle('active');
                this.classList.toggle('open');
            });
        }
    };

    // =============================================
    // INICIALIZAÇÃO DE TODOS OS COMPONENTES
    // =============================================
    initCarousel();
    initSizeSelection();
    initWishlist();
    initQuantityControls();
    initStarRating();
    initPaymentOptions();
    initMobileMenu();

    // =============================================
    // FUNÇÕES ADICIONAIS
    // =============================================
    
    // Adicionar ao carrinho
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            const product = this.closest('.product-display');
            const productName = product.querySelector('.product-name').textContent;
            const quantity = product.querySelector('.quantity-controls input')?.value || 1;
            const size = product.querySelector('.size-buttons .selected')?.textContent || '';
            
            console.log('Adicionado ao carrinho:', {
                name: productName,
                quantity: quantity,
                size: size
            });
            
            // Aqui você pode adicionar a lógica real para adicionar ao carrinho
            alert(`${quantity}x ${productName} ${size ? '(Tamanho: ' + size + ')' : ''} adicionado ao carrinho!`); // Corrigido
        });
    });

    // Pesquisa
    const searchInput = document.querySelector('.search-section input');
    if (searchInput) {
        searchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                console.log('Pesquisar por:', this.value);
                // Aqui você pode adicionar a lógica de pesquisa
            }
        });
    }
});
