/**
 * Create or update a URL parameter.
 * @param {string} param - The parameter name.
 * @param {string} value - The parameter value.
 */
export function setUrlParameter( param, value ) {
	const url = new URL( window.location );
	url.searchParams.set( param, value );
	window.history.pushState( {}, '', url );
}

/**
 * Read a URL parameter.
 * @param {string} param - The parameter name.
 * @return {string|null} - The parameter value or null if not found.
 */
export function getUrlParameter( param ) {
	const url = new URL( window.location );
	return url.searchParams.get( param );
}

/**
 * Delete a URL parameter.
 * @param {string} param - The parameter name.
 */
export function deleteUrlParameter( param ) {
	const url = new URL( window.location );
	url.searchParams.delete( param );
	window.history.pushState( {}, '', url );
}

/**
 * Check if a URL parameter exists.
 * @param {string} param - The parameter name.
 * @return {boolean} - True if the parameter exists, false otherwise.
 */
export function hasUrlParameter( param ) {
	const url = new URL( window.location );
	return url.searchParams.has( param );
}

/**
 * Add a value to a URL parameter that supports multiple values.
 * @param {string} param - The parameter name.
 * @param {string} value - The value to add.
 */
export function addUrlParameterValue( param, value ) {
	const url = new URL( window.location );
	const values = url.searchParams.get( param )?.split( ',' ) || [];
	if ( ! values.includes( value ) ) {
		values.push( value );
		url.searchParams.set( param, values.join( ',' ) );
		window.history.pushState( {}, '', url );
	}
}

/**
 * Remove a value from a URL parameter that supports multiple values.
 * @param {string} param - The parameter name.
 * @param {string} value - The value to remove.
 */
export function removeUrlParameterValue( param, value ) {
	const url = new URL( window.location );
	let values = url.searchParams.get( param )?.split( ',' ) || [];
	values = values.filter( ( v ) => v !== value );
	if ( values.length > 0 ) {
		url.searchParams.set( param, values.join( ',' ) );
	} else {
		url.searchParams.delete( param );
	}
	window.history.pushState( {}, '', url );
}

/**
 * Get all values of a URL parameter that supports multiple values.
 * @param {string} param - The parameter name.
 * @return {string[]} - An array of values.
 */
export function getUrlParameterValues( param ) {
	const url = new URL( window.location );
	return url.searchParams.get( param )?.split( ',' ) || [];
}
