<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'UVQ_USER_PROFILE_SHORTCODE' ) ) {
	/**
	 * UVQ_USER_PROFILE_SHORTCODE Class
	 *
	 * @since	1.0
	 */
	class UVQ_USER_PROFILE_SHORTCODE {

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
			
			add_shortcode( 'uvq_user_profile', array( $this, 'shortcode' ) );
			if ( defined( 'WPB_VC_VERSION' ) && function_exists( 'vc_add_param' ) ) {
				$this->vc_shortcode();
			}

        }
        
        public function vc_shortcode() {
		
        }
		
		public function shortcode( $atts, $content = null ) {
			$userinfor = array();
			$user = wp_get_current_user();

		    $avatar = get_user_meta($user->ID, 'uvq_user_avatar', true);
		    $avatar = (isset($avatar) && !empty($avatar)) ? $avatar : array('data' => 'default','image' => BESTBUG_UVQ_URL . '/assets/images/default_avatar.gif');

		    $social = get_user_meta($user->ID, 'uvq_user_social');

		    $facebook = isset($social[0]->facebook) ? $social[0]->facebook : '';
		    $twitter = isset($social[0]->twitter) ? $social[0]->twitter : '';
		    $google = isset($social[0]->google) ? $social[0]->google : '';

		    $able_edit_username = true;
		    $able_edit_email = true ;
			
			$userinfor = array(
				'avatar' => $avatar,
		        'fullname' => $user->nickname,
		        'username' => $user->user_login,
		        'email' => $user->user_email,
		        'aboutme' => $user->description,
		        'website' => $user->user_url,
		        'facebook' => $facebook,
		        'google' => $google,
		        'twitter' => $twitter,
			);
			
			wp_localize_script('ultimate-viral-quiz', 'USER_INFOMARION', $userinfor);
			ob_start();
			include 'view/user_profile.view.php';
			$content = ob_get_contents(); 
			ob_end_clean();
			return $content;
		}
        
    }
	
	new UVQ_USER_PROFILE_SHORTCODE();
}

