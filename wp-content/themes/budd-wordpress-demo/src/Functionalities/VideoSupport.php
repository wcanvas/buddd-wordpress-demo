<?php
/**
 * VideoSupport class
 *
 * This class allow use videos with embedded fields
 *
 * @package WCB
 */

namespace WCB\Functionalities;

defined( 'ABSPATH' ) || die();

/**
 * VideoSupport class
 */
class VideoSupport {
	/**
	 * Local Service method
	 *
	 * Retrieves video skeleton tags. Works with videos loaded manually in the backend, it doesn't work with any provider
	 * like youtube, or vimeo.
	 *
	 * @param array $args to use.
	 * - field: acf slug of oembed field ('video' by default) [string]
	 * - post_id: The post ID ('null' by default)[integer]
	 * - placeholder: acf slug of image field ('null' by default) [string].
	 * - placeholder_size: The size of the image to display ('large' by default)[string].
	 * - button_content: The content that'll be displayed in the btn tag ('Play >' by default)[string].
	 */
	public static function local_service( $args = array() ) {
		$args += array(
			'field'            => 'video',
			'post_id'          => null,
			'placeholder'      => null,
			'placeholder_size' => 'large',
			'button_content'   => 'Play >',
		);

		if ( get_field( $args['field'] ) ) {
			$video_url = get_field( $args['field'] );
		} else {
			$video_url = get_sub_field( $args['field'] );
		}

		$image_placeholder = '';
		if ( get_field( $args['placeholder'] ) ) {
			$image_placeholder = get_field( $args['placeholder'] )['sizes'][ $args['placeholder_size'] ];
		} elseif ( get_sub_field( $args['placeholder'] ) ) {
			$image_placeholder = get_sub_field( $args['placeholder'] )['sizes'][ $args['placeholder_size'] ];
		}
		$video_id = self::generate_random_string();

		$template = "<div class='wcb-support-video wcb-support-video--local-service js-support-video-local' id='{$video_id}'>
			<img class='wcb-h-full wcb-w-full' src='{$image_placeholder}'/>
			<button class='wcb-support-video__play js-support-video-play wcb-w-16 wcb-h-16 wcb-bg-white wcb-rounded-full wcb-flex wcb-items-center wcb-justify-center wcb-z-10'>{$args['button_content']}</button>
			<video class='wcb-support-video__video wcb-w-full wcb-hidden hover:controls' controls data-src='{$video_url}'></video>
		</div>";

		// $template is built with escaped values
		// @codingStandardsIgnoreStart
		echo $template;
		// @codingStandardsIgnoreEnd
	}

