import { MARKERS } from '../props';
import gsap from 'gsap';
import ScrollTrigger from 'gsap/ScrollTrigger';

export default class revealFromDirection {
	/**
	 * Animations constructor
	 * @param {HTMLElement|NodeList} elements array of elements to animate.
	 * @param {string} direction direction of the reveal effect, either 'up', 'down', 'left', or 'right'.
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
	 * Prepare elements by adding a cover div
	 */
	prepareElements() {
		this.elements.forEach( ( element ) => {
			const cover = document.createElement( 'div' );
			cover.style.position = 'absolute';
			cover.style.width = '100%';
			cover.style.height = '100%';
			cover.style.background = 'white'; // You can change this to match the background color of your element
			cover.style.top = 0;
			cover.style.left = 0;
			cover.style.zIndex = 2;
			cover.style.willChange = 'transform';

			element.appendChild( cover );
			element._cover = cover;
			element.style.opacity = 1;

			this.initialState( element._cover );
		} );
	}

	initialState( element ) {
		gsap.set( element, { x: 0, y: 0 } );
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
				this.initialState( element._cover );
				let xValue;
				if ( this.direction === 'left' ) {
					xValue = '100%';
				} else if ( this.direction === 'right' ) {
					xValue = '-100%';
				} else {
					xValue = '0%';
				}

				let yValue;
				if ( this.direction === 'up' ) {
					yValue = '-100%';
				} else if ( this.direction === 'down' ) {
					yValue = '100%';
				} else {
					yValue = '0%';
				}

				tl.to( element._cover, {
					x: xValue,
					y: yValue,
					duration: speed, // Use speed property for animation duration
					ease: 'power4.inOut',
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

				this.createScrollTrigger( element, tl );

				return new Promise( ( resolve ) => {
					resolvePromise = resolve;
				} );
			} )
		);
	}
}
