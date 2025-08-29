import { MARKERS } from '../props';
import gsap from 'gsap';
import ScrollTrigger from 'gsap/ScrollTrigger';

/**
 * Class representing word-by-word animations for text elements.
 * @class
 */
export default class wordByWord {
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
					const span = document.createElement( 'span' );
					span.className = 'word';
					span.style.display = 'inline-block';
					span.textContent = word;
					fragment.appendChild( span );
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

	initialStyles( elements ) {
		gsap.set( elements, {
			opacity: 0,
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
	 * Animate titles with options for speed and execution threshold
	 * @param {Object} props properties for the animation
	 * @return {Promise} Promise that resolves when the animation reaches the specified progress
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
					duration: speed, // Use speed parameter for animation duration
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
