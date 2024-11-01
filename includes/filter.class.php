<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'BESTBUG_UVQ_FILTER' ) ) {
	/**
	 * BESTBUG_UVQ_FILTER Class
	 *
	 * @since	1.0
	 */
	class BESTBUG_UVQ_FILTER {


		/**
		 * Constructor
		 *
		 * @return	void
		 * @since	1.0
		 */
		function __construct() {
			add_filter( 'the_content', array( $this, 'content_filter' ) );
			add_action('init', array($this, 'image'));
		}

		public function init() {

        }
        
		public function content_filter($content) {
			if (is_singular(BESTBUG_UVQ_POSTTYPE) && $GLOBALS['post']->post_type == BESTBUG_UVQ_POSTTYPE) {
				return do_shortcode('['.BESTBUG_UVQ_SHORTCODE.' id="'.$GLOBALS['post']->ID.'"]');
			}
			return $content;
		}

		public function	image() {
			$url_path = trim(parse_url(add_query_arg(array()), PHP_URL_PATH), '/');
			if ($url_path === 'uvq_image') {
     			// load the file if exists
				include 'images.php';
				exit();
			}
		}
    }
	
	new BESTBUG_UVQ_FILTER();
}

