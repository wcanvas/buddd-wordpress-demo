/*
 * Create a Api object with Axios and
 * configure it for the WordPRess Rest Api.
 *
 * The 'apiArgs' object is injected into the page
 * using the WordPress wp_localize_script function.
 */

import axios from 'axios';

if ( ! apiArgs ) {
	const apiArgs = {
		rootapiurl: `${ window.location.origin }/wp-json/`,
		nonce: false,
	};
}

const Api = axios.create( {
	baseURL: apiArgs.rootapiurl,
	headers: {
		'content-type': 'application/json',
	},
} );

export default Api;
