import ACFBlock from '../../assets/js/utils/blocks';

/**
 * Pricing Block - Client-side functionality
 */
class PricingView {
	constructor(block) {
		this.block = block;

		this.init();
	}

	static getName() {
		return 'pricing';
	}

	init() {
		this.initializeToggle();
	}

	initializeToggle() {
		this.toggleSwitch = this.block.querySelector('.js-toggle-switch');
		if (!this.toggleSwitch) {
			return;
		}

		this.monthlyButton = this.toggleSwitch.querySelector('.js-toggle-monthly');
		this.yearlyButton = this.toggleSwitch.querySelector('.js-toggle-yearly');
		this.priceElements = this.block.querySelectorAll('.js-price');

		if (
			!this.monthlyButton ||
			!this.yearlyButton ||
			this.priceElements.length === 0
		) {
			return;
		}

		this.monthlyButton.addEventListener('click', () =>
			this.updatePricing(false)
		);
		this.yearlyButton.addEventListener('click', () => this.updatePricing(true));
	}

	updatePricing(isYearly) {
		const activeClasses = ['wcb-bg-color-7', 'wcb-text-color-42'];
		const inactiveClasses = ['wcb-bg-transparent', 'wcb-text-color-7'];

		if (isYearly) {
			this.yearlyButton.classList.add(...activeClasses);
			this.yearlyButton.classList.remove(...inactiveClasses);
			this.monthlyButton.classList.add(...inactiveClasses);
			this.monthlyButton.classList.remove(...activeClasses);
		} else {
			this.monthlyButton.classList.add(...activeClasses);
			this.monthlyButton.classList.remove(...inactiveClasses);
			this.yearlyButton.classList.add(...inactiveClasses);
			this.yearlyButton.classList.remove(...activeClasses);
		}

		this.priceElements.forEach((priceEl) => {
			const price = isYearly
				? priceEl.dataset.yearlyPrice
				: priceEl.dataset.monthlyPrice;
			if (price) {
				priceEl.textContent = price;
			}
		});
	}
}

new ACFBlock(PricingView);