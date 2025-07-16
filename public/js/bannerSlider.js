class BannerSlider {
    constructor() {
        this.banner = document.querySelector('.home-user-banner');
        this.images = document.querySelectorAll('.home-user-banner-image');
        this.prevBtn = document.querySelector('.home-user-banner-prev');
        this.nextBtn = document.querySelector('.home-user-banner-next');
        this.currentIndex = 0;
        this.totalImages = this.images.length;
        this.autoplay = null;

        this.initialize();
    }

    initialize() {
        this.createDots();
        this.initializeSlider();
        this.setupEventListeners();
        this.startAutoplay();
    }

    createDots() {
        const dotsContainer = document.createElement('div');
        dotsContainer.className = 'home-user-banner-dots';
        this.banner.parentNode.appendChild(dotsContainer);

        for (let i = 0; i < this.totalImages; i++) {
            const dot = document.createElement('div');
            dot.className = 'home-user-banner-dot';
            if (i === 0) dot.classList.add('active');
            dot.addEventListener('click', () => this.goToSlide(i));
            dotsContainer.appendChild(dot);
        }

        this.dots = document.querySelectorAll('.home-user-banner-dot');
    }

    initializeSlider() {
        this.images.forEach((img, index) => {
            img.style.transform = `translateX(${index * 100}%)`;
        });
    }

    updateSlider() {
        this.images.forEach((img, index) => {
            img.style.transform = `translateX(${100 * (index - this.currentIndex)}%)`;
        });

        this.dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === this.currentIndex);
        });
    }

    goToSlide(index) {
        this.currentIndex = index;
        this.updateSlider();
    }

    nextSlide() {
        this.currentIndex = (this.currentIndex + 1) % this.totalImages;
        this.updateSlider();
    }

    prevSlide() {
        this.currentIndex = (this.currentIndex - 1 + this.totalImages) % this.totalImages;
        this.updateSlider();
    }

    handleSwipe(touchStartX, touchEndX) {
        const threshold = 50;
        if (touchEndX < touchStartX - threshold) {
            this.nextSlide();
        } else if (touchEndX > touchStartX + threshold) {
            this.prevSlide();
        }
    }

    setupEventListeners() {
        this.nextBtn.addEventListener('click', () => this.nextSlide());
        this.prevBtn.addEventListener('click', () => this.prevSlide());

        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowRight') this.nextSlide();
            if (e.key === 'ArrowLeft') this.prevSlide();
        });

        let touchStartX = 0;
        this.banner.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });

        this.banner.addEventListener('touchend', (e) => {
            const touchEndX = e.changedTouches[0].screenX;
            this.handleSwipe(touchStartX, touchEndX);
        }, { passive: true });

        this.banner.parentNode.addEventListener('mouseenter', () => this.pauseAutoplay());
        this.banner.parentNode.addEventListener('mouseleave', () => this.startAutoplay());
    }

    startAutoplay() {
        this.autoplay = setInterval(() => this.nextSlide(), 5000);
    }

    pauseAutoplay() {
        clearInterval(this.autoplay);
    }
}
