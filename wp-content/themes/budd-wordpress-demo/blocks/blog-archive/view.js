import ACFBlock from '../../assets/js/utils/blocks';

/**
 * BlogArchive - Client-side functionality
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
        // This block has no client-side interactive behaviors.
    }
}

new ACFBlock(BlogArchiveView);