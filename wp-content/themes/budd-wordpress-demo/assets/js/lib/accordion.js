class Accordion {
	constructor( container, closeOthers = true, openIndex = null ) {
		this.container = container;
		this.items = Array.from(
			container.querySelectorAll( '[wcb-accordion-item]' )
		);
		this.closeOthers = closeOthers;
		this.onOpenCallback = null;
		this.onCloseCallback = null;

		// Initialize all accordion items.
		this.items.forEach( ( item, index ) => {
			const btn = item.querySelector( '[wcb-accordion-item-btn]' );

			btn.addEventListener( 'click', () => this.toggleAccordion( item ) );

			// Check if this accordion should start open.
			if ( index === openIndex ) {
				// Fixes the issue of the accordion being cut off if the height of the accordion is being calculated before the content/css is fully loaded.
				setTimeout( () => {
					this.openAccordion( item );
				}, 100 );
			}
		} );
	}

	toggleAccordion( item ) {
		const isOpen = item.hasAttribute( 'wcb-accordion-open' );

		if ( isOpen ) {
			this.closeAccordion( item );
		} else {
			if ( this.closeOthers ) {
				this.closeAllAccordions();
			}
			this.openAccordion( item );
		}
	}

	openAccordion( item ) {
		const content = item.querySelector( '[wcb-accordion-item-content]' );
		const contentHeight = content.scrollHeight;

		content.style.height = `${ contentHeight }px`;
		item.setAttribute( 'wcb-accordion-open', '' );

		if ( typeof this.onOpenCallback === 'function' ) {
			this.onOpenCallback( item );
		}
	}

	closeAccordion( item ) {
		const content = item.querySelector( '[wcb-accordion-item-content]' );

		content.style.height = '0px';
		item.removeAttribute( 'wcb-accordion-open' );

		if ( typeof this.onCloseCallback === 'function' ) {
			this.onCloseCallback( item );
		}
	}

	closeAllAccordions() {
		this.items.forEach( ( item ) => {
			if ( item.hasAttribute( 'wcb-accordion-open' ) ) {
				this.closeAccordion( item );
			}
		} );
	}

	onOpen( callback ) {
		this.onOpenCallback = callback;
	}

	onClose( callback ) {
		this.onCloseCallback = callback;
	}
}
export default Accordion;
