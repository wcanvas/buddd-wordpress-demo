import { MARKERS } from '../props';
import gsap from 'gsap';
import ScrollTrigger from 'gsap/ScrollTrigger';

export default class revealFromColumns {
	/**
	 * Animations constructor
	 * @param {HTMLElement|NodeList} elements array of elements to animate.
	 * @param {string} direction direction of the reveal effect, either 'up' or 'down'.
	 */
	constructor( elements, direction = 'up' ) {
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
		this.addStylesToElements();
		this.prepareElements();
	}

	/**
	 * Add necessary styles directly to the elements for animation
	 */
	addStylesToElements() {
		this.elements.forEach( ( element ) => {
			element.style.position = 'relative';
			element.style.overflow = 'hidden';
		} );
	}

	/**
	 * Prepare elements by adding cover divs for each column
	 */
	prepareElements() {
		this.elements.forEach( ( element ) => {
			const cover = document.createElement( 'div' );
			cover.style.position = 'absolute';
			cover.style.width = '100%';
			cover.style.height = '100%';
			cover.style.top = 0;
			cover.style.left = 0;
			cover.style.display = 'flex';
			cover.style.zIndex = 1;
			cover.style.pointerEvents = 'none';

			// Create 5 inner divs for staggered reveal
			for ( let i = 0; i < 8; i++ ) {
				const innerDiv = document.createElement( 'div' );
				innerDiv.style.flex = '1';
				innerDiv.style.height = '100%';
				innerDiv.style.background = 'white';
				cover.appendChild( innerDiv );
			}

			element.appendChild( cover );
			element._cover = cover;
			element._coverChildren = Array.from( cover.children );
			element.style.opacity = 1;

			this.initialState( element._coverChildren );
		} );
	}

	initialState( elements ) {
		elements.forEach( ( element ) => gsap.set( element, { y: 0 } ) );
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
			markers: MARKERS, // Enable markers for debugging
		} );
	}

	/**
	 * Animate elements with reveal effect and return a Promise
	 * @param {Object} props properties for the animation
	 */
	animate( props ) {
		const speed = props.speed ? props.speed : 0.8;
		const executeNextAnimation = props.trigger ? props.trigger : 0.2;
		return Promise.all(
			this.elements.map( ( element ) => {
				let resolvePromise;
				const tl = gsap.timeline( {
					paused: true,
					onComplete: () => {
						ScrollTrigger.refresh();
						if ( resolvePromise ) resolvePromise();
					},
				} );

				let resolved = false;
				const columnProgressCheck = new Array(
					element._coverChildren.length
				).fill( false );
				this.initialState( element._coverChildren );

				element._coverChildren.forEach( ( child, index ) => {
					tl.to(
						child,
						{
							y: this.direction === 'up' ? '-100%' : '100%',
							duration: speed, // Use speed property for animation duration
							ease: 'power4.out', // Adjust easing for smoother transition
							onUpdate: () => {
								if (
									tl.progress() >= executeNextAnimation &&
									! columnProgressCheck[ index ] &&
									index < element._coverChildren.length - 1
								) {
									columnProgressCheck[ index ] = true;
									tl.play(); // Trigger next column animation
								}
							},
						},
						index * 0.1
					);
				} );

				tl.eventCallback( 'onUpdate', function () {
					if ( tl.progress() >= executeNextAnimation && ! resolved ) {
						resolved = true;
						if ( resolvePromise ) resolvePromise();
					}
				} );

				this.createScrollTrigger( element, tl );

				return new Promise( ( resolve ) => {
					resolvePromise = resolve;
				} );
			} )
		);
	}
}
