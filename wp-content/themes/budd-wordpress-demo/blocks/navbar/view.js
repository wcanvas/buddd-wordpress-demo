import ACFBlock from '../../assets/js/utils/blocks';

/**
 * NavbarView - Client-side functionality for the Navbar block
 */
class NavbarView {
	constructor(block) {
		this.block = block;
		this.isMenuOpen = false;
		this.lastScrollY = window.scrollY;

		this.init();
	}

	static getName() {
		return 'navbar';
	}

	init() {
		this.cacheDOMElements();
		this.addEventListeners();
	}

	cacheDOMElements() {
		this.header = this.block.querySelector('.js-navbar-header');
		this.menuToggle = this.block.querySelector('.js-menu-toggle');
		this.mobileMenu = this.block.querySelector('.js-mobile-menu');
		this.hamburgerIcon = this.block.querySelector('.js-hamburger-icon');
		this.closeIcon = this.block.querySelector('.js-close-icon');
	}

	addEventListeners() {
		if (this.menuToggle) {
			this.menuToggle.addEventListener('click', this.toggleMenu.bind(this));
		}
		window.addEventListener('scroll', this.handleScroll.bind(this), { passive: true });
	}

	toggleMenu() {
		this.isMenuOpen = !this.isMenuOpen;

		if (this.mobileMenu) {
			this.mobileMenu.classList.toggle('wcb-hidden', !this.isMenuOpen);
		}

		if (this.hamburgerIcon && this.closeIcon) {
			this.hamburgerIcon.classList.toggle('wcb-hidden', this.isMenuOpen);
			this.closeIcon.classList.toggle('wcb-hidden', !this.isMenuOpen);
		}

		document.body.style.overflow = this.isMenuOpen ? 'hidden' : '';
	}

	closeMenu() {
		if (!this.isMenuOpen) return;
		this.toggleMenu();
	}

	handleScroll() {
		if (!this.header) return;

		const currentScrollY = window.scrollY;

		if (currentScrollY > this.lastScrollY && currentScrollY > 100) {
			// Scrolling down
			this.header.classList.add('-wcb-translate-y-full');
			this.closeMenu();
		} else {
			// Scrolling up
			this.header.classList.remove('-wcb-translate-y-full');
		}

		this.lastScrollY = currentScrollY;
	}
}

new ACFBlock(NavbarView);