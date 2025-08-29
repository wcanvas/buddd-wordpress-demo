// TODO: Merge this template with the block_template as a variation.

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
			return {
				...view,
				camelCaseName: toCamelCase( view.title ),
			};
		},
		$schema:
			'https://schemas.wp.org/trunk/block.json',
		apiVersion: 3,
		namespace: 'wcb',
		category: 'wcb-blocks',
		description: 'Block description.',
		textdomain: 'wcanvas-boilerplate',
		example: {
			attributes: {
				data: {},
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
		render: 'file:./render.php',
		editorScript: [ 'file:./index.js' ],
		viewScript: [ 'file:./view.js' ],
		editorStyle: [ 'file:./index.css' ],
		style: [ 'file:./style-index.css' ],
	},
	blockTemplatesPath: join( __dirname, 'templates/block' ),
};
