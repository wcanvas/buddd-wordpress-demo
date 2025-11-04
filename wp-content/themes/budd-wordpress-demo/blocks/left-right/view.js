import ACFBlock from '../../assets/js/utils/blocks';
import gsap from 'gsap';

/**
 * LeftRight - Client-side functionality
 */
class LeftRightView {
	constructor(block) {
		this.block = block;
		this.accordions = [];
		this.openAccordionIndex = null;

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

		// Set initial state: first accordion is open
		this.openAccordionIndex = 0;
		this.updateAccordionStates();

		this.accordions.forEach((accordion, index) => {
			const trigger = accordion.querySelector('.js-accordion-trigger');
			if (trigger) {
				trigger.addEventListener('click', () => this.handleAccordionClick(index));
			}
		});
	}

	handleAccordionClick(index) {
		this.openAccordionIndex = this.openAccordionIndex === index ? null : index;
		this.updateAccordionStates();
	}

	updateAccordionStates() {
		this.accordions.forEach((accordion, index) => {
			const content = accordion.querySelector('.js-accordion-content');
			const icon = accordion.querySelector('.js-accordion-icon');
			const trigger = accordion.querySelector('.js-accordion-trigger');

			if (!content || !trigger) {
				return;
			}

			const isOpen = this.openAccordionIndex === index;

			trigger.setAttribute('aria-expanded', isOpen);

			gsap.to(content, {
				height: isOpen ? 'auto' : 0,
				duration: 0.4,
				ease: 'power2.inOut',
			});

			if (icon) {
				// Handle dark variant icon rotation
				gsap.to(icon, {
					rotation: isOpen ? 0 : -180,
					duration: 0.3,
				});
			} else {
				// Handle light variant icon visibility (fallback)
				const iconOpen = accordion.querySelector('.js-accordion-icon-open');
				const iconClosed = accordion.querySelector('.js-accordion-icon-closed');
				if (iconOpen && iconClosed) {
					iconOpen.style.display = isOpen ? 'block' : 'none';
					iconClosed.style.display = isOpen ? 'none' : 'block';
				}
			}
		});
	}
}

new ACFBlock(LeftRightView);