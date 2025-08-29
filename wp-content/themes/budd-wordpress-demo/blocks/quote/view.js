import ACFBlock from '../../assets/js/utils/blocks';

/**
 * Quote - Client-side functionality
 */
class QuoteView {
	constructor(block) {
		this.block = block;
		this.init();
	}

	static getName() {
		return 'quote';
	}

	init() {
		// This block has no interactive elements, so the init function is empty.
	}
}

new ACFBlock(QuoteView);