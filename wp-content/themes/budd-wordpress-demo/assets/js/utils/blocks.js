let BlockClassUse;

/**
 * Class to instantiate all JS blocks
 */
export default class ACFBlock {
	/**
	 * ACFBlock constructor method
	 * @param {Function} blockClass to instantiate
	 */
	constructor( blockClass ) {
		BlockClassUse = blockClass;
		this.admin();
		this.frontend();
	}
	/**
	 * Initialize dynamic block preview (editor)
	 */
	admin() {
		// First Print on admin side
		if ( window.location.href.indexOf( 'wp-admin' ) != -1 ) {
			document.addEventListener( 'DOMContentLoaded', function() {
				setTimeout( () => {
					const blockSelector = BlockClassUse.getName()
						.replace( /([a-z])([A-Z])/g, '$1-$2' )
						.replaceAll( '_', '-' )
						.toLowerCase();
					const blocks = document.querySelectorAll( `.js-${ blockSelector }` );
					blocks.forEach( ( block ) => {
						self.initializeBlock( block, true, true );
					} );
				}, 3000 );
			} );
		}

		// On change event
		const self = this;
		setTimeout( () => {
			if ( window.acf ) {
				window.acf.addAction(
					`render_block_preview/type=${ BlockClassUse.getName()
						.replace( /([a-z])([A-Z])/g, '$1-$2' )
						.replaceAll( '_', '-' )
						.toLowerCase() }`,
					self.initializeBlock,
				);
			}
		}, 500 );
	}
	/**
	 * Initialize each block on page load (front end)
	 */
	frontend() {
		const self = this;
		document.addEventListener( 'DOMContentLoaded', function() {
			document
				.querySelectorAll(
					`.js-${ BlockClassUse.getName()
						.replace( /([a-z])([A-Z])/g, '$1-$2' )
						.replaceAll( '_', '-' )
						.toLowerCase() }`,
				)
				.forEach( ( block ) => {
					self.initializeBlock( block );
				} );
		} );
	}

	/**
	 * Initialize Block js
	 * @param {HTMLElement} block
	 * @param {Object}      context
	 * @param {boolean}     firstPrint
	 */
	initializeBlock( block, context, firstPrint = false ) {
		if ( firstPrint ) {
			new BlockClassUse( block );
			return;
		}

		if ( window.location.href.indexOf( 'wp-admin' ) != -1 ) {
			new BlockClassUse(
				block[ 0 ].querySelector(
					`.js-${ BlockClassUse.getName()
						.replace( /([a-z])([A-Z])/g, '$1-$2' )
						.replaceAll( '_', '-' )
						.toLowerCase() }`,
				),
			);
		} else {
			new BlockClassUse( block );
		}
	}
}
