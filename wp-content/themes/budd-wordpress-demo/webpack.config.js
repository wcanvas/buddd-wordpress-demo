const getDependencies = require( './dev-scripts/dependencies.js' );
getDependencies();

const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
const DependencyExtractionWebpackPlugin = require( '@wordpress/dependency-extraction-webpack-plugin' );

// Utilities.
const path = require( 'path' );
const { globSync } = require( 'glob' );
const fs = require( 'fs' );

// External Utilities.
const BrowserSyncPlugin = require( 'browser-sync-webpack-plugin' );
const RemoveEmptyScriptsPlugin = require( 'webpack-remove-empty-scripts' );

// Live local server proxy.
require( 'dotenv' ).config();
const currentPath = __dirname;
const three = currentPath.split( '/' );
const localhost =
	'https://' + three[ three.indexOf( 'Local Sites' ) + 1 ] + '.local';

// Load dependencies from JSON file.
const dependenciesPath = path.resolve( __dirname, 'dependencies.json' );
let dependencies = {};
if ( fs.existsSync( dependenciesPath ) ) {
	dependencies = JSON.parse( fs.readFileSync( dependenciesPath, 'utf-8' ) );
}

/**
 * Custom entries output path.
 *
 * Note: From the package.json, we are specifying the output for all file to assets/build/blocks.
 * For the custom entries we want to go a level up to just assets/build/. This way we leave the blocks on their own folder.
 */

const customEntriesOutputPath = '../';

/**
 * Generates custom entry objects for SCSS files based on the provided type.
 *
 * For 'core-blocks', it generates entries for each SCSS file in the './assets/scss/core-blocks/' directory.
 * For 'components', it generates entries for 'style.scss' and 'editor.scss' files in each component directory.
 *
 * @param {string} type - The type of custom entry. Can be 'core-blocks' or 'components'.
 * @return {Object|boolean} - An object where keys are output paths and values are absolute paths to the corresponding SCSS files. Returns false if the type is not recognized.
 */
const customEntryCss = ( type ) => {
	switch ( type ) {
		case 'core-blocks':
			return globSync( `./assets/scss/core-blocks/*.scss` ).reduce(
				( files, filepath ) => {
					const name = path.parse( filepath ).name;

					files[
						`${ customEntriesOutputPath }css/core-blocks/${ name }`
					] = path.resolve(
						process.cwd(),
						`assets/scss/core-blocks`,
						`${ name }.scss`
					);

					return files;
				},
				{}
			);
		case 'components':
			return globSync( `./components/**/*.scss` ).reduce(
				( files, filepath ) => {
					// Get the directory name of the .scss file.
					const dirPath = path.dirname( filepath );
					// Get the base name of the directory.
					const name = path.basename( dirPath );

					// Check if the file is 'style.scss' or 'editor.scss' and add to files object accordingly.
					if ( filepath.endsWith( 'style.scss' ) ) {
						files[
							`${ customEntriesOutputPath }components/${ name }/frontend`
						] = path.resolve(
							process.cwd(),
							`components/${ name }`,
							`style.scss`
						);
					} else if ( filepath.endsWith( 'editor.scss' ) ) {
						files[
							`${ customEntriesOutputPath }components/${ name }/index`
						] = path.resolve(
							process.cwd(),
							`components/${ name }`,
							`editor.scss`
						);
					}

					return files;
				},
				{}
			);
		default:
			break;
	}
	return false;
};

module.exports = {
	...defaultConfig,
	entry: {
		...defaultConfig.entry(),
		...customEntryCss( 'core-blocks' ),
		...customEntryCss( 'components' ),
		[ `${ customEntriesOutputPath }index-script` ]: path.resolve(
			process.cwd(),
			'assets/js',
			'index.js'
		),
		[ `${ customEntriesOutputPath }admin-script` ]: path.resolve(
			process.cwd(),
			'assets/js',
			'admin.js'
		),
		[ `${ customEntriesOutputPath }resizable-sidebar-script` ]:
			path.resolve( process.cwd(), 'assets/js', 'resizable-sidebar.js' ),
		[ `${ customEntriesOutputPath }resizable-sidebar` ]: path.resolve(
			process.cwd(),
			'assets/styles',
			'resizable-sidebar.scss'
		),
	},
	module: {
		...defaultConfig.module,
		/**
		 * Remove url-loader from the default rules. This is to prevent SVGs from being inlined in the CSS.
		 */
		rules: defaultConfig.module.rules
			.map( ( rule ) => {
				if ( rule.use && Array.isArray( rule.use ) ) {
					rule.use = rule.use.filter(
						( loader ) => loader !== 'url-loader'
					);
				}
				return rule;
			} )
			.filter(
				( rule ) =>
					! (
						rule.test &&
						rule.test.source === '\\.svg$' &&
						rule.issuer &&
						rule.issuer.source === '\\.(pc|sc|sa|c)ss$'
					)
			),
	},
	plugins: [
		...defaultConfig.plugins.filter(
			( plugin ) =>
				plugin.constructor.name !== 'DependencyExtractionWebpackPlugin'
		),
		new DependencyExtractionWebpackPlugin( {
			requestToExternal( request ) {
				if ( dependencies[ request ] ) {
					return dependencies[ request ].globalObject;
				}
			},
			requestToHandle( request ) {
				if ( dependencies[ request ] ) {
					return dependencies[ request ].handle;
				}
			},
		} ),
		new BrowserSyncPlugin( {
			host: 'localhost',
			port: 3000,
			proxy: process.env.LOCAL_SERVER_URL || localhost,
			files: [ 'src/**/*.php', 'components/**/*.php', 'functions.php' ],
			open: false,
		} ),
		new RemoveEmptyScriptsPlugin( {
			stage: RemoveEmptyScriptsPlugin.STAGE_AFTER_PROCESS_PLUGINS,
		} ),
	],
};
