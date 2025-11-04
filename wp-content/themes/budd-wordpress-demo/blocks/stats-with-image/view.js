import ACFBlock from '../../assets/js/utils/blocks';

/**
 * StatsWithImage Block - Client-side functionality
 */
class StatsWithImageView {
	/**
	 * Constructor.
	 * @param {HTMLElement} block - The block element.
	 */
	constructor(block) {
		this.block = block;
		this.init();
	}

	/**
	 * Get the block name.
	 * @returns {string} The block name.
	 */
	static getName() {
		return 'stats-with-image';
	}

	/**
	 * Initialize the block.
	 */
	init() {
		this.initializeAccordion();
	}

	/**
	 * Initialize accordion functionality.
	 */
	initializeAccordion() {
		this.accordionItems = this.block.querySelectorAll('.js-accordion-item');
		if (!this.accordionItems.length) {
			return;
		}

		// Open the first accordion item by default, matching the React component's initial state.
		if (this.accordionItems[0]) {
			this.openAccordion(this.accordionItems[0]);
		}

		this.accordionItems.forEach((item) => {
			const trigger = item.querySelector('.js-accordion-trigger');
			if (trigger) {
				trigger.addEventListener('click', () => {
					if (item.classList.contains('is-open')) {
						// If it's already open, close it.
						this.closeAccordion(item);
					} else {
						// If it's closed, close all others and open this one.
						this.accordionItems.forEach((otherItem) => {
							if (otherItem !== item) {
								this.closeAccordion(otherItem);
							}
						});
						this.openAccordion(item);
					}
				});
			}
		});
	}

	/**
	 * Open a specific accordion item.
	 * @param {HTMLElement} item - The accordion item to open.
	 */
	openAccordion(item) {
		if (!item) {
			return;
		}

		const trigger = item.querySelector('.js-accordion-trigger');
		const content = item.querySelector('.js-accordion-content');
		const iconOpen = item.querySelector('.js-accordion-icon-open');
		const iconClosed = item.querySelector('.js-accordion-icon-closed');

		item.classList.add('is-open');
		if (trigger) {
			trigger.setAttribute('aria-expanded', 'true');
		}
		if (content) {
			content.style.height = content.scrollHeight + 'px';
		}

		if (iconOpen && iconClosed) {
			iconOpen.style.display = 'block';
			iconClosed.style.display = 'none';
		}
	}

	/**
	 * Close a specific accordion item.
	 * @param {HTMLElement} item - The accordion item to close.
	 */
	closeAccordion(item) {
		if (!item) {
			return;
		}

		const trigger = item.querySelector('.js-accordion-trigger');
		const content = item.querySelector('.js-accordion-content');
		const iconOpen = item.querySelector('.js-accordion-icon-open');
		const iconClosed = item.querySelector('.js-accordion-icon-closed');

		item.classList.remove('is-open');
		if (trigger) {
			trigger.setAttribute('aria-expanded', 'false');
		}
		if (content) {
			content.style.height = '0px';
		}

		if (iconOpen && iconClosed) {
			iconOpen.style.display = 'none';
			iconClosed.style.display = 'block';
		}
	}
}

new ACFBlock(StatsWithImageView);