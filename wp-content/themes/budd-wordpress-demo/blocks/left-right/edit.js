import ACFBlock from '../../assets/js/utils/blocks';

/**
 * Custom LeftRightEditor Block, describe your block here.
 */
class LeftRightEditor {
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
        return 'left-right';
    }

    // Your methods
}

new ACFBlock( LeftRightEditor );