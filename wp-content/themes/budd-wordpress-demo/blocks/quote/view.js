import ACFBlock from '../../assets/js/utils/blocks';

/**
 * Quote - Client-side functionality
 */
class QuoteView {
    constructor(block) {
        this.block = block;
        // No interactive elements in this block, init is not strictly necessary
        // but kept for consistency.
        this.init();
    }

    static getName() {
        return 'quote';
    }

    init() {
        // This block is purely presentational and has no client-side interactions.
    }
}

new ACFBlock(QuoteView);