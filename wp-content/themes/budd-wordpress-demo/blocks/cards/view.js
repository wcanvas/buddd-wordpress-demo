import ACFBlock from '../../assets/js/utils/blocks';

/**
 * Cards - Client-side functionality
 */
class CardsView {
	constructor(block) {
		this.block = block;
		// this.init();
	}

	static getName() {
		return 'cards';
	}

	init() {
		// No interactive behaviors needed for this block.
	}
}

new ACFBlock(CardsView);