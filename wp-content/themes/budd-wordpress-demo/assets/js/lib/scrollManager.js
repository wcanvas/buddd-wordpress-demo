class ScrollManager {
	constructor( options = {} ) {
		this.behavior = options.behavior || 'smooth';
		this.offset = options.offset || 0;
	}

	// Method to scroll to an element with the provided options
	scrollToElement( element ) {
		return new Promise( ( resolve ) => {
			const elementTop = element.getBoundingClientRect().top; // Get element's position relative to the viewport.
			const scrollTop =
				window.pageYOffset || document.documentElement.scrollTop;

			const targetPosition = elementTop + scrollTop - this.offset;

			window.scrollTo( {
				top: targetPosition,
				behavior: this.behavior,
			} );

			// Use an event listener to resolve the promise once the scroll is complete.
			const onScroll = () => {
				// Check if we've scrolled to (or past) the target position.
				const currentPosition =
					window.pageYOffset || document.documentElement.scrollTop;
				if ( Math.abs( currentPosition - targetPosition ) <= 1 ) {
					window.removeEventListener( 'scroll', onScroll );
					resolve();
				}
			};

			// Add a listener to detect when scrolling is done.
			window.addEventListener( 'scroll', onScroll );
		} );
	}
}

export default ScrollManager;
