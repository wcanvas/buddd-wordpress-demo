import ACFBlock from '../../assets/js/utils/blocks';

/**
 * Cards - Client-side functionality
 */
class CardsView {
    constructor(block) {
        this.block = block;
        this.init();
    }

    static getName() {
        return 'cards';
    }

    init() {
        // The block has no interactive elements that require JavaScript.
    }
}

new ACFBlock(CardsView);