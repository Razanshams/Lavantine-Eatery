/*index.js*/

const slides = document.querySelectorAll('.slide');
let currentSlide = 0;

//goes through all the images with class slide and goes to next one every time function is called 
function nextSlide() {
    slides[currentSlide].classList.remove('active');
    currentSlide = (currentSlide + 1) % slides.length;
    slides[currentSlide].classList.add('active');
}

setInterval(nextSlide, 3000);