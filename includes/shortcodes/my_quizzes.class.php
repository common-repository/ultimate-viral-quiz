<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'UVQ_MY_QUIZZES_SHORTCODE' ) ) {
	/**
	 * UVQ_MY_QUIZZES_SHORTCODE Class
	 *
	 * @since	1.0
	 */
	class UVQ_MY_QUIZZES_SHORTCODE {

		/**
		 * Constructor
		 *
		 * @return	void
		 * @since	1.0
		 */
		function __construct() {
			$this->init();
		}

		public function init() {
			
			add_shortcode( 'uvq_my_quizzes', array( $this, 'shortcode' ) );
			if ( defined( 'WPB_VC_VERSION' ) && function_exists( 'vc_add_param' ) ) {
				$this->vc_shortcode();
			}

        }
        
        public function vc_shortcode() {
		
        }
		
		public function shortcode( $atts, $content = null ) {
			if ( !is_user_logged_in() ) {
				return;
			}
			$user = wp_get_current_user();
			if ( get_query_var( 'paged' ) ) { $paged = get_query_var( 'paged' ); }
			elseif ( get_query_var( 'page' ) ) { $paged = get_query_var( 'page' ); }
			else { $paged = 1; }
			$atts = shortcode_atts( array(
				'posts_per_page'   => get_option('posts_per_page'),
				'paged'			   => $paged,
				'offset'           => '',
				'category'         => '',
				'category_name'    => '',
				'orderby'          => 'date',
				'order'            => 'DESC',
				'include'          => '',
				'exclude'          => '',
				'meta_key'         => '',
				'meta_value'       => '',
				'post_type'        => BESTBUG_UVQ_POSTTYPE,
				// 'post_mime_type'   => '',
				// 'post_parent'      => '',
				'author'	   	   => $user->ID,
				// 'author_name'	   => '',
				'post_status'      => 'any',
				// 'suppress_filters' => true,
			), $atts );
			
			$this->quizzes = new WP_Query($atts);
			
			ob_start();
			include 'view/my_quizzes.view.php';
			$content = ob_get_contents(); 
			ob_end_clean();
			return $content;
		}
        
    }
	
	new UVQ_MY_QUIZZES_SHORTCODE();
}

