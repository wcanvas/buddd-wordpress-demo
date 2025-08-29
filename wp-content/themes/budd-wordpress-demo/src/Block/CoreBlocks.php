<?php
/**
 * This class setup all Core Blocks
 *
 * @package WCB
 */

namespace WCB\Block;

defined( 'ABSPATH' ) || die();

use WP_HTML_Tag_Processor;

/**
 * This class setup all Core Blocks
 */
class CoreBlocks {

	/**
	 * Allowed blocks array.
	 *
	 * To find all blocks in WordPress go to a gutenberg editor (page or post), open the browser's developer
	 * console and paste the code snippet below. Remove any ACF blocks as this blocks will always be present on the editor.
	 *
	 * wp.blocks.getBlockTypes().map( block => block.name )
	 *
	 * Remove from this array ANY block that you want to HIDE from ALL editors (post/page and site editors)
	 *
	 * Some blocks should not be remove as this form part of the core functionality of the editor are:
	 * - core/block
	 * - core/template-part
	 * - core/pattern
	 * - core/missing
	 * - core/post-content (this block is used to render the content of a post/page).
	 *
	 * @var array
	 */
	private static $allowed_blocks = array(
		'core/paragraph',
		'core/image',
		'core/heading',
		'core/gallery',
		'core/list',
		'core/list-item',
		'core/quote',
		'core/archives',
		'core/audio',
		'core/calendar',
		'core/categories',
		'core/freeform',
		'core/code',
		'core/column',
		'core/columns',
		'core/cover',
		'core/details',
		'core/embed',
		'core/file',
		'core/group',
		'core/html',
		'core/latest-comments',
		'core/latest-posts',
		'core/media-text',
		'core/missing',
		'core/more',
		'core/nextpage',
		'core/page-list',
		'core/page-list-item',
		'core/pattern',
		'core/preformatted',
		'core/pullquote',
		'core/block',
		'core/rss',
		'core/search',
		'core/separator',
		'core/shortcode',
		'core/social-link',
		'core/social-links',
		'core/spacer',
		'core/table',
		'core/tag-cloud',
		'core/text-columns',
		'core/verse',
		'core/video',
		'core/footnotes',
		'core/navigation',
		'core/navigation-link',
		'core/navigation-submenu',
		'core/site-logo',
		'core/site-title',
		'core/site-tagline',
		'core/query',
		'core/template-part',
		'core/avatar',
		'core/post-title',
		'core/post-excerpt',
		'core/post-featured-image',
		'core/post-content',
		'core/post-author',
		'core/post-author-name',
		'core/post-date',
		'core/post-terms',
		'core/post-navigation-link',
		'core/post-template',
		'core/query-pagination',
		'core/query-pagination-next',
		'core/query-pagination-numbers',
		'core/query-pagination-previous',
		'core/query-no-results',
		'core/read-more',
		'core/comments',
		'core/comment-author-name',
		'core/comment-content',
		'core/comment-date',
		'core/comment-edit-link',
		'core/comment-reply-link',
		'core/comment-template',
		'core/comments-title',
		'core/comments-pagination',
		'core/comments-pagination-next',
		'core/comments-pagination-numbers',
		'core/comments-pagination-previous',
		'core/post-comments-form',
		'core/home-link',
		'core/loginout',
		'core/term-description',
		'core/query-title',
		'core/post-author-biography',
		'core/legacy-widget',
		'core/widget-group',
	);

	/**
	 * CoreBlocks construct function
	 */
	public function __construct() {
		add_filter( 'allowed_block_types_all', array( $this, 'allow_list_block_types' ), 10, 2 );
		add_action( 'after_setup_theme', array( $this, 'remove_core_block_patterns' ) );
		// Prevent patterns loading from the wordpress.org directory.
		add_filter( 'should_load_remote_block_patterns', '__return_false' );
		add_filter( 'render_block', array( $this, 'blocks_wrappers' ), 10, 2 );
		add_action( 'init', array( $this, 'register_custom_core_block_styles' ) );
		// Globally disable the Block Directory (When searching for a block in the inserter sidebar).
		remove_action( 'enqueue_block_editor_assets', 'wp_enqueue_editor_block_directory_assets' );
		add_filter( 'block_editor_settings_all', array( $this, 'disable_openverse' ), 10, 1 );
	}

