<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'UVQ_USER_LOGIN_SHORTCODE' ) ) {
	/**
	 * UVQ_USER_LOGIN_SHORTCODE Class
	 *
	 * @since	1.0
	 */
	class UVQ_USER_LOGIN_SHORTCODE {

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
			
			add_shortcode( 'uvq_user_login', array( $this, 'shortcode' ) );
			if ( defined( 'WPB_VC_VERSION' ) && function_exists( 'vc_add_param' ) ) {
				$this->vc_shortcode();
			}

        }
        
        public function vc_shortcode() {
		
        }
		
		public function shortcode( $atts, $content = null ) {
			wp_localize_script('uvq-editor-js', 'USER_INFOMARION', array('no' => '1'));
			ob_start();
			include 'view/user_login.view.php';
			$content = ob_get_contents(); 
			ob_end_clean();
			return $content;
		}
        
    }
	
	new UVQ_USER_LOGIN_SHORTCODE();
}

