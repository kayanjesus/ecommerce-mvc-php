let currentSlide = 0; // índice do slide atual
const slides = document.querySelectorAll('.banner-slide');
// seleciona todos os slides
const indicators = document.querySelectorAll('.indicator');
// seleciona todos os indicadores


// Função para mudar o slide
function changeSlide(direction) {  
    
slides[currentSlide].classList.remove('active');
 // remove a classe 'active' do slide atual
   
indicators[currentSlide].classList.remove('active');
// remove a classe 'active' do indicador atual
  currentSlide = (currentSlide + direction + slides.length) % slides.length;
// atualiza o índice do slide atual  

slides[currentSlide].classList.add('active');
// adiciona a classe 'active' ao próximo slide  

indicators[currentSlide].classList.add('active');
// adiciona a classe 'active' ao indicador correspondente
}

// Função para ir a um slide específico
function goToSlide(index) { 
    
slides[currentSlide].classList.remove('active');
// remove a classe 'active' do slide atual
 
indicators[currentSlide].classList.remove('active');
// remove a classe 'active' do indicador atual
  currentSlide = index;
// atualiza o índice do slide atual para o índice fornecido

slides[currentSlide].classList.add('active');
// adiciona a classe 'active' ao slide selecionado
  
indicators[currentSlide].classList.add('active'); 
// adiciona a classe 'active' ao indicador correspondente
}

// Função para mudar os slides automaticamente a cada 5 segundos (opcional)
setInterval(() => {
  changeSlide(1);
}, 3000); 