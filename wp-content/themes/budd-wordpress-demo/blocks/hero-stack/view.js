import ACFBlock from '../../assets/js/utils/blocks';

/**
 * HeroStack Block - Client-side functionality
 */
class HeroStackView {
    constructor(block) {
        this.block = block;
        this.init();
    }

    static getName() {
        return 'hero-stack';
    }

    init() {
        // No interactive elements in this block.
    }
}

new ACFBlock(HeroStackView);