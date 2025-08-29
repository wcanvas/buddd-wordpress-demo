import ACFBlock from '../../assets/js/utils/blocks';

/**
 * BlogArchiveView - Client-side functionality
 */
class BlogArchiveView {
    constructor(block) {
        this.block = block;
        this.init();
    }

    static getName() {
        return 'blog-archive';
    }

    init() {
        // This block has no specific JavaScript-driven interactions.
        // The "Read More" link hover effect is handled by Tailwind's group-hover utility.
    }
}

new ACFBlock(BlogArchiveView);