	/**
	 * Stream_service
	 *
	 * Calls secondary methods that retrieves the video skeleton tags.
	 *
	 * @param array $args to use.
	 * $args:
	 * - field: acf slug of oembed field ('video' by default) [string]
	 * - post_id: The post ID ('null' by default)[integer]
	 * - placeholder: acf slug of image field ('null' by default) [string].
	 * - placeholder_size: The size of the image to display ('large' by default)[string].
	 * - button_content: The content that'll be displayed in the btn tag ('Play >' by default)[string].
	 */
	public static function stream_service( $args = array() ) {
		$args += array(
			'field'            => 'video',
			'post_id'          => null,
			'placeholder'      => null,
			'placeholder_size' => 'large',
			'button_content'   => 'Play >',
		);

		if ( get_field( $args['field'], $args['post_id'] ) ) {
			$video_url = get_field( $args['field'], $args['post_id'], false, false );
		} else {
			$video_url = get_sub_field( $args['field'], $args['post_id'], false, false );
		}

		$attr = self::video_attributes( $video_url );

		$image_placeholder = '';
		if ( get_field( $args['placeholder'] ) ) {
			$image_placeholder = get_field( $args['placeholder'] )['sizes'][ $args['placeholder_size'] ];
		} elseif ( get_sub_field( $args['placeholder'] ) ) {
			$image_placeholder = get_sub_field( $args['placeholder'] )['sizes'][ $args['placeholder_size'] ];
		}

		$thumbnail = $image_placeholder ? $image_placeholder : self::video_thumbnail_url( $attr['type'], $attr['id'] );
		// @codingStandardsIgnoreStart
		echo self::video_html_generator( esc_attr( $attr['type'] ), esc_attr( $attr['id']), $thumbnail, $args['button_content'] );
		// @codingStandardsIgnoreEnd
	}
	/**
	 * Video attributes method
	 *
	 * @param string $url the video url.
	 */
	protected static function video_attributes( $url ) {

		// Parse the url.
		$parse = wp_parse_url( $url );
		// Set blank variables.
		$video_type = '';
		$video_id   = '';

		// Url is http://youtu.be/xxxx.
		if ( 'youtu.be' === $parse['host'] ) {

			$video_type = 'youtube';

			$video_id = ltrim( $parse['path'], '/' );
		}

		// Url is http://www.youtube.com/watch?v=xxxx.
		// or http://www.youtube.com/watch?feature=player_embedded&v=xxx.
		// or http://www.youtube.com/embed/xxxx.
		if ( ( 'youtube.com' === $parse['host'] ) || ( 'www.youtube.com' === $parse['host'] ) ) {

			$video_type = 'youtube';

			parse_str( $parse['query'], $output );
			$v = $output['v'];

			$video_id = $v;
			if ( ! empty( $feature ) ) {
				$video_id = end( explode( 'v=', $parse['query'] ) );
			}

			if ( strpos( $parse['path'], 'embed' ) === 1 ) {
				$video_id = end( explode( '/', $parse['path'] ) );
			}
		}

		// Url is http://www.vimeo.com.
		if ( ( 'vimeo.com' === $parse['host'] ) || ( 'www.vimeo.com' === $parse['host'] ) ) {

			$video_type = 'vimeo';

			$video_id = ltrim( $parse['path'], '/' );
		}
		$host_names = explode( '.', $parse['host'] );
		$rebuild    = ( ! empty( $host_names[1] ) ? $host_names[1] : '' ) . '.' . ( ! empty( $host_names[2] ) ? $host_names[2] : '' );
		// Url is an embed url wistia.com.
		if ( ( 'wistia.com' === $rebuild ) || ( 'wi.st.com' === $rebuild ) ) {

			$video_type = 'wistia';

			if ( strpos( $parse['path'], 'medias' ) === 1 ) {
				$video_id = end( explode( '/', $parse['path'] ) );
			}
		}

		// If recognised type return video array.
		if ( ! empty( $video_type ) ) {

			$video_array = array(
				'type' => $video_type,
				'id'   => $video_id,
			);

			return $video_array;
		} else {

			return false;
		}
	}
	/**
	 * Returns thumbnail url for your video
	 *
	 * @param string $service video provider('youtube', 'vimeo', etc).
	 * @param string $id video id.
	 * @return string
	 */
	protected static function video_thumbnail_url( $service, $id ) {
		switch ( $service ) {
			case 'youtube':
				if ( wp_remote_get( "https://img.youtube.com/vi/{$id}/maxresdefault.jpg" ) ) {
					$path = "https://img.youtube.com/vi/{$id}/maxresdefault.jpg";
				} else {
					$path = "https://img.youtube.com/vi/{$id}/0.jpg";
				}
				$thumbnail = $path;
				break;
			case 'vimeo':
				$data      = json_decode( wp_remote_retrieve_body( wp_remote_get( "https://vimeo.com/api/v2/video/{$id}.json" ) ) );
				$thumbnail = $data[0]->thumbnail_large;
				break;
		}

		return $thumbnail;
	}
	/**
	 * Function video_html_generator()
	 *
	 * Creates skeleton tags for youtube videos.
	 *
	 * @param  string      $service provider.
	 * @param  string      $id video id.
	 * @param  htmlElement $thumbnail img tag.
	 * @param htmlElement $btn_content svg tag.
	 * @return htmlElement
	 */
	protected static function video_html_generator( $service, $id, $thumbnail, $btn_content ) {
		$id_dom = 'youtube' !== $service ? 'id="' . self::generate_random_string() . '"' : '';
		// Youtube case.
		if ( 'youtube' === $service ) {
			$youtube_dom_id  = self::generate_random_string();
			$youtube_id_attr = "youtube-dom='{$youtube_dom_id}'";
			$youtube_iframe  = "<div id='{$youtube_dom_id}'></div>";
		}

		$template = "<div class='wcb-support-video js-support-video' service='{$service}' video-id='{$id}' {$id_dom} {$youtube_id_attr}>
           	<img class='wcb-h-full wcb-w-full' src='{$thumbnail}'/>
            <button class='wcb-support-video__play js-support-video-play wcb-w-16 wcb-h-16 wcb-bg-white wcb-rounded-full wcb-flex wcb-items-center wcb-justify-center'>{$btn_content}</button>
            {$youtube_iframe}
        </div>";

		return $template;
	}
	/**
	 * Function generate_random_string
	 *
	 * Returns a random string
	 *
	 * @param int $length index position to cut string.
	 */
	protected static function generate_random_string( $length = 10 ) {
		return substr( str_shuffle( 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' ), 0, $length );
	}
}
