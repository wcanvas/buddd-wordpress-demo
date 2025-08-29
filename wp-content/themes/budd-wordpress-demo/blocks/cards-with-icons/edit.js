import ACFBlock from '../../assets/js/utils/blocks';

/**
 * Custom CardsWithIconsEditor Block, describe your block here.
 */
class CardsWithIconsEditor {
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
        return 'cards-with-icons';
    }

    // Your methods
}

new ACFBlock( CardsWithIconsEditor );