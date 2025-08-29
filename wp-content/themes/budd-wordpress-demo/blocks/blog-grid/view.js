import ACFBlock from '../../assets/js/utils/blocks';

/**
 * BlogGrid Block - Client-side functionality
 */
class BlogGridView {
    constructor(block) {
        this.block = block;
        this.init();
    }

    static getName() {
        return 'blog-grid';
    }

    init() {
        // No interactive elements in this block.
    }
}

new ACFBlock(BlogGridView);