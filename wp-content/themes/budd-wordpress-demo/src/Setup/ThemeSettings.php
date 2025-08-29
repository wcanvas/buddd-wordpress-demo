<?php
/**
 * This class setup all basic theme features
 *
 * @package WCB
 */

namespace WCB\Setup;

defined( 'ABSPATH' ) || die();

/**
 * This class setup all basic theme features
 */
class ThemeSettings {
	/**
	 * Path to build folder.
	 *
	 * @var string
	 */
	private $build_files_path;

	/**
	 * URL path of build folder.
	 *
	 * @var string
	 */
	private $path;

	/**
	 * Absolute path of build folder.
	 *
	 * @var string
	 */
	private $absolute_path;

	/**
	 * ThemeSettings construct function
	 */
	public function __construct() {
		$this->build_files_path = '/assets/build/';
		$this->path             = get_template_directory_uri() . $this->build_files_path;
		$this->absolute_path    = get_template_directory() . $this->build_files_path;

		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ), 10 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
		add_action( 'login_enqueue_scripts', array( $this, 'admin_styles' ), 10 );
		add_action( 'login_enqueue_scripts', 'wp_enqueue_global_styles' ); // Adds theme.json styles to login page.
		add_action( 'after_setup_theme', array( $this, 'theme_support' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'resizable_sidebar_assets' ) );
		add_action( 'wp_head', array( $this, 'critical_styles' ), 1 );
		add_filter( 'style_loader_src', array( $this, 'add_custom_style_link_version' ), 15, 1 );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor_global_styles' ), 0 );
		add_action( 'enqueue_block_assets', array( $this, 'enqueue_block_editor_global_styles' ), 0 );
		add_action( 'enqueue_block_assets', array( $this, 'enqueue_site_editor_global_styles' ), 0 );
		add_action( 'init', array( $this, 'enqueue_frontend_global_styles' ), 0 );

