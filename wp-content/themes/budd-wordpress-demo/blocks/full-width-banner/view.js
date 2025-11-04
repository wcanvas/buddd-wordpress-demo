import ACFBlock from '../../assets/js/utils/blocks';

/**
 * FullWidthBannerView - Client-side functionality
 */
class FullWidthBannerView {
	constructor(block) {
		this.block = block;
		this.init();
	}

	static getName() {
		return 'full-width-banner';
	}

	init() {
		// This block has no interactive elements, so the init function is empty.
	}
}

new ACFBlock(FullWidthBannerView);