import gsap from 'gsap';
import ScrollTrigger from 'gsap/ScrollTrigger';

export default class ParallaxEffect {
	/**
	 * ParallaxEffect constructor
	 * @param {HTMLElement|NodeList} elements array of elements to animate.
	 * @param {string} direction direction of the parallax effect, either 'up', 'down', or 'auto'.
	 */
	constructor( elements, direction = 'auto' ) {
		if ( ! elements ) {
			return;
		}

		if (
			NodeList.prototype.isPrototypeOf( elements ) ||
			HTMLCollection.prototype.isPrototypeOf( elements )
		) {
			this.elements = Array.from( elements );
		} else if ( elements instanceof HTMLElement ) {
			this.elements = [ elements ];
		} else {
			console.error( 'Invalid elements input' );
			return;
		}

		this.direction = direction;
		this.initParallax();
	}

	/**
	 * Initialize parallax effect
	 */
	initParallax() {
		this.elements.forEach( ( element, index ) => {
			let direction;
			if ( this.direction === 'auto' ) {
				direction = index % 2 === 0 ? 'up' : 'down';
			} else {
				direction = this.direction;
			}

			gsap.to( element, {
				y: direction === 'up' ? '-20%' : '20%',
				ease: 'none',
				scrollTrigger: {
					trigger: element,
					start: 'top bottom', // Start when top of the element hits the bottom of the viewport
					end: 'bottom top', // End when bottom of the element hits the top of the viewport
					scrub: true, // Smooth scrubbing, takes 1 second to "catch up" to the scrollbar
				},
			} );
		} );
	}
}
