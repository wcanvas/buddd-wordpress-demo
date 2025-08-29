#!/usr/bin/env node

/**
 * Theme Renaming Script
 *
 * This script renames the WordPress theme by changing prefixes, namespaces,
 * text domains, and other identifiers throughout the codebase.
 *
 * Usage: node rename-theme.js [options]
 */

const fs = require( 'fs' );
const path = require( 'path' );
const readline = require( 'readline' );
const { execSync } = require( 'child_process' );

// Create readline interface for user input
const rl = readline.createInterface( {
	input: process.stdin,
	output: process.stdout,
} );

// VS Code settings file path (relative to WordPress root)
const vscodeSettingsPath = '.vscode/settings.json';

// Default values from the boilerplate
const defaultValues = {
	themeFolder: 'wcanvas-boilerplate',
	namespacePrefix: 'WCB',
	variablePrefix: 'wcb',
	textDomain: 'wcanvas-boilerplate',
	authorUrl: 'example.com',
	authorName: 'White Canvas',
	composerName: 'whitecanvas',
};

// Values to be collected from user
const newValues = {};

// Directories to ignore during the search and replace
const ignoredDirs = [ 'node_modules', 'vendor', '.git', '.github', '.vscode' ];

/**
 * Prompt the user for input with a default value
 *
 * @param {string} question The question to ask
 * @param {string} defaultValue The default value
 * @return {Promise<string>} The user's answer
 */
function promptUser( question, defaultValue ) {
	return new Promise( ( resolve ) => {
		rl.question(
			`${ question } (default: ${ defaultValue }): `,
			( answer ) => {
				resolve( answer.trim() || defaultValue );
			}
		);
	} );
}

/**
 * Process command line arguments
 *
 * @return {Object} The values from command line arguments
 */
function processArguments() {
	const args = process.argv.slice( 2 );
	const values = {};

	for ( let i = 0; i < args.length; i++ ) {
		const arg = args[ i ];

		if ( arg === '--theme-name' && i + 1 < args.length ) {
			values.themeFolder = args[ ++i ];
			// Set text domain to the same value as theme folder
			values.textDomain = values.themeFolder;
		} else if ( arg === '--namespace-prefix' && i + 1 < args.length ) {
			values.namespacePrefix = args[ ++i ];
		} else if ( arg === '--variable-prefix' && i + 1 < args.length ) {
			values.variablePrefix = args[ ++i ];
		} else if ( arg === '--author-url' && i + 1 < args.length ) {
			values.authorUrl = args[ ++i ];
		} else if ( arg === '--author-name' && i + 1 < args.length ) {
			values.authorName = args[ ++i ];
		} else if ( arg === '--composer-name' && i + 1 < args.length ) {
			values.composerName = args[ ++i ];
		}
	}

	return values;
}

/**
 * Get all files in a directory recursively
 *
 * @param {string} dir The directory to search
 * @param {Array} fileList The list of files
 * @return {Array} The list of files
 */
function getAllFiles( dir, fileList = [] ) {
	const files = fs.readdirSync( dir );

	files.forEach( ( file ) => {
		const filePath = path.join( dir, file );
		const stat = fs.statSync( filePath );

		if ( stat.isDirectory() ) {
			// Skip ignored directories
			if ( ! ignoredDirs.includes( file ) ) {
				fileList = getAllFiles( filePath, fileList );
			}
		} else {
			fileList.push( filePath );
		}
	} );

	return fileList;
}

/**
 * Replace strings in a file
 *
 * @param {string} filePath The path to the file
 * @param {Object} replacements The replacements to make
 */
function replaceInFile( filePath, replacements ) {
	try {
		let content = fs.readFileSync( filePath, 'utf8' );
		let modified = false;

		for ( const [ search, replace ] of Object.entries( replacements ) ) {
			if ( content.includes( search ) ) {
				content = content.replace( new RegExp( search, 'g' ), replace );
				modified = true;
			}
		}

		if ( modified ) {
			fs.writeFileSync( filePath, content, 'utf8' );
			process.stdout.write( `Updated: ${ filePath }\n` );
		}
	} catch ( error ) {
		process.stderr.write(
			`Error processing file ${ filePath }: ${ error }\n`
		);
	}
}

