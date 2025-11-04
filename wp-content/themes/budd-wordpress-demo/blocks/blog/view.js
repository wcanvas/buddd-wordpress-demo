import ACFBlock from '../../assets/js/utils/blocks';

/**
 * BlogView - Client-side functionality for the Blog block.
 */
class BlogView {
	constructor(block) {
		this.block = block;
		this.init();
	}

	static getName() {
		return 'blog';
	}

	init() {
		this.filterButtons = this.block.querySelectorAll('.js-filter-button');
		this.searchInput = this.block.querySelector('.js-search-input');
		this.postsContainer = this.block.querySelector('.js-recent-posts-list');
		this.featuredPostId = this.block.dataset.featuredPostId || 0;

		this.activeCategory = 'all';
		this.searchTerm = '';
		this.debounceTimer = null;
		this.isLoading = false;

		this.addEventListeners();
	}

	addEventListeners() {
		if (this.filterButtons.length) {
			this.filterButtons.forEach((button) => {
				button.addEventListener(
					'click',
					this.handleFilterClick.bind(this)
				);
			});
		}

		if (this.searchInput) {
			this.searchInput.addEventListener(
				'input',
				this.handleSearchInput.bind(this)
			);
		}
	}

	handleFilterClick(event) {
		event.preventDefault();
		const button = event.currentTarget;
		const category = button.dataset.category;

		if (category === this.activeCategory || this.isLoading) {
			return;
		}

		this.activeCategory = category;

		// Update active classes
		this.filterButtons.forEach((btn) => {
			const isActive = btn.dataset.category === this.activeCategory;
			// These classes are based on the existing filter-button component
			const activeClasses = ['wcb-bg-color-15', 'wcb-text-color-27', 'wcb-border-color-15'];
			const inactiveClasses = ['wcb-bg-transparent', 'wcb-text-color-3', 'wcb-border-color-5'];
			
			if (isActive) {
				btn.classList.remove(...inactiveClasses);
				btn.classList.add(...activeClasses);
			} else {
				btn.classList.remove(...activeClasses);
				btn.classList.add(...inactiveClasses);
			}
		});

		this.fetchPosts();
	}

	handleSearchInput(event) {
		clearTimeout(this.debounceTimer);
		this.debounceTimer = setTimeout(() => {
			this.searchTerm = event.target.value;
			this.fetchPosts();
		}, 300);
	}

	fetchPosts() {
		if (!this.postsContainer || this.isLoading) {
			return;
		}

		this.isLoading = true;
		this.postsContainer.style.opacity = '0.5';
		this.postsContainer.style.transition = 'opacity 0.3s';

		const formData = new FormData();
		formData.append('action', 'wcb_filter_blog_posts');
		// Assuming wp_localize_script is used to provide wcb_ajax object with url and nonce
		if (window.wcb_ajax && window.wcb_ajax.nonce) {
			formData.append('nonce', window.wcb_ajax.nonce);
		}
		formData.append('category', this.activeCategory);
		formData.append('search_term', this.searchTerm);
		formData.append('featured_post_id', this.featuredPostId);

		fetch(window.wcb_ajax.url, {
			method: 'POST',
			body: formData,
		})
			.then((response) => response.json())
			.then((result) => {
				if (result.success && result.data.html) {
					this.postsContainer.innerHTML = result.data.html;
				} else {
					this.postsContainer.innerHTML = `<p class="wcb-text-color-3">${result.data.message || 'No posts found.'}</p>`;
				}
			})
			.catch((error) => {
				console.error('Error fetching posts:', error);
				this.postsContainer.innerHTML = '<p class="wcb-text-color-3">An error occurred. Please try again.</p>';
			})
			.finally(() => {
				this.isLoading = false;
				this.postsContainer.style.opacity = '1';
			});
	}
}

new ACFBlock(BlogView);