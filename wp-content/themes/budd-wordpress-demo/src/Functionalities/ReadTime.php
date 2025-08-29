<?php
/**
 * Get Read Time
 *
 * @package WCB
 */

namespace WCB\Functionalities;

defined( 'ABSPATH' ) || die();

/**
 * Class ReadTime
 *
 * @package WCB\Functionalities
 */
class ReadTime {

	/**
	 * This function determines minutes read in post
	 *
	 * @param string $words the content of the post.
	 *
	 * @return string
	 */
	public static function count_minutes_read( $words ) {
		$formula = round( str_word_count( wp_strip_all_tags( $words ) ) / 200 );
		return $formula < 1 ? '1' : $formula;
	}
}
