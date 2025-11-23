let slideIndex = 0;

function showSlides() {
    const slides = document.querySelectorAll('.slide');
    slides.forEach((slide, index) => {
        slide.style.display = (index === slideIndex) ? 'block' : 'none';
    });
}

function moveSlide(n) {
    const slides = document.querySelectorAll('.slide');
    slideIndex += n;
    if (slideIndex >= slides.length) {
        slideIndex = 0; // Loop back to first slide
    }
    if (slideIndex < 0) {
        slideIndex = slides.length - 1; // Loop to last slide
    }
    showSlides();
}

// Initial call to show the first slide
showSlides();