/**
 * Rename the theme folder
 *
 * @param {string} oldPath The old path
 * @param {string} newPath The new path
 */
function renameThemeFolder( oldPath, newPath ) {
	try {
		fs.renameSync( oldPath, newPath );
		process.stdout.write(
			`Renamed theme folder from ${ oldPath } to ${ newPath }\n`
		);
	} catch ( error ) {
		process.stderr.write( `Error renaming theme folder: ${ error }\n` );
	}
}

/**
 * Update VS Code settings file
 *
 * @param {string} oldThemeName The old theme name
 * @param {string} newThemeName The new theme name
 */
function updateVSCodeSettings( oldThemeName, newThemeName ) {
	try {
		// Get WordPress root directory (3 levels up from the script)
		const wpRootDir = path.resolve( process.cwd(), '../../..' );
		const settingsPath = path.join( wpRootDir, vscodeSettingsPath );

		// Check if the settings file exists
		if ( ! fs.existsSync( settingsPath ) ) {
			process.stdout.write(
				`VS Code settings file not found at ${ settingsPath }\n`
			);
			return;
		}

		// Read and parse the settings file
		const settings = JSON.parse( fs.readFileSync( settingsPath, 'utf8' ) );
		let modified = false;

		// Update the paths if they exist
		if ( settings.hasOwnProperty( 'phpsab.executablePathCS' ) ) {
			settings[ 'phpsab.executablePathCS' ] = settings[
				'phpsab.executablePathCS'
			].replace( oldThemeName, newThemeName );
			modified = true;
		}

		if ( settings.hasOwnProperty( 'phpsab.executablePathCBF' ) ) {
			settings[ 'phpsab.executablePathCBF' ] = settings[
				'phpsab.executablePathCBF'
			].replace( oldThemeName, newThemeName );
			modified = true;
		}

		if ( modified ) {
			// Write the updated settings back to the file
			fs.writeFileSync(
				settingsPath,
				JSON.stringify( settings, null, 2 ),
				'utf8'
			);
			process.stdout.write(
				`Updated VS Code settings at ${ settingsPath }\n`
			);
		}
	} catch ( error ) {
		process.stderr.write( `Error updating VS Code settings: ${ error }\n` );
	}
}

/**
 * Update .hide_dev_files file with new theme name
 *
 * @param {string} oldThemeName The old theme name
 * @param {string} newThemeName The new theme name
 */
function updateHideDevFiles( oldThemeName, newThemeName ) {
	try {
		// Get WordPress root directory (3 levels up from the script)
		const wpRootDir = path.resolve( process.cwd(), '../../..' );
		const hideDevFilesPath = path.join( wpRootDir, '.hide_dev_files' );

		// Check if the file exists
		if ( fs.existsSync( hideDevFilesPath ) ) {
			// Read, replace, and write back
			let content = fs.readFileSync( hideDevFilesPath, 'utf8' );
			content = content.replace(
				new RegExp( oldThemeName, 'g' ),
				newThemeName
			);
			fs.writeFileSync( hideDevFilesPath, content, 'utf8' );
			process.stdout.write(
				`Updated .hide_dev_files with new theme name\n`
			);
		}
	} catch ( error ) {
		process.stderr.write( `Error updating .hide_dev_files: ${ error }\n` );
	}
}

/**
 * Main function to run the script
 */
