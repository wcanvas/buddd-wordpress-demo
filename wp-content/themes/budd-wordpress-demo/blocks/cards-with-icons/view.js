import ACFBlock from '../../assets/js/utils/blocks';

/**
 * CardsWithIcons - Client-side functionality
 */
class CardsWithIconsView {
    constructor(block) {
        this.block = block;
        this.init();
    }

    static getName() {
        return 'cards-with-icons';
    }

    init() {
        // This block does not contain any interactive elements.
    }
}

new ACFBlock(CardsWithIconsView);