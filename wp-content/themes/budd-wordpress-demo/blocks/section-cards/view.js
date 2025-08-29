import ACFBlock from '../../assets/js/utils/blocks';

/**
 * SectionCardsView - Client-side functionality
 */
class SectionCardsView {
	constructor(block) {
		this.block = block;
		this.init();
	}

	static getName() {
		return 'section-cards';
	}

	init() {
		// No interactive behaviors in the provided React component.
	}
}

new ACFBlock(SectionCardsView);