async function main() {
	process.stdout.write( 'WordPress Theme Renaming Script\n' );
	process.stdout.write( '===============================\n' );
	process.stdout.write(
		'This script will rename the theme by changing prefixes, namespaces, and other identifiers.\n'
	);
	process.stdout.write(
		'Press Enter to accept the default values or provide your own.\n\n'
	);

	// Process command line arguments
	const argsValues = processArguments();

	// If arguments were provided, use them
	if ( Object.keys( argsValues ).length > 0 ) {
		Object.assign( newValues, argsValues );
	} else {
		// Otherwise, prompt the user for input
		newValues.themeFolder = await promptUser(
			'Theme folder name (also used as text domain)',
			defaultValues.themeFolder
		);
		// Set text domain to the same value as theme folder
		newValues.textDomain = newValues.themeFolder;
		newValues.namespacePrefix = await promptUser(
			'PHP namespace prefix (PSR-4)',
			defaultValues.namespacePrefix
		);
		newValues.variablePrefix = await promptUser(
			'PHP variables, CSS and Tailwind classes prefix',
			defaultValues.variablePrefix
		);
		newValues.authorUrl = await promptUser(
			'Author URL',
			defaultValues.authorUrl
		);
		newValues.authorName = await promptUser(
			'Author name',
			defaultValues.authorName
		);
		newValues.composerName = await promptUser(
			'Composer name',
			defaultValues.composerName
		);
	}

	// Close the readline interface
	rl.close();

	process.stdout.write( '\nRenaming theme with the following values:\n' );
	process.stdout.write(
		`Theme folder and text domain: ${ newValues.themeFolder }\n`
	);
	process.stdout.write(
		`Namespace prefix: ${ newValues.namespacePrefix }\n`
	);
	process.stdout.write( `Variable prefix: ${ newValues.variablePrefix }\n` );
	process.stdout.write( `Author URL: ${ newValues.authorUrl }\n` );
	process.stdout.write( `Author name: ${ newValues.authorName }\n` );
	process.stdout.write( `Composer name: ${ newValues.composerName }\n` );
	process.stdout.write( '\nProcessing files...\n' );

	// Get the current theme directory path
	const currentDir = process.cwd();
	const themeDir = path.resolve( currentDir, '..' );
	const oldThemePath = path.join( themeDir, defaultValues.themeFolder );
	const newThemePath = path.join( themeDir, newValues.themeFolder );

	// Create replacements object
	const replacements = {
		[ defaultValues.namespacePrefix ]: newValues.namespacePrefix,
		[ defaultValues.variablePrefix ]: newValues.variablePrefix,
		[ defaultValues.textDomain ]: newValues.textDomain,
		[ defaultValues.authorUrl ]: newValues.authorUrl,
		[ defaultValues.authorName ]: newValues.authorName,
		[ defaultValues.composerName ]: newValues.composerName,
	};

	// Get all files in the theme directory
	const files = getAllFiles( oldThemePath );

	// Replace strings in all files
	files.forEach( ( file ) => {
		replaceInFile( file, replacements );
	} );

	// Rename the theme folder if needed
	if ( defaultValues.themeFolder !== newValues.themeFolder ) {
		renameThemeFolder( oldThemePath, newThemePath );
	}

	// Update VS Code settings
	updateVSCodeSettings( defaultValues.themeFolder, newValues.themeFolder );

	// Update .hide_dev_files
	updateHideDevFiles( defaultValues.themeFolder, newValues.themeFolder );

	process.stdout.write( '\nTheme renaming completed successfully!\n' );
	process.stdout.write( '\nThe script has modified:\n' );
	process.stdout.write( '1. Theme folder name\n' );
	process.stdout.write( '2. Text domain\n' );
	process.stdout.write( '3. PHP namespace prefix\n' );
	process.stdout.write( '4. Variable and class prefixes\n' );
	process.stdout.write( '5. Author information\n' );
	process.stdout.write( '6. Package.json name\n' );
	process.stdout.write( '7. VS Code settings paths\n' );
}

// Run the script
main().catch( ( error ) => {
	process.stderr.write( `Error: ${ error }\n` );
	process.exit( 1 );
} );
