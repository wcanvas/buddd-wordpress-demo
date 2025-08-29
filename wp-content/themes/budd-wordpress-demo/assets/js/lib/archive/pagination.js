import { setUrlParameter, deleteUrlParameter } from '../../utils/urlUtils';
import ScrollManager from '../scrollManager';

class Pagination {
	constructor( archiveContainer, fetchPageCallback ) {
		this.archiveContainer = archiveContainer;
		// Elements - pagination.
		this.paginationContainer = archiveContainer.querySelector(
			'[wcb-pagination-container]'
		);
		this.paginationNumbers = this.paginationContainer.querySelector(
			'[wcb-pagination-numbers]'
		);
		this.prevButton = this.paginationContainer.querySelector(
			'[wcb-prev-button]'
		);
		this.nextButton = this.paginationContainer.querySelector(
			'[wcb-next-button]'
		);

		// Templates.
		this.pageNumberTemplate = this.paginationContainer.querySelector(
			'[wcb-page-number-template]'
		).content;
		this.ellipsisTemplate = this.paginationContainer.querySelector(
			'[wcb-ellipsis-template]'
		).content;

		// Elements - pagination meta info.
		this.metaInfoContainer = archiveContainer.querySelector(
			'[wcb-archive-filter-meta-info]'
		);
		this.startPostElement = this.metaInfoContainer.querySelector(
			'[wcb-archive-filter-meta-info-start-post]'
		);
		this.endPostElement = this.metaInfoContainer.querySelector(
			'[wcb-archive-filter-meta-info-end-post]'
		);
		this.totalPostsElement = this.metaInfoContainer.querySelector(
			'[wcb-archive-filter-meta-info-displayed-posts]'
		);

		// State.
		this.perPage = parseInt(
			this.paginationContainer.getAttribute( 'data-per-page' )
		);
		this.currentPage = parseInt(
			this.paginationContainer.getAttribute( 'data-current-page' )
		);
		this.totalPages = parseInt(
			this.paginationContainer.getAttribute( 'data-total-pages' )
		);
		this.range = parseInt(
			this.paginationContainer.getAttribute( 'data-range' )
		);

		this.scrollManager = new ScrollManager( {
			offset: 180,
		} );

		// Callbacks.
		this.fetchPageCallback = fetchPageCallback;

		// Functions.
		this.init();
	}

	init() {
		this.paginationNumbers.addEventListener( 'click', ( e ) => {
			this.handlePageClick( e );
			this.scrollManager.scrollToElement( this.archiveContainer );
		} );
		this.prevButton.addEventListener( 'click', () => {
			this.handlePrevClick();
			this.scrollManager.scrollToElement( this.archiveContainer );
		} );
		this.nextButton.addEventListener( 'click', () => {
			this.handleNextClick();
			this.scrollManager.scrollToElement( this.archiveContainer );
		} );
	}

	handlePageClick( e ) {
		e.preventDefault();

		if (
			e.target.classList.contains( 'wcb-c-pagination__page-number' )
		) {
			const pageNumber = parseInt( e.target.getAttribute( 'data-page' ) );
			this.setCurrentPage( pageNumber );
			this.fetchPageCallback();
			setUrlParameter( 'cpage', pageNumber );
		}
	}

	handlePrevClick() {
		const currentPage = this.getCurrentPage();
		if ( currentPage > 1 ) {
			const newPage = currentPage - 1;
			this.setCurrentPage( newPage );
			this.fetchPageCallback();
			setUrlParameter( 'cpage', newPage );
		}
	}

	handleNextClick() {
		const currentPage = this.getCurrentPage();
		if ( currentPage < this.totalPages ) {
			const newPage = currentPage + 1;
			this.setCurrentPage( newPage );
			this.fetchPageCallback();
			setUrlParameter( 'cpage', newPage ); // Update URL
		}
	}

	updatePagination() {
		const currentPage = this.currentPage;
		const totalPages = this.totalPages;

		if ( totalPages > 1 ) {
			this.paginationContainer.setAttribute( 'has-pages', '' );
		} else {
			this.paginationContainer.removeAttribute( 'has-pages' );
		}

		// Update buttons
		this.prevButton.disabled = currentPage <= 1;
		this.nextButton.disabled = currentPage >= totalPages;

		// Update pagination numbers
		this.paginationNumbers.innerHTML = '';
		const startPage = Math.max( 1, currentPage - this.range );
		const endPage = Math.min( totalPages, currentPage + this.range );

		if ( startPage > 1 ) {
			this.paginationNumbers.appendChild(
				this.createPageNumberElement( 1 )
			);
			if ( startPage > 2 ) {
				this.paginationNumbers.appendChild(
					this.ellipsisTemplate.cloneNode( true )
				);
			}
		}

		for ( let i = startPage; i <= endPage; i++ ) {
			this.paginationNumbers.appendChild(
				this.createPageNumberElement( i, i === currentPage )
			);
		}

		if ( endPage < totalPages ) {
			if ( endPage < totalPages - 1 ) {
				this.paginationNumbers.appendChild(
					this.ellipsisTemplate.cloneNode( true )
				);
			}
			this.paginationNumbers.appendChild(
				this.createPageNumberElement( totalPages )
			);
		}
	}

	createPageNumberElement( pageNumber, isActive = false ) {
		const pageNumberElement = this.pageNumberTemplate.cloneNode( true );
		const pageNumberItem = pageNumberElement.querySelector(
			'[wcb-pagination-page-number]'
		);
		pageNumberItem.setAttribute( 'data-page', pageNumber );
		pageNumberItem.textContent = pageNumber;
		if ( isActive ) {
			pageNumberItem.classList.add( 'active' );
		}
		return pageNumberElement;
	}

	updatePaginationState(
		totalPages,
		currentPage,
		totalItems,
		reset = false
	) {
		this.totalPages = totalPages;
		this.currentPage = reset ? 1 : currentPage;
		if ( reset ) deleteUrlParameter( 'cpage' );

		// Update pagination buttons and numbers. This is called in the archive.js file.
		this.updatePagination();
		this.updateMetaInfo( currentPage, totalItems );
	}

	getCurrentPage() {
		return this.currentPage;
	}

	setCurrentPage( pageNumber ) {
		this.currentPage = pageNumber;
	}

	getPerPage() {
		return this.perPage;
	}

	resetPagination() {
		deleteUrlParameter( 'cpage' );
		this.setCurrentPage( 1 );
		this.updatePagination();
	}

	updateMetaInfo( currentPage, totalItems ) {
		const startPost = ( currentPage - 1 ) * this.perPage + 1;
		const endPost = Math.min( currentPage * this.perPage, totalItems );

		this.startPostElement.textContent = startPost;
		this.endPostElement.textContent = endPost;
		this.totalPostsElement.textContent = totalItems;

		if ( totalItems > 0 ) {
			this.metaInfoContainer.setAttribute( 'has-meta-info', '' );
		} else {
			this.metaInfoContainer.removeAttribute( 'has-meta-info' );
		}
	}
}

export default Pagination;
