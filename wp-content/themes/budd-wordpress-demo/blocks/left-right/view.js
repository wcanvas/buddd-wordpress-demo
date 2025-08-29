import ACFBlock from '../../assets/js/utils/blocks';
import gsap from 'gsap';

/**
 * LeftRightView - Client-side functionality for the Left Right block.
 */
class LeftRightView {
	constructor(block) {
		this.block = block;
		this.accordions = [];
		this.init();
	}

	static getName() {
		return 'left-right';
	}

	init() {
		this.initializeAccordions();
	}

	initializeAccordions() {
		this.accordions = this.block.querySelectorAll('.js-accordion-item');
		if (!this.accordions.length) {
			return;
		}

		// Set the first accordion to be open by default, matching React's useState(0)
		const firstAccordion = this.accordions[0];
		if (firstAccordion) {
			this.openAccordion(firstAccordion);
		}

		this.accordions.forEach((accordion) => {
			const trigger = accordion.querySelector('.js-accordion-trigger');
			if (trigger) {
				trigger.addEventListener('click', () =>
					this.handleAccordionClick(accordion)
				);
			}
		});
	}

	handleAccordionClick(clickedAccordion) {
		const isOpening = !clickedAccordion.classList.contains('is-open');

		// Close any currently open accordion
		this.accordions.forEach((accordion) => {
			if (accordion.classList.contains('is-open')) {
				this.closeAccordion(accordion);
			}
		});

		// If the clicked accordion was not already open, open it
		if (isOpening) {
			this.openAccordion(clickedAccordion);
		}
	}

	openAccordion(accordion) {
		if (!accordion) return;

		accordion.classList.add('is-open');
		const trigger = accordion.querySelector('.js-accordion-trigger');
		const content = accordion.querySelector('.js-accordion-content');

		if (trigger) {
			trigger.setAttribute('aria-expanded', 'true');
		}
		if (content) {
			gsap.to(content, {
				height: 'auto',
				duration: 0.4,
				ease: 'power2.inOut',
			});
		}
	}

	closeAccordion(accordion) {
		if (!accordion) return;

		accordion.classList.remove('is-open');
		const trigger = accordion.querySelector('.js-accordion-trigger');
		const content = accordion.querySelector('.js-accordion-content');

		if (trigger) {
			trigger.setAttribute('aria-expanded', 'false');
		}
		if (content) {
			gsap.to(content, {
				height: 0,
				duration: 0.4,
				ease: 'power2.inOut',
			});
		}
	}
}

new ACFBlock(LeftRightView);