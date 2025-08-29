import {
	addUrlParameterValue,
	removeUrlParameterValue,
	getUrlParameter,
	setUrlParameter,
	deleteUrlParameter,
} from '../../utils/urlUtils';
import debounce from 'lodash.debounce';
import Accordion from '../accordion';

class Filters {
	constructor( container, fetchPageCallback ) {
		this.container = container;
		this.fetchPageCallback = fetchPageCallback;
		this._selectedFilters = [];

		this.initializeSelectedFilters();
		this.init();

		// Wrap the filters in an accordion.
		const accordionElement = this.container.querySelector(
			'.wcb-archive__filters'
		);

		new Accordion( accordionElement, false, 0 );
	}

	init() {
		this.bindCheckboxEvents();
		this.bindSearchEvents();
		this.bindSelectedFiltersEvents();
		this.bindOrderByEvents();
	}

	initializeSelectedFilters() {
		const checkboxes = this.container.querySelectorAll(
			'[data-filter-name] input[type="checkbox"]'
		);

		checkboxes.forEach( ( checkbox ) => {
			if ( checkbox.checked ) {
				const taxonomy = checkbox
					.closest( '[data-filter-name]' )
					.getAttribute( 'data-filter-name' );
				const term = checkbox.value;
				const label = checkbox.getAttribute( 'data-label' );

				const filterValue = {
					slug: term,
					label,
				};

				this.updateSelectedFiltersArray(
					'taxonomy',
					taxonomy,
					filterValue,
					'add'
				);
			}
		} );

		// Initialize selected filters for select menus. TODO: this is working for order_by only.
		const selects = this.container.querySelectorAll(
			'[data-filter-name] select'
		);
		selects.forEach( ( select ) => {
			const filterName = select
				.closest( '[data-filter-name]' )
				.getAttribute( 'data-filter-name' );
			const filterValue = select.value;
			const label = select.options[ select.selectedIndex ].text;

			if ( filterValue ) {
				const filterData = {
					type: 'order_by',
					filter_name: filterName,
					filter_value: [ { slug: filterValue, label } ],
				};

				this.updateSingleValueFilter( filterName, filterData );
			}
		} );

		// Initialize selected filters for search input
		const searchInput = this.container.querySelector(
			'[wcb-archive-filter-search-input]'
		);
		if ( searchInput && searchInput.value ) {
			const searchQuery = searchInput.value;

			const filterData = {
				type: 'search',
				filter_name: 'search',
				filter_value: [ { slug: searchQuery, label: searchQuery } ],
			};

			this.updateSingleValueFilter( 'search', filterData );
		}
	}

	bindCheckboxEvents() {
		const checkboxes = this.container.querySelectorAll(
			'[data-filter-name] input[type="checkbox"]'
		);
		checkboxes.forEach( ( checkbox ) => {
			checkbox.addEventListener( 'change', () =>
				this.handleCheckboxChange( checkbox )
			);
		} );
	}

	bindSearchEvents() {
		const searchInput = this.container.querySelector(
			'[wcb-archive-filter-search-input]'
		);
		if ( searchInput ) {
			searchInput.addEventListener(
				'input',
				debounce( () => this.handleSearchChange( searchInput ), 300 )
			);
		}
	}

	bindSelectedFiltersEvents() {
		const selectedFiltersContainer = this.container.querySelector(
			'[wcb-filter-selected]'
		);
		const clearAllButton = selectedFiltersContainer.querySelector(
			'[wcb-filter-selected-clear-all]'
		);

		selectedFiltersContainer.addEventListener( 'click', ( event ) => {
			const filterItem = event.target.closest(
				'[wcb-filter-selected-item] button'
			);

			if ( filterItem ) {
				const taxonomy =
					filterItem.parentElement.getAttribute( 'data-taxonomy' );
				const term =
					filterItem.parentElement.getAttribute( 'data-term' );
				this.removeSelectedFilter( taxonomy, term );
			}
		} );

		if ( clearAllButton ) {
			clearAllButton.addEventListener( 'click', () =>
				this.clearAllFilters()
			);
		}
	}

