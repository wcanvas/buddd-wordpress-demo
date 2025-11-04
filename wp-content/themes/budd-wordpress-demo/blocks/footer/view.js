import ACFBlock from '../../assets/js/utils/blocks';

/**
 * Footer - Client-side functionality
 */
class FooterView {
    constructor(block) {
        this.block = block;
        this.init();
    }

    static getName() {
        return 'footer';
    }

    init() {
        this.initializeForm();
    }

    initializeForm() {
        const newsletterForm = this.block.querySelector('.js-newsletter-form');

        if (!newsletterForm) {
            return;
        }

        newsletterForm.addEventListener('submit', (event) => {
            event.preventDefault();
            // In a real-world scenario, you would handle the form submission here,
            // e.g., via an AJAX request to a server endpoint.
            console.log('Newsletter form submission prevented.');
        });
    }
}

new ACFBlock(FooterView);