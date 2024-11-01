<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'BESTBUG_UVQ_OPTIONS' ) ) {
	/**
	 * BESTBUG_UVQ_OPTIONS Class
	 *
	 * @since	1.0
	 */
	class BESTBUG_UVQ_OPTIONS {


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
			
			add_filter('bb_register_options', array( $this, 'options'), 10, 1 );

			if(is_admin()) {
				add_action( 'admin_enqueue_scripts', array( $this, 'adminEnqueueScripts' ) );
			}
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueueScripts' ) );
        }

		public function adminEnqueueScripts() {
		
		}

		public function enqueueScripts() {
		
        }
        
        public function options($options) {
			if( empty($options) ) {
				$options = array();
			}
			
			$posttypes = get_post_types( array( 'public' => true ) );
			unset($posttypes['attachment']);
			$args = array(
				'posts_per_page'  => -1,
				'post_type' => BESTBUG_UVQ_POSTTYPE,
				'orderby' => 'title',
				'post_status' => 'publish',
				'order' => 'ASC',
			);
			$query = new WP_Query( $args );
			$headers = array('' => esc_html__('None', 'ultimate-viral-quiz'));
			if($query->post_count > 0) {
				foreach ($query->posts as $key => $post) {
					$headers[ $post->post_name ] = $post->post_title;
				}
			}
			
			$prefix = BESTBUG_UVQ_PREFIX;
			$options[] = array(
				'type' => 'options_fields',
				'menu' => array(
					// add_submenu_page || add_menu_page
					'type' => 'add_submenu_page',
					'parent_slug' => 'uvq-all-quizzes',
					'page_title' => esc_html('Settings', 'ultimate-viral-quiz'),
					'menu_title' => esc_html('Settings', 'ultimate-viral-quiz'),
					'capability' => 'manage_options',
					'menu_slug' => BESTBUG_UVQ_SLUG_SETTINGS,
				),
				'fields' => array(
					array(
						'type'       => 'toggle',
						'heading'    => esc_html__( 'Force share to view result', 'ultimate-viral-quiz' ),
						'param_name' => $prefix . 'force_share',
						'value'      => 'no',
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Create List URL', 'ultimate-viral-quiz' ),
						'param_name' => $prefix . 'create_list_url',
						'value'      => '',
						'description' => esc_html('The link to create List', 'ultimate-viral-quiz'),
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Create Rank URL', 'ultimate-viral-quiz' ),
						'param_name' => $prefix . 'create_rank_url',
						'value'      => '',
						'description' => esc_html('The link to create Rank', 'ultimate-viral-quiz'),
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Create Poll URL', 'ultimate-viral-quiz' ),
						'param_name' => $prefix . 'create_poll_url',
						'value'      => '',
						'description' => esc_html('The link to create Poll', 'ultimate-viral-quiz'),
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Create Trivia Quiz URL', 'ultimate-viral-quiz' ),
						'param_name' => $prefix . 'create_triviaquiz_url',
						'value'      => '',
						'description' => esc_html('The link to create Trivia Quiz', 'ultimate-viral-quiz'),
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Create Personality Quiz URL', 'ultimate-viral-quiz' ),
						'param_name' => $prefix . 'create_personalityquiz_url',
						'value'      => '',
						'description' => esc_html('The link to create Personality Quiz', 'ultimate-viral-quiz'),
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Login URL', 'ultimate-viral-quiz' ),
						'param_name' => $prefix . 'login_url',
						'value'      => '',
						'description' => esc_html('The link to login', 'ultimate-viral-quiz'),
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Signup URL', 'ultimate-viral-quiz' ),
						'param_name' => $prefix . 'signup_url',
						'value'      => '',
						'description' => esc_html('The link to Signup', 'ultimate-viral-quiz'),
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Forgot password URL', 'ultimate-viral-quiz' ),
						'param_name' => $prefix . 'forgot_password_url',
						'value'      => '',
						'description' => esc_html('The link to get password', 'ultimate-viral-quiz'),
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'My Quiz URL', 'ultimate-viral-quiz' ),
						'param_name' => $prefix . 'my_quiz_url',
						'value'      => '',
						'description' => esc_html('The link to quizzes of user', 'ultimate-viral-quiz'),
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Profile URL', 'ultimate-viral-quiz' ),
						'param_name' => $prefix . 'profile_url',
						'value'      => '',
						'description' => esc_html('The link to Profile of user', 'ultimate-viral-quiz'),
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Change password URL', 'ultimate-viral-quiz' ),
						'param_name' => $prefix . 'change_password_url',
						'value'      => '',
						'description' => esc_html('The link to Change password', 'ultimate-viral-quiz'),
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Embed URL', 'ultimate-viral-quiz' ),
						'param_name' => $prefix . 'embed_url',
						'value'      => '',
						'description' => esc_html('The link to Embed Quiz', 'ultimate-viral-quiz'),
					),
				),
			);
			
			return $options;
        }
        
    }
	
	new BESTBUG_UVQ_OPTIONS();
}

