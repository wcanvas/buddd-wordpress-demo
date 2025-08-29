const { join } = require( 'path' );

/**
 * Convert string to camelCase
 * @param {string} str to return as camelCase
 * @return {string} camelCase string
 */
function toCamelCase( str ) {
	return str
		.replace( /\s(.)/g, function ( match, group ) {
			return group.toUpperCase();
		} )
		.replace( /\s/g, '' )
		.replace( /^(.)/, function ( match, group ) {
			return group.toLowerCase();
		} );
}

module.exports = {
	defaultValues: {
		transformer: ( view ) => {
			// https://github.com/WordPress/gutenberg/pull/55423
			return {
				...view,
				camelCaseName: toCamelCase( view.title ),
				customBlockJSON: {
					acf: {
						mode: 'preview',
						renderTemplate: `./render.php`,
					},
				},
				textdomain: 'wcanvas-boilerplate',
			};
		},
		$schema:
			'https://advancedcustomfields.com/schemas/json/main/block.json',
		apiVersion: 2,
		namespace: 'wcb',
		category: 'wcb-blocks',
		description: 'Block description.',
		example: {
			attributes: {
				data: {},
				mode: 'preview',
			},
			viewportWidth: 1440,
		},
		dashicon: 'smiley',
		supports: {
			customClassName: false,
			align: false,
			renaming: false,
			spacing: {
				margin: true,
				padding: true,
			},
		},
		attributes: {
			style: {
				type: 'object',
				default: {
					spacing: {
						padding: {
							top: 'var:preset|spacing|60',
							bottom: 'var:preset|spacing|60',
						},
					},
				},
			},
		},
		editorScript: [ 'file:./index.js' ],
		viewScript: [ 'file:./view.js' ],
		editorStyle: [ 'file:./index.css' ],
		style: [ 'file:./style-index.css' ],
	},
	variants: {
		dynamic: {},
		static: {},
		plugin: {},
	},
	blockTemplatesPath: join( __dirname, 'templates/block' ),
};
