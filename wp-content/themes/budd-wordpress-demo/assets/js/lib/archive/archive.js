import Api from '../../utils/api';
import Pagination from './pagination';
import Filters from './filters';
import fadeInUpStagger from '../animations/fades/fade-in-up';

class Archive {
	constructor( container ) {
		this.container = container;

		this.itemsContainer = container.querySelector(
			'[wcb-archive-items]'
		);

		this.itemsSkeletonTemplate = container.querySelector(
			'[wcb-archive-items-skeleton]'
		);

		this.errorContainer = container.querySelector(
			'[wcb-archive-error]'
		);
		this.errorParagraph = this.errorContainer.querySelector( 'p' );

		this.postType = container.getAttribute( 'data-post-type' );
		this.componentName = container.getAttribute( 'data-component-name' );

		this.pagination = new Pagination(
			container,
			this.fetchPage.bind( this )
		);

		this.filters = new Filters( container, this.fetchPage.bind( this ) );

		// Animations.
		this.itemsStagger = new fadeInUpStagger(
			this.container.querySelector( '[wcb-archive-items]' )
		);

		this.animate();
	}

	fetchPage( resetPagination = false ) {
		this.loading();

		const filters = this.filters.getSelectedFilters();

		Api.get( 'wcb-archive/v1/paged-posts', {
			params: {
				post_type: this.postType,
				cpage: resetPagination ? 1 : this.pagination.getCurrentPage(),
				component: this.componentName,
				filters,
			},
		} )
			.then( ( response ) => {
				if ( response.data.status ) {
					this.resetError();
					this.itemsContainer.innerHTML = response.data.posts;
				} else {
					this.itemsContainer.innerHTML = '';
					this.displayErrorMessage( response.data.error_message );
				}

				this.pagination.updatePaginationState(
					response.data.total_pages,
					response.data.current_page,
					response.data.total_items,
					resetPagination
				);

				// Animate on new items.
				this.animate();
			} )
			.catch( ( error ) => {
				console.error( error );
				this.displayErrorMessage( 'Ops! Something went wrong...' );
			} );
	}

	loading() {
		this.resetError();
		this.itemsContainer.innerHTML = '';

		// TODO: We can know exactly how many items to render here by checking the perPage, total items and the "nextPage".
		for ( let i = 0; i < 4; i++ ) {
			this.itemsContainer.append(
				this.itemsSkeletonTemplate.content.cloneNode( true )
			);
		}
	}

	displayErrorMessage( message ) {
		this.errorParagraph.textContent = message;
		this.errorContainer.removeAttribute( 'hidden' );
	}

	resetError() {
		this.errorParagraph.textContent = '';
		this.errorContainer.setAttribute( 'hidden', '' );
	}

	async animate() {
		await this.itemsStagger.animate( { speed: 0.2 } );
	}
}

export default Archive;