	// TODO: Should make a separate bind for select items.
	bindOrderByEvents() {
		const selects = this.container.querySelectorAll(
			'[data-filter-name] select'
		);
		selects.forEach( ( select ) => {
			select.addEventListener( 'change', () =>
				this.handleOrderByChange( select )
			);
		} );
	}

	handleCheckboxChange( checkbox ) {
		const taxonomy = checkbox
			.closest( '[data-filter-name]' )
			.getAttribute( 'data-filter-name' );
		const term = checkbox.value;
		const label = checkbox.getAttribute( 'data-label' );

		const filterValue = {
			slug: term,
			label,
		};

		if ( checkbox.checked ) {
			addUrlParameterValue( taxonomy, term );
			this.updateSelectedFiltersArray(
				'taxonomy',
				taxonomy,
				filterValue,
				'add'
			);
		} else {
			removeUrlParameterValue( taxonomy, term );
			this.updateSelectedFiltersArray(
				'taxonomy',
				taxonomy,
				filterValue,
				'remove'
			);
		}

		this.fetchPageCallback( true );
		this.updateSelectedFilters();
	}

	handleSearchChange( searchInput ) {
		const searchQuery = searchInput.value;

		this._selectedFilters = this._selectedFilters.filter(
			( filter ) => filter.type !== 'search'
		);

		// If we have a value then set the url parameter, otherwise remove it.
		if ( searchQuery ) {
			setUrlParameter( 'search', searchQuery );
			this._selectedFilters.push( {
				type: 'search',
				filter_name: 'search',
				filter_value: [ { slug: searchQuery, label: searchQuery } ],
			} );
		} else {
			deleteUrlParameter( 'search' );
		}

		this.fetchPageCallback( true );
		this.updateSelectedFilters();
	}

	// TODO: finish this.
	handleSelectChange( select ) {
		const filterName = select
			.closest( '[data-filter-name]' )
			.getAttribute( 'data-filter-name' );
		const filterValue = select.value;
		const label = select.options[ select.selectedIndex ].text;

		const filterData = {
			type: 'select',
			filter_name: filterName,
			filter_value: [ { slug: filterValue, label } ],
		};

		if ( filterValue ) {
			setUrlParameter( filterName, filterValue );
			this.updateSingleValueFilter( filterName, filterData );
		} else {
			deleteUrlParameter( filterName );
			this.updateSingleValueFilter( filterName, null );
		}

		this.fetchPageCallback( true );
		this.updateSelectedFilters();
	}

	handleOrderByChange( select ) {
		const filterName = select
			.closest( '[data-filter-name]' )
			.getAttribute( 'data-filter-name' );
		const filterValue = select.value;
		const label = select.options[ select.selectedIndex ].text;

		const filterData = {
			type: 'order_by',
			filter_name: filterName,
			filter_value: [ { slug: filterValue, label } ],
		};

		if ( filterValue ) {
			setUrlParameter( filterName, filterValue );
			this.updateSingleValueFilter( filterName, filterData );
		} else {
			deleteUrlParameter( filterName );
			this.updateSingleValueFilter( filterName, null );
		}

		this.fetchPageCallback( true );
		this.updateSelectedFilters();
	}

	updateSelectedFilters() {
		const selectedFiltersContainer = this.container.querySelector(
			'[wcb-filter-selected]'
		);
		const selectedFiltersItems = this.container.querySelector(
			'[wcb-filter-selected-items]'
		);
		const selectedFiltersTemplate = this.container.querySelector(
			'[wcb-filter-selected-template]'
		);

		const excludeFilters = [ 'cpage', 'order_by' ];

		selectedFiltersItems.innerHTML = '';

		// Remove filters that should be excluded from the show filters element.
		const filtersToShow = this._selectedFilters.filter(
			( filter ) => ! excludeFilters.includes( filter.filter_name )
		);

		// Show the container if there are selected filters.
		if ( filtersToShow.length ) {
			selectedFiltersContainer.setAttribute( 'has-selected-filters', '' );
		} else {
			selectedFiltersContainer.removeAttribute( 'has-selected-filters' );
		}

		filtersToShow.forEach( ( filter ) => {
			filter.filter_value.forEach( ( value ) => {
				const filterElement =
					selectedFiltersTemplate.content.cloneNode( true );
				filterElement
					.querySelector( '[data-taxonomy]' )
					.setAttribute( 'data-taxonomy', filter.filter_name );
				filterElement
					.querySelector( '[data-term]' )
					.setAttribute( 'data-term', value.slug );
				filterElement.querySelector(
					'.wcb-c-archive-selected-filters__item-label'
				).textContent =
					filter.filter_name === 'search'
						? 'Search term: ' + value.label
						: value.label;
				selectedFiltersItems.appendChild( filterElement );
			} );
		} );
	}

