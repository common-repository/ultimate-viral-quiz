<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'BESTBUG_UVQ_QUIZZES' ) ) {
	/**
	 * BESTBUG_UVQ_QUIZZES Class
	 *
	 * @since	1.0
	 */
	class BESTBUG_UVQ_QUIZZES {
		
		public $page_title;
		public $shortcodes;

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
			
			add_action( 'admin_menu', array( $this, 'all_quizs' ) );
			
			if(is_admin()) {
				add_action( 'admin_enqueue_scripts', array( $this, 'adminEnqueueScripts' ) );
			}
			//if(is_admin() && isset($_GET['page']) && substr($_GET['page'], 7) == 'uvq-add') {
				add_action( 'admin_enqueue_scripts', array( $this, 'adminEnqueueScriptsAddQuiz' ) );
			//}
			
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueueScripts' ) );
			add_action( 'wp_ajax_rc_delete_shortcode', array($this, 'delete') );
			add_filter( 'script_loader_tag', array($this, 'modify_jsx_tag'), 10, 3 );
			
			add_action( 'wp_ajax_update_quiz', array( $this, 'update_quiz' ) );
			add_action( 'wp_ajax_uvq_delete_quiz', array( $this, 'delete' ) );
			add_action( 'wp_ajax_uvq_duplicate_quiz', array( $this, 'duplicate' ) );
        }
		
		public function modify_jsx_tag( $tag, $handle, $src ) {
			// Check that this is output of JSX file
			if ( 'bb-quiz-editor' == $handle ) {
				$tag = str_replace( "<script type='text/javascript'", "<script type='text/babel'", $tag );
				$tag = str_replace( "<script src=", "<script type='text/babel' src=", $tag );
			}

			return $tag;
		}
		
		public function adminEnqueueScripts()
		{
			// wp_enqueue_script( 'sweetalert', BESTBUG_CORE_URL . '/assets/admin/js/sweetalert.min.js', array( 'jquery' ), null, true );
			// wp_enqueue_script( 'bb-sm', BESTBUG_UVQ_URL . '/assets/admin/js/admin.js', array( 'jquery' ), '1.0', true );
		}

		public function adminEnqueueScriptsAddQuiz() {
			// wp_enqueue_style( 'wp-color-picker');
			// wp_enqueue_script( 'wp-color-picker');

			wp_enqueue_style( 'uvq-editor', BESTBUG_UVQ_URL . 'assets/css/editor.css' );

			// wp_enqueue_script( 'TweenMax', BESTBUG_UVQ_URL . 'assets/libs/TweenMax/TweenMax.min.js', array( 'jquery' ), '1.15.1', true );

			// wp_enqueue_script( 'bb-quiz-editor', BESTBUG_UVQ_URL . '/assets/admin/js/bb-quiz-editor.js', array( 'react' ), null, true );

			// $settings_edit = array();
			// if(isset($_GET['idQuiz']) && !empty($_GET['idQuiz']) && is_numeric($_GET['idQuiz'])) {
			// 	$quiz_settings = get_post($_GET['idQuiz']);
			// 	$settings_edit = (array)json_decode(base64_decode($quiz_settings->post_content));
			// 	$settings_edit['quiz_id'] = $quiz_settings->ID;
			// }
			// wp_localize_script( 'bb-quiz-editor', 'BB_QUIZ_EDIT_SETTINGS', $settings_edit );

		}

		public function enqueueScripts() {
		
		}
		
		function update_quiz(){

			if( isset( $_POST['data'] ) && !empty( $_POST['data'] ) ) {
				$settings = sanitize_text_or_array_field($_POST['data']);

				foreach ($settings['init'] as $key => $init) {
					if($init == '') {
						unset($settings['init'][$key]);
					}
				}
				foreach ($settings['scroll'] as $key => $scroll) {
					if($scroll == '') {
						unset($settings['scroll'][$key]);
					}
				}
				
				$name = sanitize_title( esc_html($settings['general']['name']) );
				if(empty($name)) {
					$name = sanitize_title( esc_html($settings['general']['title']) );
				}
				$settings['general']['name'] = $name;

				$quiz = array(
					'post_title' => esc_html($settings['general']['title']),
					'post_content' => base64_encode(json_encode($settings)),
					'post_type' => BESTBUG_UVQ_POSTTYPE,
					'post_status' => 'publish',
					'post_name' => $name
				);

				if(isset($settings['quiz_id']) && !empty($settings['quiz_id'])) {
					$quiz['ID'] = esc_html($settings['quiz_id']);
				}
				
				$error = true;

				$quiz_ID = wp_insert_post( $quiz, $error );

				if( !$quiz_ID ) {
					echo json_encode(array(
						'msg' => esc_html__('Failed'),
						'status' => 'error',
					));
				} else {
					$post = get_post($quiz_ID);
					
					echo json_encode(array(
						'msg' => esc_html__('Saved'),
						'status' => 'ok',
						'name' => $post->post_name,
						'quiz_id' => $quiz_ID,
					));
					
				}
			}

			wp_die();

		}
	
		public function delete(){
			if(isset($_POST['id'])) {
				$del = wp_delete_post( $_POST['id'], true );
				if($del) {
					echo json_encode(array(
						'status' => 'notice',
						'title' => esc_html('Deleted', 'ultimate-viral-quiz'),
						'message' => esc_html('Quiz is deleted!', 'ultimate-viral-quiz'),
					));
					exit;
				}
			}
			echo json_encode(array(
				'status' => 'error',
				'title' => esc_html('Error', 'ultimate-viral-quiz'),
				'message' => esc_html('Can not delete!', 'ultimate-viral-quiz'),
			));
			exit;
		}
		
		public function duplicate(){
			if(!isset($_POST['id']) || empty($_POST['id'])) {
				return;
			}
			$post = get_post($_POST['id']);

			if($post) {
				
				$quiz = array(
					'post_title' => esc_html($post->post_title) . ' - copy',
					'post_content' => $post->post_content,
					'post_type' => BESTBUG_UVQ_POSTTYPE,
					'post_status' => 'publish',
				);
				
				$quiz_ID = wp_insert_post( $quiz );
				
				if( !$quiz_ID ) {
					echo json_encode(array(
						'status' => 'error',
						'title' => esc_html('Error', 'ultimate-viral-quiz'),
						'message' => esc_html('Can not copy!', 'ultimate-viral-quiz'),
					));
					exit;
				} else {
					
					BESTBUG_HELPER::update_meta($quiz_ID, 'uvq_quiz_content', get_post_meta($_POST['id'], 'uvq_quiz_content', true));
					BESTBUG_HELPER::update_meta($quiz_ID, 'uvq_quiz_permission', get_post_meta($_POST['id'], 'uvq_quiz_permission', true));
					BESTBUG_HELPER::update_meta($quiz_ID, 'uvq_quiz_thumb', get_post_meta($_POST['id'], 'uvq_quiz_thumb', true));
					BESTBUG_HELPER::update_meta($quiz_ID, 'uvq_type', get_post_meta($_POST['id'], 'uvq_type', true));
					
					$title = array(
						'personalityquiz' => esc_html__( 'Personality Quiz','ultimate-viral-quiz'),
						'triviaquiz' => esc_html__( 'Trivia Quiz','ultimate-viral-quiz'),
						'poll' => esc_html__( 'Poll','ultimate-viral-quiz'),
						'list' => esc_html__( 'List','ultimate-viral-quiz'),
						'rank' => esc_html__( 'Rank','ultimate-viral-quiz'),
					);
					$label = get_post_meta($quiz_ID, 'uvq_type', true);
					if(isset($title[$label])) {
						$label = $title[$label];
					}
					
					echo json_encode(array(
						'status' => 'notice',
						'title' => esc_html('Copied', 'ultimate-viral-quiz'),
						'message' => esc_html('Quiz is copied!', 'ultimate-viral-quiz'),
						'row' => array(
							'id' => $quiz_ID,
							'title' => $quiz['post_title'],
							'type' => $label,
						),
					));
					exit;
				}
				
			} 
			echo json_encode(array(
				'status' => 'error',
				'title' => esc_html('Error', 'ultimate-viral-quiz'),
				'message' => esc_html('Can not copy!', 'ultimate-viral-quiz'),
			));
			exit;
			
		}
		
		public function all_quizs(){
			$menu = array(
				'page_title' => esc_html('All Quizzes', 'ultimate-viral-quiz'),
				'menu_title' => esc_html('Viral Quiz', 'ultimate-viral-quiz'),
				'capability' => 'manage_options',
				'menu_slug' => BESTBUG_UVQ_SLUG_ALL_QUIZZES,
				'icon' => BESTBUG_UVQ_URL . '/assets/admin/images/personality.svg',
				'position' =>  76,
			);
			$this->page_title = $menu['page_title'];
			add_menu_page($menu['page_title'],
						$menu['menu_title'],
						$menu['capability'],
						$menu['menu_slug'],
						array(&$this, 'view'),
						$menu['icon'],
						$menu['position']
					);
			add_submenu_page(
				BESTBUG_UVQ_SLUG_ALL_QUIZZES,
				esc_html__('All Quizzes' , 'ultimate-viral-quiz'),
				esc_html__('All Quizzes' , 'ultimate-viral-quiz'),
				$menu['capability'],
				$menu['menu_slug'],
				array(&$this, 'view')
		    );
			add_submenu_page(
				BESTBUG_UVQ_SLUG_ALL_QUIZZES,
				esc_html('Add PersonalityQuiz', 'ultimate-viral-quiz'),
				esc_html('Add PersonalityQuiz', 'ultimate-viral-quiz'),
				'manage_options',
				'uvq-add-personalityquiz',
				array(&$this, 'add_quizs' )
			);
			add_submenu_page(
				BESTBUG_UVQ_SLUG_ALL_QUIZZES,
				esc_html('Add TriviaQuiz', 'ultimate-viral-quiz'),
				esc_html('Add TriviaQuiz', 'ultimate-viral-quiz'),
				'manage_options',
				'uvq-add-triviaquiz',
				array(&$this, 'add_quizs' )
			);
			add_submenu_page(
				BESTBUG_UVQ_SLUG_ALL_QUIZZES,
				esc_html('Add Poll', 'ultimate-viral-quiz'),
				esc_html('Add Poll', 'ultimate-viral-quiz'),
				'manage_options',
				'uvq-add-poll',
				array(&$this, 'add_quizs' )
			);
			add_submenu_page(
				BESTBUG_UVQ_SLUG_ALL_QUIZZES,
				esc_html('Add List', 'ultimate-viral-quiz'),
				esc_html('Add List', 'ultimate-viral-quiz'),
				'manage_options',
				'uvq-add-list',
				array(&$this, 'add_quizs' )
			);
			add_submenu_page(
				BESTBUG_UVQ_SLUG_ALL_QUIZZES,
				esc_html('Add Rank', 'ultimate-viral-quiz'),
				esc_html('Add Rank', 'ultimate-viral-quiz'),
				'manage_options',
				'uvq-add-rank',
				array(&$this, 'add_quizs' )
			);
		}
		public function tax() {
			
		}
		public function view() {
			
			$this->quizzes = get_posts(array(
				'numberposts' => -1,
				'post_type' => BESTBUG_UVQ_POSTTYPE,
			));
			
			BESTBUG_HELPER::begin_wrap_html($this->page_title);
			include 'templates/quizzes.view.php';
			BESTBUG_HELPER::end_wrap_html();
		}
		
        public function add_quizs() {
			$id = '';
			if(isset($_GET['idQuiz']) && !empty($_GET['idQuiz'])) {
				$id = esc_html($_GET['idQuiz']);
			}
			switch ($_GET['page']) {
				case 'uvq-add-personalityquiz':
					echo do_shortcode('[uvq_add_quiz type="personalityquiz" id="'.$id.'"]');
					break;
				case 'uvq-add-triviaquiz':
					echo do_shortcode('[uvq_add_quiz type="triviaquiz" id="'.$id.'"]');
					break;
				case 'uvq-add-poll':
					echo do_shortcode('[uvq_add_quiz type="poll" id="'.$id.'"]');
					break;
				case 'uvq-add-list':
					echo do_shortcode('[uvq_add_quiz type="list" id="'.$id.'"]');
					break;
				case 'uvq-add-rank':
					echo do_shortcode('[uvq_add_quiz type="rank" id="'.$id.'"]');
					break;
			}
			
        }
		
		public function subform(){
			?>
			<div class="bb-row">
			    <div class="bb-col">
					<a href="<?php echo admin_url( 'edit-tags.php?taxonomy=uvq_category&post_type=uvq_quiz' ) ?>" class="button success"><span class="dashicons dashicons-plus-alt"></span><?php esc_html_e('Add Category', 'ultimate-viral-quiz') ?></a>
					
					<a href="<?php echo admin_url( 'edit-tags.php?taxonomy=uvq_tag&post_type=uvq_quiz' ) ?>" class="button success"><span class="dashicons dashicons-plus-alt"></span><?php esc_html_e('Add Tags', 'ultimate-viral-quiz') ?></a>
			    </div>
			</div>
			<?php
		}
        
    }
	
	new BESTBUG_UVQ_QUIZZES();
}

