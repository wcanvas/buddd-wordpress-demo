/** @type {import('tailwindcss').Config} */
const plugin = require( 'tailwindcss/plugin' );
const defaultColors = require( 'tailwindcss/colors' );
const fs = require( 'fs' );
const path = require( 'path' );

// Read and parse the theme.json file.
const themePath = path.join( __dirname, 'theme.json' );
const themeData = JSON.parse( fs.readFileSync( themePath, 'utf8' ) );

// Extract colors, sizes, and font families from standard WordPress settings.
const standardColors = themeData.settings.color.palette.reduce(
	( acc, color ) => {
		acc[ color.slug ] = `var(--wp--preset--color--${ color.slug })`;
		return acc;
	},
	{}
);

const standardFontSizes = themeData.settings.typography.fontSizes.reduce(
	( acc, size ) => {
		acc[ size.slug ] = `var(--wp--preset--font-size--${ size.slug })`;
		return acc;
	},
	{}
);

const standardFontFamilies = themeData.settings.typography.fontFamilies.reduce(
	( acc, family ) => {
		acc[ family.slug ] = `var(--wp--preset--font-family--${ family.slug })`;
		return acc;
	},
	{}
);

// Dynamic function to extract all custom properties from theme.json
function extractCustomProperties( customSettings ) {
	const themeExtensions = {};

	Object.keys( customSettings ).forEach( ( customKey ) => {
		if (
			typeof customSettings[ customKey ] === 'object' &&
			customSettings[ customKey ] !== null
		) {
			themeExtensions[ customKey ] = Object.keys(
				customSettings[ customKey ]
			).reduce( ( acc, valueKey ) => {
				acc[ valueKey ] = `var(--wp--custom--${ customKey
					.replace( /([A-Z])/g, '-$1' )
					.toLowerCase() }--${ valueKey })`;
				return acc;
			}, {} );
		}
	} );

	return themeExtensions;
}

// Extract all custom properties dynamically
const customSettings = themeData.settings.custom || {};
const customExtensions = extractCustomProperties( customSettings );

const customExtensionsWithoutFontSizeAndColors = Object.keys( customExtensions )
	.filter( ( key ) => key !== 'fontSize' && key !== 'color' )
	.reduce( ( result, key ) => {
		result[key] = customExtensions[key];
		return result;
	}, {} );

const config = {
	prefix: 'wcb-',
	content: [
		'./blocks/**/*.php',
		'./blocks/**/*.js',
		'./components/**/*.php',
		'./src/**/*.php',
		'./assets/**/*.js',
		'./assets/styles/**/*.css',
	],
	theme: {
		extend: {
			fontSize: {
				...standardFontSizes,
				...customExtensions.fontSize,
			},
			fontFamily: {
				...standardFontFamilies,
			},
			colors: {
				transparent: 'transparent',
				current: 'currentColor',
				...standardColors,
			},
			...customExtensionsWithoutFontSizeAndColors,
		},
		container: {
			center: true,
			padding: {
				DEFAULT: '1rem',
				sm: '1.5rem',
				lg: '2rem',
				xl: '2rem',
				'2xl': '2.5rem',
			},
		},
	},
	corePlugins: {
		preflight: false, // Remove Tailwind's reset.
	},
	plugins: [ 'postcss-import' ],
};

module.exports = config;