	updateSelectedFiltersArray( type, filterName, filterValue, action ) {
		if ( action === 'add' ) {
			const existingFilter = this._selectedFilters.find(
				( filter ) =>
					filter.type === type && filter.filter_name === filterName
			);
			if ( existingFilter ) {
				existingFilter.filter_value.push( filterValue );
			} else {
				this._selectedFilters.push( {
					type,
					filter_name: filterName,
					filter_value: [ filterValue ],
				} );
			}
		} else if ( action === 'remove' ) {
			const existingFilter = this._selectedFilters.find(
				( filter ) =>
					filter.type === type && filter.filter_name === filterName
			);
			if ( existingFilter ) {
				const valueIndex = existingFilter.filter_value.findIndex(
					( value ) => value.slug === filterValue.slug
				);
				if ( valueIndex > -1 ) {
					existingFilter.filter_value.splice( valueIndex, 1 );
				}
				if ( existingFilter.filter_value.length === 0 ) {
					this._selectedFilters = this._selectedFilters.filter(
						( filter ) => filter !== existingFilter
					);
				}
			}
		}
	}

	removeSelectedFilter( taxonomy, term ) {
		removeUrlParameterValue( taxonomy, term );
		this._selectedFilters = this._selectedFilters.filter( ( filter ) => {
			if ( filter.filter_name === taxonomy ) {
				filter.filter_value = filter.filter_value.filter(
					( value ) => value.slug !== term
				);
				return filter.filter_value.length > 0;
			}
			return true;
		} );

		// If the taxonomy is search, clear the search input.
		if ( taxonomy === 'search' ) {
			const searchInput = this.container.querySelector(
				'[wcb-archive-filter-search-input]'
			);
			if ( searchInput ) {
				searchInput.value = '';
			}
		} else {
			// Uncheck the corresponding checkbox.
			const checkbox = this.container.querySelector(
				`[data-filter-name="${ taxonomy }"] input[value="${ term }"]`
			);
			if ( checkbox ) {
				checkbox.checked = false;
			}
		}

		this.fetchPageCallback( true );
		this.updateSelectedFilters();
	}

	updateSingleValueFilter( filterName, filterData ) {
		const filterIndex = this._selectedFilters.findIndex(
			( filter ) => filter.filter_name === filterName
		);

		if ( filterData ) {
			if ( filterIndex !== -1 ) {
				this._selectedFilters[ filterIndex ] = filterData;
			} else {
				this._selectedFilters.push( filterData );
			}
		} else if ( filterIndex !== -1 ) {
			this._selectedFilters.splice( filterIndex, 1 );
		}
	}

	clearAllFilters() {
		// Remove all URL parameters.
		const url = new URL( window.location );
		url.search = '';
		window.history.replaceState( {}, document.title, url );

		// Uncheck all checkboxes.
		const checkboxes = this.container.querySelectorAll(
			'input[type="checkbox"]'
		);
		checkboxes.forEach( ( checkbox ) => {
			checkbox.checked = false;
		} );

		// Clear the search input.
		const searchInput = this.container.querySelector(
			'[wcb-archive-filter-search-input]'
		);
		if ( searchInput ) {
			searchInput.value = '';
		}

		// Reset all select menus.
		const selects = this.container.querySelectorAll( 'select' );
		selects.forEach( ( select ) => {
			select.selectedIndex = 0;
		} );

		this._selectedFilters = [];

		this.fetchPageCallback( true );
		this.updateSelectedFilters();
	}

	getSelectedFilters() {
		return this._selectedFilters;
	}
}

export default Filters;
