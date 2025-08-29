import ACFBlock from '../../assets/js/utils/blocks';

/**
 * Navbar - Client-side functionality
 */
class NavbarView {
	constructor(block) {
		this.block = block;

		// Selectors
		this.toggleButton = this.block.querySelector('.js-navbar-toggle');
		this.menu = this.block.querySelector('.js-navbar-menu');
		this.openIcon = this.block.querySelector('.js-navbar-open-icon');
		this.closeIcon = this.block.querySelector('.js-navbar-close-icon');

		this.init();
	}

	static getName() {
		return 'navbar';
	}

	init() {
		if (!this.toggleButton || !this.menu || !this.openIcon || !this.closeIcon) {
			return;
		}
		this.addEventListeners();
	}

	addEventListeners() {
		this.toggleButton.addEventListener('click', this.toggleMenu.bind(this));
	}

	toggleMenu() {
		const isExpanded = this.toggleButton.getAttribute('aria-expanded') === 'true';

		this.toggleButton.setAttribute('aria-expanded', !isExpanded);
		this.menu.classList.toggle('wcb-hidden');
		this.openIcon.classList.toggle('wcb-hidden');
		this.closeIcon.classList.toggle('wcb-hidden');
	}
}

new ACFBlock(NavbarView);