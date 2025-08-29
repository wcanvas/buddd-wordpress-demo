import ACFBlock from '../../assets/js/utils/blocks';
import gsap from 'gsap';

/**
 * StatsWithImage Block - Client-side functionality
 */
class StatsWithImageView {
	constructor(block) {
		this.block = block;
		this.accordionItems = this.block.querySelectorAll('.js-accordion-item');

		this.init();
	}

	static getName() {
		return 'stats-with-image';
	}

	init() {
		if (this.accordionItems.length > 0) {
			this.initAccordions();
		}
	}

	initAccordions() {
		this.accordionItems.forEach((item, index) => {
			const trigger = item.querySelector('.js-accordion-trigger');
			if (trigger) {
				trigger.addEventListener('click', () => this.toggleAccordion(item));
			}

			if (index === 0) {
				// Open first item by default, no animation on load
				this.openAccordion(item, 0);
			} else {
				// Ensure all others are closed, no animation on load
				this.closeAccordion(item, 0);
			}
		});
	}

	toggleAccordion(itemToToggle) {
		const isOpening = !itemToToggle.classList.contains('is-open');

		// Close all other accordions
		this.accordionItems.forEach((item) => {
			if (item !== itemToToggle) {
				this.closeAccordion(item);
			}
		});

		// Toggle the clicked one
		if (isOpening) {
			this.openAccordion(itemToToggle);
		} else {
			this.closeAccordion(itemToToggle);
		}
	}

	openAccordion(item, duration = 0.4) {
		if (!item || item.classList.contains('is-open')) {
			return;
		}

		item.classList.add('is-open');
		const trigger = item.querySelector('.js-accordion-trigger');
		const content = item.querySelector('.js-accordion-content');
		const iconOpen = item.querySelector('.js-accordion-icon-open');
		const iconClosed = item.querySelector('.js-accordion-icon-closed');

		if (trigger) {
			trigger.setAttribute('aria-expanded', 'true');
		}
		if (iconOpen) {
			iconOpen.style.display = 'block';
		}
		if (iconClosed) {
			iconClosed.style.display = 'none';
		}

		if (content) {
			gsap.to(content, {
				height: 'auto',
				duration,
				ease: 'power2.inOut',
			});
		}
	}

	closeAccordion(item, duration = 0.4) {
		if (!item || (!item.classList.contains('is-open') && duration > 0)) {
			return;
		}

		item.classList.remove('is-open');
		const trigger = item.querySelector('.js-accordion-trigger');
		const content = item.querySelector('.js-accordion-content');
		const iconOpen = item.querySelector('.js-accordion-icon-open');
		const iconClosed = item.querySelector('.js-accordion-icon-closed');

		if (trigger) {
			trigger.setAttribute('aria-expanded', 'false');
		}
		if (iconOpen) {
			iconOpen.style.display = 'none';
		}
		if (iconClosed) {
			iconClosed.style.display = 'block';
		}

		if (content) {
			gsap.to(content, {
				height: 0,
				duration,
				ease: 'power2.inOut',
			});
		}
	}
}

new ACFBlock(StatsWithImageView);