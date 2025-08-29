import ACFBlock from '../../assets/js/utils/blocks';

/**
 * Blog Block - Client-side functionality
 */
class BlogView {
	constructor(block) {
		this.block = block;

		this.dom = {
			filterButtons: this.block.querySelectorAll('.js-filter-button'),
			searchInput: this.block.querySelector('.js-search-input'),
			postItems: this.block.querySelectorAll('.js-post-item'),
		};

		this.state = {
			activeCategory: 'all',
			searchTerm: '',
		};

		this.init();
	}

	static getName() {
		return 'blog';
	}

	init() {
		if (this.dom.filterButtons.length) {
			this.initFilters();
		}
		if (this.dom.searchInput) {
			this.initSearch();
		}
	}

	initFilters() {
		this.dom.filterButtons.forEach((button) => {
			button.addEventListener('click', this.handleFilterClick.bind(this));
		});
	}

	initSearch() {
		// Use a debounce to prevent firing on every keystroke
		let timeout;
		this.dom.searchInput.addEventListener('input', (e) => {
			clearTimeout(timeout);
			timeout = setTimeout(() => {
				this.handleSearchInput(e);
			}, 300);
		});
	}

	handleFilterClick(e) {
		const button = e.currentTarget;
		this.state.activeCategory = button.dataset.category || 'all';

		// Update active class on buttons
		this.dom.filterButtons.forEach((btn) => {
			const baseClasses = 'js-filter-button wcb-font-font-3 wcb-text-xs wcb-leading-tight wcb-rounded-full wcb-px-3 wcb-py-1.5 wcb-transition-colors wcb-duration-300 wcb-cursor-pointer wcb-border wcb-border-solid wcb-flex wcb-items-center wcb-gap-1.5';
			const activeClasses = 'wcb-bg-color-15 wcb-text-color-27 wcb-border-color-15';
			const inactiveClasses = 'wcb-bg-transparent wcb-text-color-3 wcb-border-color-5 hover:wcb-bg-color-7 hover:wcb-text-color-30 hover:wcb-border-color-7';

			if (btn.dataset.category === this.state.activeCategory) {
				btn.className = `${baseClasses} ${activeClasses}`;
			} else {
				btn.className = `${baseClasses} ${inactiveClasses}`;
			}
		});

		this.applyFilters();
	}

	handleSearchInput(e) {
		this.state.searchTerm = e.target.value.toLowerCase().trim();
		this.applyFilters();
	}

	applyFilters() {
		this.dom.postItems.forEach((item) => {
			const categorySlugs = item.dataset.categorySlugs || '';
			const searchContent = item.dataset.searchContent || '';

			const categoryMatch =
				this.state.activeCategory === 'all' ||
				categorySlugs.split(' ').includes(this.state.activeCategory);

			const searchMatch =
				this.state.searchTerm === '' ||
				searchContent.includes(this.state.searchTerm);

			if (categoryMatch && searchMatch) {
				item.style.display = '';
			} else {
				item.style.display = 'none';
			}
		});
	}
}

new ACFBlock(BlogView);