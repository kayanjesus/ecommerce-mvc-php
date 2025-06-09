let currentSlide = 0; 
const slides = document.querySelectorAll('.banner-slide');

const indicators = document.querySelectorAll('.indicator');

function changeSlide(direction) {  
    
slides[currentSlide].classList.remove('active');
   
indicators[currentSlide].classList.remove('active');

  currentSlide = (currentSlide + direction + slides.length) % slides.length;


slides[currentSlide].classList.add('active');
 

indicators[currentSlide].classList.add('active');

}

function goToSlide(index) { 
    
slides[currentSlide].classList.remove('active');
 
indicators[currentSlide].classList.remove('active');

  currentSlide = index;


slides[currentSlide].classList.add('active');

  
indicators[currentSlide].classList.add('active'); 
}

setInterval(() => {
  changeSlide(1);
}, 3000); 