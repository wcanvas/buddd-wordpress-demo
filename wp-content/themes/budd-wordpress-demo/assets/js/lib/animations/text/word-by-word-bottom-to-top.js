import { MARKERS } from '../props';
import gsap from 'gsap';
import ScrollTrigger from 'gsap/ScrollTrigger';

/**
 * Class representing a word-by-word bottom-to-top animation.
 * @class
 */
export default class wordByWordBottomToTop {
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

		this.prepareElements();
	}

	/**
	 * Add necessary styles for animation
	 * @param {NodeList} words
	 */
	initialStyles( words ) {
		gsap.set( words, {
			opacity: 0,
			y: '2em',
		} );
	}

	/**
	 * Prepare elements by wrapping words in span
	 */
	prepareElements() {
		this.elements.forEach( ( title ) => {
			this.wrapWords( title );
			this.wrapLists( title );
			const words = title.querySelectorAll( '.word, .list-container' );
			this.initialStyles( words );
		} );
	}

	/**
	 * Recursively wrap words in span elements
	 * @param {Node} node
	 */
	wrapWords( node ) {
		if ( node.nodeType === Node.TEXT_NODE ) {
			const words = node.textContent.split( /(\s+)/ ).filter( Boolean );
			const fragment = document.createDocumentFragment();

			words.forEach( ( word ) => {
				if ( word.match( /\s+/ ) ) {
					fragment.appendChild( document.createTextNode( word ) );
				} else {
					const outerSpan = document.createElement( 'span' );
					outerSpan.style.display = 'inline-block';
					outerSpan.style.overflow = 'hidden';
					outerSpan.style.lineHeight = '0em';
					outerSpan.className = 'word-container';

					const innerSpan = document.createElement( 'span' );
					innerSpan.className = 'word';
					innerSpan.style.display = 'inline-block';
					innerSpan.style.lineHeight = window.getComputedStyle(
						node.parentNode
					).lineHeight;
					innerSpan.textContent = word;

					outerSpan.appendChild( innerSpan );
					fragment.appendChild( outerSpan );
				}
			} );

			node.replaceWith( fragment );
		} else if ( node.nodeType === Node.ELEMENT_NODE ) {
			Array.from( node.childNodes ).forEach( ( child ) =>
				this.wrapWords( child )
			);
		}
	}

	/**
	 * Wrap list items in a container
	 * @param {HTMLElement} element
	 */
	wrapLists( element ) {
		const lists = element.querySelectorAll( 'ul, ol' );
		lists.forEach( ( list ) => {
			const listContainer = document.createElement( 'div' );
			listContainer.className = 'list-container';
			list.parentNode.insertBefore( listContainer, list );
			listContainer.appendChild( list );
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
	 * Animate titles and return a Promise
	 * @param {Object} props properties for the animation
	 */
	animate( props ) {
		const speed = props.speed ? props.speed : 0.8;
		const executeNextAnimation = props.trigger ? props.trigger : 0.2;

		return Promise.all(
			this.elements.map( ( title ) => {
				title.style.opacity = '1'; // Set opacity to 1 to prevent flicker
				let resolvePromise;
				const tl = gsap.timeline( {
					paused: true,
					onComplete: () => {
						ScrollTrigger.refresh();
						if ( resolvePromise ) resolvePromise();
					},
				} );

				let resolved = false;

				const words = title.querySelectorAll(
					'.word, .list-container'
				);
				const staggerAmount = speed / words.length;

				this.initialStyles( words );

				tl.to( words, {
					opacity: 1,
					y: '0em', // Move words to their initial position
					duration: speed, // Adjust duration for slower animation
					ease: 'power2.out',
					stagger: {
						each: staggerAmount, // Distribute the animation evenly across all words
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

				this.createScrollTrigger( title, tl );

				return new Promise( ( resolve ) => {
					resolvePromise = resolve;
				} );
			} )
		);
	}
}
