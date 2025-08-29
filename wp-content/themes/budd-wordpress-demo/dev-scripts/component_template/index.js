/* eslint-disable no-console */

const fs = require( 'fs' );
const path = require( 'path' );
const Mustache = require( 'mustache' );

// ANSI escape codes for colors.
const reset = '\x1b[0m';
const green = '\x1b[32m';
const yellow = '\x1b[33m';
const red = '\x1b[31m';

// Get the component name from the command line arguments.
let componentName = process.argv[ 2 ];

// Sanitize the component name.
componentName = path.basename( componentName );

console.log( `${ green }\nCreating component: ${ componentName }\n${ reset }` );

// Define the templates and output files.
const templates = [
	{
		templateFile: 'template.php.mustache',
		outputFile: `${ componentName }.php`,
	},
	{ templateFile: 'style.scss.mustache', outputFile: 'style.scss' },
	{ templateFile: 'editor.scss.mustache', outputFile: 'editor.scss' },
];

// Define the data for the template.
const data = {
	name: componentName,
};

// Create the component folder inside the components folder.
const projectRoot = process.cwd();
const componentsFolder = path.join( projectRoot, 'components' );

// Check if components folder exists, if not, create it.
if ( ! fs.existsSync( componentsFolder ) ) {
	fs.mkdirSync( componentsFolder );
	console.log( `${ yellow }Created folder: ${ componentsFolder }\n${ reset }` );
}

const componentFolder = path.join( componentsFolder, componentName );

// Check if component already exists, if so, exit.
if ( fs.existsSync( componentFolder ) ) {
	console.log(
		`${ red }Component ${ componentName } already exists. Exiting.\n${ reset }`,
	);
	process.exit( 1 );
}

fs.mkdirSync( componentFolder, { recursive: true } );
console.log( `${ yellow }Created folder: ${ componentFolder }\n${ reset }` );

// Render the templates and write to files.
for ( const { templateFile, outputFile } of templates ) {
	const template = fs.readFileSync(
		path.join( __dirname, templateFile ),
		'utf-8',
	);
	const output = Mustache.render( template, data );
	fs.writeFileSync( path.join( componentFolder, outputFile ), output );
	console.log(
		`${ yellow }Created file: ${ path.join(
			componentFolder,
			outputFile,
		) }\n${ reset }`,
	);
}

console.log( `${ green }Component creation complete.\n${ reset }` );
