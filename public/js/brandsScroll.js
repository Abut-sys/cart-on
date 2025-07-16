class BrandsScroll {
    constructor() {
        this.container = document.querySelector('.home-user-brands-container');
        this.inner = document.querySelector('.home-user-brands-inner');
        this.scrollSpeed = 1;
        this.scrollPos = 0;
        this.animationId = null;

        this.initialize();
    }

    initialize() {
        if (!this.container || !this.inner) return;

        this.cloneBrands();
        this.setupEventListeners();
        this.startAnimation();
    }

    cloneBrands() {
        const brands = document.querySelectorAll('.home-user-brand');
        brands.forEach(brand => {
            const clone = brand.cloneNode(true);
            this.inner.appendChild(clone);
        });
    }

    animateBrands() {
        this.scrollPos += this.scrollSpeed;
        const brands = document.querySelectorAll('.home-user-brand');
        const brandWidth = brands[0]?.offsetWidth + 30;

        if (this.scrollPos >= brandWidth * brands.length / 2) {
            this.scrollPos = 0;
        }

        this.inner.style.transform = `translateX(-${this.scrollPos}px)`;
        this.animationId = requestAnimationFrame(() => this.animateBrands());
    }

    startAnimation() {
        setTimeout(() => {
            this.animationId = requestAnimationFrame(() => this.animateBrands());
        }, 1000);
    }

    setupEventListeners() {
        this.container.addEventListener('mouseenter', () => {
            this.scrollSpeed = 0;
        });

        this.container.addEventListener('mouseleave', () => {
            this.scrollSpeed = 1;
        });
    }
}
