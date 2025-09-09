import ACFBlock from '../../assets/js/utils/blocks';

/**
 * NavbarView - Client-side functionality
 */
class NavbarView {
	constructor(block) {
		this.block = block;
		this.lastScrollY = window.scrollY;
		this.isMenuOpen = false;

		this.init();
	}

	static getName() {
		return 'navbar';
	}

	init() {
		this.initDOM();
		this.initEvents();
	}

	initDOM() {
		this.mobileMenuToggle = this.block.querySelector('.js-mobile-menu-toggle');
		this.mobileMenu = this.block.querySelector('.js-mobile-menu');
		this.openIcon = this.block.querySelector('.js-hamburger-icon');
		this.closeIcon = this.block.querySelector('.js-close-icon');
	}

	initEvents() {
		if (this.mobileMenuToggle) {
			this.mobileMenuToggle.addEventListener('click', this.handleMenuToggle.bind(this));
		}
		window.addEventListener('scroll', this.handleScroll.bind(this), { passive: true });
	}

	handleScroll() {
		const currentScrollY = window.scrollY;
		const isScrollingDown = currentScrollY > this.lastScrollY;
		const shouldHide = currentScrollY > 100 && isScrollingDown;

		this.block.classList.toggle('-wcb-translate-y-full', shouldHide);
		this.block.classList.toggle('wcb-translate-y-0', !shouldHide);

		if (shouldHide && this.isMenuOpen) {
			this.closeMenu();
		}

		this.lastScrollY = currentScrollY;
	}

	handleMenuToggle() {
		this.isMenuOpen = !this.isMenuOpen;
		this.updateMenuState();
	}

	closeMenu() {
		if (!this.isMenuOpen) return;
		this.isMenuOpen = false;
		this.updateMenuState();
	}

	updateMenuState() {
		if (!this.mobileMenu || !this.mobileMenuToggle || !this.openIcon || !this.closeIcon) {
			return;
		}

		this.mobileMenu.classList.toggle('wcb-max-h-screen', this.isMenuOpen);
		this.mobileMenu.classList.toggle('wcb-max-h-0', !this.isMenuOpen);

		this.openIcon.classList.toggle('wcb-hidden', this.isMenuOpen);
		this.closeIcon.classList.toggle('wcb-hidden', !this.isMenuOpen);

		this.mobileMenuToggle.setAttribute('aria-expanded', this.isMenuOpen.toString());
	}
}

new ACFBlock(NavbarView);