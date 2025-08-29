import ACFBlock from '../../assets/js/utils/blocks';

/**
 * Custom GalleryBlockEditor Block, describe your block here.
 */
class GalleryBlockEditor {
    /**
     * Constructor method
     *
     * @param {HTMLElement} block
     */
    constructor( block ) {
        // This Js file only runs in the editor view.
    }

    /**
     * The name of your block, don't modify this static method!!
     *
     * @return {string} The name of your block
     */
    static getName() {
        return 'gallery-block';
    }

    // Your methods
}

new ACFBlock( GalleryBlockEditor );