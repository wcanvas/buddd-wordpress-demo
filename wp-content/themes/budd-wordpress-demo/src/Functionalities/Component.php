<?php
/**
 * Component handler Class
 *
 * @package WCB
 */

namespace WCB\Functionalities;

defined( 'ABSPATH' ) || die();

/** Class Component */
class Component {
	/**
	 * Component arguments.
	 *
	 * @var array $args
	 */
	public $args;

	/**
	 * Component filename.
	 *
	 * @var string $name
	 */
	public $name;


	/**
	 * Component constructor.
	 *
	 * @param string $name Component filename.
	 * @param array  $args Component arguments.
	 */
	public function __construct( $name, $args = array() ) {
		$this->name = $name;
		$this->args = $args;
	}

	/**
	 * Render the component.
	 *
	 * @return void
	 */
	public function render() {
		get_template_part( 'components/' . $this->name . '/' . $this->name, $this->name, $this->args );
	}
}
