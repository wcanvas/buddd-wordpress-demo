import { MARKERS } from '../props';
import gsap from 'gsap';
import ScrollTrigger from 'gsap/ScrollTrigger';

export default class fadeInUpStagger {
	/**
	 * Animations constructor
	 * @param {HTMLElement|NodeList} elements array of elements to animate.
	 */
	constructor( elements ) {
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

		this.setInitialStyles();
	}

	/**
	 * Add necessary styles for animation
	 */
	setInitialStyles() {
		this.elements.forEach( ( container ) => {
			container.style.opacity = '0';
			const boxes = Array.from( container.children ); // Get direct children
			gsap.set( boxes, {
				opacity: 0,
				y: '1em',
			} );
		} );
	}

	/**
	 * Link timelines to scroll position
	 * @param {*} triggerElement
	 * @param {*} timeline
	 */
	createScrollTrigger( triggerElement, timeline ) {
		ScrollTrigger.create( {
			trigger: triggerElement,
			start: '10% bottom',
			onEnter: () => timeline.play(),
			onLeaveBack: () => timeline.pause(),
			markers: MARKERS,
		} );
	}

	/**
	 * Animate boxes diagonally and return a Promise
	 * @param {Object} props properties for the animation
	 */
	animate( props ) {
		const speed = props.speed ? props.speed : 0.8;
		const executeNextAnimation = props.trigger ? props.trigger : 0.2;
		return Promise.all(
			this.elements.map( ( container ) => {
				let resolvePromise;
				const tl = gsap.timeline( {
					paused: true,
					onComplete: () => {
						ScrollTrigger.refresh();
						if ( resolvePromise ) resolvePromise();
					},
				} );

				let resolved = false;

				const boxes = Array.from( container.children ); // Get direct children
				const isSingleRow = this.checkIfSingleRow( boxes );

				// Set initial styles using gsap.set
				this.setInitialStyles();
				container.style.opacity = '1';

				tl.to( boxes, {
					opacity: 1,
					y: '0em', // Move boxes to their initial position
					ease: 'power2.inOut',
					stagger: {
						each: speed,
						grid: isSingleRow ? false : 'auto', // Stagger items in a grid layout if not single row
						from: isSingleRow ? 0 : 'start', // Start stagger from the beginning
					},
					onUpdate: () => {
						if (
							tl.progress() >= executeNextAnimation &&
							! resolved
						) {
							resolved = true;
							if ( resolvePromise ) resolvePromise();
						}
					},
				} );

				this.createScrollTrigger( container, tl );

				return new Promise( ( resolve ) => {
					resolvePromise = resolve;
				} );
			} )
		);
	}

	/**
	 * Check if the boxes are in a single row
	 * @param {HTMLElement[]} boxes
	 * @return {boolean} true if the boxes are in a single row
	 */
	checkIfSingleRow( boxes ) {
		if ( boxes.length < 2 ) return true;
		const firstBoxTop = boxes[ 0 ].getBoundingClientRect().top;
		for ( let i = 1; i < boxes.length; i++ ) {
			if ( boxes[ i ].getBoundingClientRect().top !== firstBoxTop ) {
				return false;
			}
		}
		return true;
	}
}
