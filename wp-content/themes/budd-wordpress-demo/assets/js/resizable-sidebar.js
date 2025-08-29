import domReady from '@wordpress/dom-ready';

function resizeSidebar() {
	const saveWidth = localStorage.getItem( 'wc_sidebar_width' ) || 280;

	jQuery( '.interface-interface-skeleton__sidebar' ).width( saveWidth );

	document.documentElement.style.setProperty(
		'--wc-sidebar-size',
		`${ saveWidth }px`
	);

	jQuery( '.interface-interface-skeleton__sidebar' ).resizable( {
		handles: 'w',
		minWidth: 280,
		resize( event, ui ) {
			jQuery( this ).css( { left: 0 } );
			const newWidth = jQuery( this ).width();
			localStorage.setItem( 'wc_sidebar_width', newWidth );
			document.documentElement.style.setProperty(
				'--wc-sidebar-size',
				`${ newWidth }px`
			);
		},
	} );
}

domReady( function () {
	function handleEditorSidebarMutation( mutationsList ) {
		for ( const mutation of mutationsList ) {
			if ( mutation.type === 'childList' ) {
				mutation.addedNodes.forEach( ( node ) => {
					if (
						node.classList &&
						node.classList.contains(
							'interface-complementary-area__fill'
						)
					) {
						resizeSidebar();
					}
				} );
				mutation.removedNodes.forEach( ( node ) => {
					if (
						node.classList &&
						node.classList.contains(
							'interface-complementary-area__fill'
						)
					) {
						document.querySelector(
							'.interface-interface-skeleton__sidebar'
						).style.width = null;
					}
				} );
			}
		}
	}

	const observer = new MutationObserver( handleEditorSidebarMutation );

	observer.observe( document.body, { childList: true, subtree: true } );
} );
