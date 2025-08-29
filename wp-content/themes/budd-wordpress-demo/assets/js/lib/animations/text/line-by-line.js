import { MARKERS } from '../props';
import gsap from 'gsap';
import ScrollTrigger from 'gsap/ScrollTrigger';

export default class lineByLine {
	/**
	 * Animations constructor
	 * @param {HTMLElement|NodeList} elements DON'T USE "document" TO GET HTML ELEMENTS, USE INSTEAD "elements"
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

		this.prepareElements();
	}

	/**
	 * Add necessary styles for animation.
	 * @param {NodeList} lines
	 */
	initialStyles( lines ) {
		gsap.set( lines, {
			opacity: 0,
			y: '2em',
		} );
	}

	/**
	 * Prepare elements by splitting lines
	 */
	prepareElements() {
		this.elements.forEach( ( element ) => {
			const lines = this.splitLines( element );
			element.innerHTML = lines;

			const linesReady = element.querySelectorAll( '.line span' );
			this.initialStyles( linesReady );
		} );
	}

	/**
	 * Split lines in the content
	 * @param {HTMLElement} element
	 */
	splitLines( element ) {
		const lines = [];
		const words = element.innerHTML.split( ' ' );
		const temp = document.createElement( 'div' );

		// Ensure the temp element has the same styles as the original element
		const computedStyle = window.getComputedStyle( element );
		temp.style.position = 'absolute';
		temp.style.visibility = 'hidden';
		temp.style.whiteSpace = 'nowrap';
		temp.style.fontSize = computedStyle.fontSize;
		temp.style.fontFamily = computedStyle.fontFamily;
		temp.style.fontWeight = computedStyle.fontWeight;
		temp.style.fontStyle = computedStyle.fontStyle;
		temp.style.letterSpacing = computedStyle.letterSpacing;
		temp.style.textTransform = computedStyle.textTransform;
		temp.style.padding = computedStyle.padding;
		temp.style.border = computedStyle.border;
		temp.style.margin = computedStyle.margin;
		document.body.appendChild( temp );

		let line = '';

		words.forEach( ( word ) => {
			temp.textContent = line + word + ' ';
			if ( temp.offsetWidth > element.clientWidth ) {
				lines.push( line.trim() );
				line = word + ' ';
			} else {
				line += word + ' ';
			}
		} );

		lines.push( line.trim() );
		document.body.removeChild( temp );

		return lines
			.map(
				( lineElement ) =>
					`<span class='line' style='display: block; overflow: hidden; will-change: transform;'><span style='display: inline-block; white-space: pre;'>${ lineElement }</span></span>`
			)
			.join( '' ); // Using <br> to ensure new lines are recognized
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
	 * Animate titles and return a Promise
	 * @param {Object	} props properties for the animation
	 */
	animate( props ) {
		const speed = props.speed ? props.speed : 0.8;
		const executeNextAnimation = props.trigger ? props.trigger : 0.2;
		return Promise.all(
			this.elements.map( ( element ) => {
				element.style.opacity = '1';
				let resolvePromise;
				const tl = gsap.timeline( {
					paused: true,
					onComplete: () => {
						ScrollTrigger.refresh();
						if ( resolvePromise ) resolvePromise();
					},
				} );

				let resolved = false;

				const lines = element.querySelectorAll( '.line span' );
				const staggerAmount = speed / lines.length;
				this.initialStyles( lines );

				tl.to( lines, {
					opacity: 1,
					y: '0%', // Move lines to their initial position
					duration: speed, // Use speed property for animation duration
					ease: 'power2.out',
					stagger: {
						each: staggerAmount,
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

				this.createScrollTrigger( element, tl );

				return new Promise( ( resolve ) => {
					resolvePromise = resolve;
				} );
			} )
		);
	}
}
