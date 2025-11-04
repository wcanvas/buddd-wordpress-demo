import ACFBlock from '../../assets/js/utils/blocks';

/**
 * HeroView - Client-side functionality
 */
class HeroView {
    constructor(block) {
        this.block = block;
        this.init();
    }

    static getName() {
        return 'hero';
    }

    init() {
        // The Hero block is purely presentational and has no interactive JavaScript behaviors.
    }
}

new ACFBlock(HeroView);