		/**
		 * Makes theme's style a link to a stylesheet instead of a inline style.
		 *
		 * @link https://developer.wordpress.org/reference/hooks/styles_inline_size_limit/
		 * Use => add_filter( 'styles_inline_size_limit', function(){return 5000;} );.
		 */
	}

	/**
	 * Generate filemtime version query strings for block styles.
	 *
	 * Doc links:
	 * https://developer.wordpress.org/reference/hooks/style_loader_src/
	 * https://developer.wordpress.org/reference/hooks/script_loader_src/
	 *
	 * @param string $src string of style.
	 */
	public function add_custom_style_link_version( $src ) {
		// Only modify styles from the blocks folder.
		$folder_to_check = '/assets/build/blocks/';

		// Check if the path contains the specified folder structure.
		if ( strpos( $src, $folder_to_check ) !== false ) {
			// Extract the directory part of the path.
			$directory = dirname( wp_parse_url( $src, PHP_URL_PATH ) );

			// Split the directory path by '/'.
			$directory_parts = explode( '/', $directory );

			// Get the folder name where styles resides.
			$folder_name = end( $directory_parts );

			// Extract the filename from the path.
			$filename = basename( wp_parse_url( $src, PHP_URL_PATH ) );

			// Get modified time of the file (style-index.css for the frontend and editor or index.css for the editor only).
			$css_modified_time = filemtime( get_template_directory() . '/assets/build/blocks/' . $folder_name . '/' . $filename );

			// Replace the query string with a custom string.
			$custom_query_string = '?ver=' . $css_modified_time;
			$new_link            = preg_replace( '/\?ver=[^&]+/', $custom_query_string, $src );

			return $new_link;
		}

		// if style is not inside the blocks folder.
		return $src;
	}

	/**
	 * Embed critical styles inline
	 */
	public function critical_styles() {
		echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
	}

	/**
	 * Enqueue scripts
	 */
	public function scripts() {
		// Load Scripts.
		wp_enqueue_script(
			'wcb-boilerplate-main',
			$this->path . 'index-script.js',
			null,
			filemtime( $this->absolute_path . 'index-script.js' ),
			array(
				'strategy' => 'defer',
			)
		);

		// Load API vars to Axios.
		wp_localize_script(
			'wcb-boilerplate-main',
			'apiArgs',
			array(
				'rootapiurl' => esc_url_raw( rest_url() ),
				'nonce'      => wp_create_nonce( 'wp_rest' ),
			)
		);

		wp_localize_script(
			'wcb-boilerplate-main',
			'ajax',
			array(
				'url' => admin_url( 'admin-ajax.php' ),
			)
		);
	}

	/**
	 * Adds style to admin screen.
	 */
	public function admin_styles() {
		$css_file = "{$this->build_files_path}tw-admin.css";

		wp_enqueue_style( 'wcb-boilerplate-admin-style', get_theme_file_uri( $css_file ), array(), filemtime( get_template_directory() . $this->build_files_path . 'tw-admin.css' ) );
	}

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	public function theme_support() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 */
		load_theme_textdomain( 'wcanvas-boilerplate', get_template_directory() . '/languages' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 *
		 * NOTE: If the YOAST SEO Plugins is active, comment this line as YOAST already handles the title tag.
		 */
		// add_theme_support( 'title-tag' );.

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );
	}


	/**
	 * Enqueues the resizable sidebar assets if we have the option enabled.
	 */
	public function resizable_sidebar_assets() {

		$asset_file = include get_template_directory() . '/assets/build/resizable-sidebar-script.asset.php';

		if ( ! class_exists( 'ACF' ) ) {
			return;
		}

		// Optional Resizable Sidebar.
		if ( ! get_field( 'deactivate_resizable_sidebar', 'option' ) ) {
			wp_enqueue_script( 'jquery-ui-resizable' );
			wp_enqueue_script( 'resizable-sidebar-script', get_template_directory_uri() . '/assets/build/resizable-sidebar-script.js', $asset_file['dependencies'], $asset_file['version'], true );
			wp_enqueue_style( 'resizable-sidebar-style', get_template_directory_uri() . '/assets/build/resizable-sidebar.css', array(), '1.0.0' );
		}
	}

	/**
	 * Enqueue block editor global styles.
	 */
	public function enqueue_block_editor_global_styles() {
		if ( is_admin() ) {
			$current_screen = get_current_screen();

			if ( 'site-editor' !== $current_screen->id ) {
				$this->enqueue_global_styles( 'editor' );
			}
		}
	}

	/**
	 * Enqueue site editor global styles.
	 */
	public function enqueue_site_editor_global_styles() {
		if ( is_admin() ) {
			$current_screen = get_current_screen();

			if ( 'site-editor' === $current_screen->id ) {
				$this->enqueue_global_styles( 'editor' );
			}
		}
	}

	/**
	 * Enqueue frontend global styles.
	 */
	public function enqueue_frontend_global_styles() {
		if ( ! is_admin() ) {
			$this->enqueue_global_styles( 'frontend' );
		}
	}

	/**
	 * Enqueue global styles depending of the context
	 *
	 * @param string $context Context of the styles to enqueue. Can be 'editor' or 'frontend'.
	 */
	private function enqueue_global_styles( $context ) {
		switch ( $context ) {
			case 'editor':
				wp_enqueue_style( 'wcb-global-editor-style', get_template_directory_uri() . '/assets/build/tw-editor.css', array(), filemtime( get_template_directory() . '/assets/build/tw-editor.css' ) );
				wp_style_add_data( 'wcb-global-editor-style', 'path', get_template_directory() . '/assets/build/tw-editor.css' );
				break;
			case 'frontend':
				wp_enqueue_style( 'wcb-tailwind', get_template_directory_uri() . '/assets/build/tailwind.css', array(), filemtime( get_template_directory() . '/assets/build/tailwind.css' ) );
				wp_style_add_data( 'wcb-tailwind', 'path', get_template_directory() . '/assets/build/tailwind.css' );
				break;
			default:
				break;
		}
	}
}
