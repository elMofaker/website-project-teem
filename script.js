// DOM Elements
const dom = {
    slider: document.querySelector('.slider'),
    slides: document.querySelectorAll('.slide'),
    prevBtn: document.querySelector('.prev-btn'),
    nextBtn: document.querySelector('.next-btn'),
    dotsContainer: document.querySelector('.slider-dots'),
    burgerMenu: document.getElementById('burger-menu'),
    navList: document.getElementById('main-nav-list'),
    dropdowns: document.querySelectorAll('.dropdown')
};

// Slider State
let currentIndex = 0;
const totalSlides = dom.slides.length;

// Initialize Slider
const initSlider = () => {
    dom.dotsContainer.innerHTML = Array.from({ length: totalSlides },
        (_, i) => `<span class="dot" data-index="${i}"></span>`).join('');

    const updateSlider = () => {
        dom.slider.style.transform = `translateX(-${currentIndex * 100}%)`;
        document.querySelectorAll('.dot').forEach(dot =>
            dot.classList.toggle('active', +dot.dataset.index === currentIndex));
    };

    const goToSlide = index => {
        currentIndex = (index + totalSlides) % totalSlides;
        updateSlider();
    };

    dom.dotsContainer.addEventListener('click', e => {
        if (e.target.classList.contains('dot')) goToSlide(+e.target.dataset.index);
    });

    dom.prevBtn.addEventListener('click', () => goToSlide(currentIndex - 1));
    dom.nextBtn.addEventListener('click', () => goToSlide(currentIndex + 1));
    goToSlide(0);
};

// Mobile Menu & Dropdowns
const handleMobileNav = () => {
    dom.burgerMenu.classList.toggle('active');
    dom.navList.classList.toggle('nav-active');
};

const handleDropdowns = e => {
    const isDropdownToggle = e.target.closest('.dropdown > a');
    const dropdown = e.target.closest('.dropdown');

    if (isDropdownToggle) {
        e.preventDefault();
        dropdown.classList.toggle('dropdown-active');
    }

    if (!e.target.closest('.dropdown')) {
        dom.dropdowns.forEach(d => d.classList.remove('dropdown-active'));
    }
};

// Event Listeners
dom.burgerMenu.addEventListener('click', handleMobileNav);
document.addEventListener('click', e => {
    handleDropdowns(e);
    if (!e.target.closest('header')) dom.navList.classList.remove('nav-active');
});

// Initialize
initSlider();


// contact
document.getElementById("contactForm").addEventListener("submit", function (e) {
    e.preventDefault();
    const name = document.getElementById("fullName").value;
    const subject = document.getElementById("subject").value;
    const message = document.getElementById("message").value;

    console.log("Full Name:", name);
    console.log("Subject:", subject);
    console.log("Message:", message);

    alert("Your message has been sent!");
});