	/**
	 * Allow an array of block types. This function just hides the blocks from the inserter. It does not Remove them.
	 *
	 * @param array  $allowed_block_types allowed block types.
	 * @param object $editor_context editor context.
	 * @return array
	 */
	public function allow_list_block_types( $allowed_block_types, $editor_context ) { // phpcs:ignore
		$this->get_custom_blocks();

		// Here you can add or remove block from a specific block editor view. Example: $editor_context->post->post_type. to target a specific post type.
		if ( 'core/edit-site' !== $editor_context->name ) {
			$blocks_to_remove = array(
				'wcb/navbar',
				'wcb/footer',
				'wcb/header',
				'wcb/single-hero',
			);

			$result = array_diff( self::$allowed_blocks, $blocks_to_remove );
			$result = array_values( $result );

			return $result;
		}

		return self::$allowed_blocks;
	}

	/**
	 * Get custom blocks from the block manifest, and add them to the allowed blocks array.
	 */
	private function get_custom_blocks() {
		$blocks = include get_template_directory() . '/assets/build/block-manifest.php';

		if ( $blocks ) {
			foreach ( $blocks as $block ) {
				if ( isset( $block['name'] ) ) {
					self::$allowed_blocks[] = $block['name'];
				}
			}
		}
	}

	/**
	 * Remove core block patterns
	 */
	public function remove_core_block_patterns() {
		remove_theme_support( 'core-block-patterns' );
	}

	/**
	 * This method add container classes and container elements
	 * to the core blocks, you can add as many cases you need.
	 *
	 * @param string $block_content the blog html.
	 * @param array  $block block properties.
	 */
	public function blocks_wrappers( $block_content, $block ) {
		$block_name = $block['blockName'];
		$needle     = 'core/';

		if ( ! $block_name ) {
			return $block_content;
		}

		$pos1 = strpos( $block_name, $needle );
		$pos2 = strpos( $block_name, 'template-part' );
		$pos3 = strpos( $block_name, 'post-content' );

		// Check if is a core block or template-part.
		if ( false !== $pos1 && ! $pos2 && ! $pos3 && strpos( $block_name, 'image' ) === false ) {
			$block_class   = 'wp-block-' . substr( $block_name, $pos1 + strlen( $needle ) );
			$block_content = new WP_HTML_Tag_Processor( $block_content );
			$block_content->next_tag(); /* first tag should always be ul or ol */
			$block_content->add_class( $block_class );

			$block_content->get_updated_html();

		}

		return $block_content;
	}

	/**
	 * Register custom core block styles.
	 */
	public function register_custom_core_block_styles() {
		if ( file_exists( get_template_directory() . '/assets/scss/core-blocks/' ) ) {

			$styles_to_enqueue = glob( get_template_directory() . '/assets/build/css/core-blocks/*.css' );

			foreach ( $styles_to_enqueue as $style ) {
				$file_name = pathinfo( $style, PATHINFO_FILENAME );

				$relative_path = "assets/build/css/core-blocks/{$file_name}";

				wp_enqueue_block_style(
					"core/{$file_name}",
					array(
						'handle' => "wcb-{$file_name}-core-custom-style",
						'src'    => get_theme_file_uri( "{$relative_path}.css" ),
						'path'   => get_theme_file_path( "{$relative_path}.css" ),
						'deps'   => array(),
						'ver'    => filemtime( get_theme_file_path( "{$relative_path}.css" ) ),
					)
				);
			}
		}
	}

	/**
	 * Disable Openverse.
	 *
	 * @param array $settings The current block editor settings.
	 * @return array          The modified block editor settings.
	 */
	public function disable_openverse( $settings ) {
		$settings['enableOpenverseMediaCategory'] = false;
		return $settings;
	}
}
