<?php
/**
 * Boilerplate functions and definitions
 *
 * Prefixes:
 * 'WCB': This should catch classes prefix (autoload psr-4 composer namespace).
 * 'wcb': PHP variables, CSS and Tailwind classes prefixes.
 * 'wcanvas-boilerplate': This should catch text domains.
 *
 * @package WCB
 */

defined( 'ABSPATH' ) || die();

/**
 * Autoloader PSR-4 for composer dependencies
 */
require_once __DIR__ . '/vendor/autoload.php';

/**
 * Setup Classes
 */
new WCB\Setup\Security();
new WCB\Setup\AssetsDependencies();
new WCB\Setup\ThemeSettings();
new WCB\Setup\DisableComments();
new WCB\Setup\PerformanceSettings();
new WCB\Setup\ImageHandler();

/**
 * Block Classes
 */
new WCB\Block\Blocks();
new WCB\Block\CoreBlocks();

/**
 * Plugins Classes
 */
new WCB\Plugins\AcfSettings();
new WCB\Plugins\GravityFormsSettings();

/**
 * Extra
 */
new WCB\Functionalities\SimplePagination();
new WCB\Functionalities\Archive\ArchiveApi();
