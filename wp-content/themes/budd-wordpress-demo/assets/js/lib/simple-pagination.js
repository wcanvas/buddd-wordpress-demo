import Api from '../utils/api';

/**
 * This class adds the functionality to the SimplePagination.php class.
 * See class SimplePagination located in 'inc/functionalities/simple-pagination.php'
 * for further details
 */
class SimplePagination {
	/**
	 * SimplePagination constructor
	 * @param {HTMLElement} module
	 */
	constructor( module ) {
		this.module = module;

		this.simplePagination = module.querySelector( '.js-simple-pagination' );
		this.container = module.querySelector(
			'.js-simple-pagination__container'
		);
		this.postType = this.simplePagination.getAttribute( 'post-type' );
		this.component = this.simplePagination.getAttribute( 'component' );
		this.postsPerPage =
			this.simplePagination.getAttribute( 'post-per-page' );
		this.loadMoreButton = module.querySelector( '.load-more-button' );
		this.init();
	}

	/**
	 * Here you should place the class methods that you want to execute.
	 */
	init() {
		this.getPosts();
	}

	/**
	 * Api call to get more posts.
	 */
	getPosts() {
		let page = 2; // Initialize page counter.
		this.loadMoreButton.addEventListener( 'click', () => {
			this.loadMoreButton.classList.add( 'is-loading' );
			Api.get( 'post-powers/v1/simple-posts', {
				params: {
					post_type: this.postType,
					posts_per_page: this.postsPerPage,
					page,
					component: this.component,
				},
			} )
				.then( ( response ) => {
					// Handle the response, e.g., append posts to the DOM.
					const responseData = response.data;
					const newPosts = responseData.posts;
					this.container.insertAdjacentHTML( 'beforeend', newPosts );
					this.loadMoreButton.classList.remove( 'is-loading' );

					if ( ! responseData.hasMorePosts ) {
						// If there are no more posts, hide the button.
						this.loadMoreButton.style.display = 'none';
					}

					page++; // Increment the page counter.
				} )
				.catch( ( error ) => {
					console.error( error );
				} );
		} );
	}
}

export default SimplePagination;
