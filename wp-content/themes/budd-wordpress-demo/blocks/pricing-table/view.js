import ACFBlock from '../../assets/js/utils/blocks';

/**
 * PricingTable - Client-side functionality
 */
class PricingTableView {
    constructor(block) {
        this.block = block;
        this.init();
    }

    static getName() {
        return 'pricing-table';
    }

    init() {
        // No interactive behaviors are specified in the React components.
        // This block is for rendering only.
    }
}

new ACFBlock(PricingTableView);