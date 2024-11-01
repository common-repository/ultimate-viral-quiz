<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'UVQ_ADD_QUIZ_SHORTCODE' ) ) {
	/**
	 * UVQ_ADD_QUIZ_SHORTCODE Class
	 *
	 * @since	1.0
	 */
	class UVQ_ADD_QUIZ_SHORTCODE {

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
			
			add_shortcode( 'uvq_add_quiz', array( $this, 'shortcode' ) );
			if ( defined( 'WPB_VC_VERSION' ) && function_exists( 'vc_add_param' ) ) {
				$this->vc_shortcode();
			}

			if(is_admin()) {
				add_action( 'admin_enqueue_scripts', array( $this, 'adminEnqueueScripts' ) );
			}
			add_action( 'wp_enqueue_scripts', array( $this, 'adminEnqueueScripts' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueueScripts' ) );

        }

		public function adminEnqueueScripts() {
			wp_enqueue_script( 'jquery' );
        	wp_enqueue_script( 'jcrop' );
			
			wp_enqueue_style( 'growl', BESTBUG_CORE_URL . '/assets/admin/css/jquery.growl.css' );
			wp_enqueue_script( 'growl', BESTBUG_CORE_URL . '/assets/admin/js/jquery.growl.js', array( 'jquery' ), BESTBUG_CORE_VERSION, true );
			
			wp_enqueue_style( 'uvq-editor', BESTBUG_UVQ_URL . 'assets/css/editor.css' );
			
			// wp_enqueue_style( 'css', BESTBUG_UVQ_URL . '/assets/css/style.css' );
			wp_enqueue_script( 'angularjs', BESTBUG_UVQ_URL . '/assets/libs/vender/angularjs/angular.min.js', array( 'jquery' ), '1.0', true );
			
			wp_enqueue_script( 'angular-ui-switch', BESTBUG_UVQ_URL . '/assets/libs/vender/angularjs/ui-switch/angular-ui-switch.min.js', array( 'jquery' ), '1.0', true );
			
			wp_enqueue_script( 'angular-animate', BESTBUG_UVQ_URL . '/assets/libs/vender/angular-animate/angular-animate.min.js', array( 'jquery' ), '1.0', true );
			
			wp_enqueue_script( 'angular-file-upload-shim', BESTBUG_UVQ_URL . '/assets/libs/vender/angularjs/angular-file-upload-shim.min.js', array( 'jquery' ), '1.0', true );
			wp_enqueue_script( 'angular-file-upload', BESTBUG_UVQ_URL . '/assets/libs/vender/angularjs/angular-file-upload.min.js', array( 'jquery' ), '1.0', true );
			wp_enqueue_script( 'bootstrap', BESTBUG_UVQ_URL . '/assets/libs/vender/bootstrap.min.js', array( 'jquery' ), '1.0', true );
			wp_enqueue_script( 'fuelux', BESTBUG_UVQ_URL . '/assets/libs/vender/fuelux.min.js', array( 'jquery' ), '1.0', true );
			wp_enqueue_script( 'uvq-editor-js', BESTBUG_UVQ_URL . '/assets/libs/app.js', array( 'jquery' ), '1.0', true );
			
			wp_enqueue_script( 'uvq-editor-list-js', BESTBUG_UVQ_URL . '/assets/libs/listController.js', array( 'jquery' ), '1.0', true );
			wp_enqueue_script( 'uvq-editor-personality-js', BESTBUG_UVQ_URL . '/assets/libs/personalityController.js', array( 'jquery' ), '1.0', true );
			wp_enqueue_script( 'uvq-editor-poll-js', BESTBUG_UVQ_URL . '/assets/libs/pollController.js', array( 'jquery' ), '1.0', true );
			wp_enqueue_script( 'uvq-editor-rank-js', BESTBUG_UVQ_URL . '/assets/libs/rankController.js', array( 'jquery' ), '1.0', true );
			wp_enqueue_script( 'uvq-editor-trivia-js', BESTBUG_UVQ_URL . '/assets/libs/triviaController.js', array( 'jquery' ), '1.0', true );
			
			$ajax = array(
	            'url' => admin_url('admin-ajax.php') ,
	            'buzz_url' => get_home_url() . '?p=',
	            'message' => array(
	                'undefine' => __("Error undefine", 'ultimate-viral-quiz') ,
	                'update_error' => __("Update successful", 'ultimate-viral-quiz') ,
	                'update_success' => __("Update error", 'ultimate-viral-quiz') ,
	                'url_image_invalid' => __("Image Url invalid", 'ultimate-viral-quiz') ,
	                'url_youtube_invalid' => __("Id or Youtube Url invalid", 'ultimate-viral-quiz') ,
	            ) ,
	            'msg' => array(
	                'required' => __("Form invalid", 'ultimate-viral-quiz') ,
	                'invalid_username' => __('Allowed characters are a-z (only lower case), 0-9, _', 'ultimate-viral-quiz') ,
	                'invalid_minlength' => __("Please enter at least {0} characters.", 'ultimate-viral-quiz') ,
	                'invalid_maxlength' => __("Please enter no more than {0} characters.", 'ultimate-viral-quiz') ,
	                'invalid_email' => __("Your email address must be in the format of name@domain.com", 'ultimate-viral-quiz') ,
	                'email_exists' => __("Email already exists", 'ultimate-viral-quiz') ,
	                'username_exists' => __("Username already exists", 'ultimate-viral-quiz') ,
	                'pass_do_not_match' => __("Password and Confirm password must match", 'ultimate-viral-quiz') ,
	                'confirm_delete_buzz' => __("Are you sure want to delete this Buzz?", 'ultimate-viral-quiz') ,
	                'deleting' => __("Deleting...", 'ultimate-viral-quiz') ,
	                'delete_again' => __("Delete again?", 'ultimate-viral-quiz') ,
	                'must_login_to_use' => __("You need login to Vote", 'ultimate-viral-quiz') ,
	            ) ,
	            'url_callback' => '',
	        );
	        $urls = array(
	            'image_url' => get_home_url() . '/uvq_image/', //plugins_url('ultimate_viral_quiz/includes/images.php') ,
	            'cloudinary_url' => '', //'http://res.cloudinary.com/badao-co/image/upload/',
	            'login' => '',
	            'transparent_image' => plugins_url() . 'assets/images/transparent.gif',
	        );
	        $editor = array(
	            'text' => array(
	                'editVideo' => __("Edit video", 'ultimate-viral-quiz') ,
	                'editImage' => __("Edit image", 'ultimate-viral-quiz') ,
	            ) ,
	        );
	        $options = array(
	            // 'UVQ_PAGINATION_LIMIT_AUTOLOAD' => UVQ_PAGINATION_LIMIT_AUTOLOAD,
	            'UVQ_UPLOAD_TYPE' => 'upload-local',
	            'UVQ_UPLOAD_LOCAL' => 'upload-local',
	            // 'UVQ_UPLOAD_CLOUDINARY' => UVQ_UPLOAD_CLOUDINARY,
	            // 'UVQ_FACEBOOKAPP_ID' => UVQ_FACEBOOKAPP_ID,
	            // 'UVQ_LOCALE_CODE' => UVQ_LOCALE_CODE,
	        );
	        $users = array(
	            'is_user_logged_in' => ((is_user_logged_in()) ? true : false) ,
	        );
	        wp_localize_script('uvq-editor-js', 'USERS', $users);
	        wp_localize_script('uvq-editor-js', 'GAMETYPE', array(
				'list' => 'list',
				'personalityquiz' => 'personalityquiz',
				'triviaquiz' => 'triviaquiz',
				'poll' => 'poll',
				'rank' => 'rank',
			));
	        wp_localize_script('uvq-editor-js', 'EDITOR', $editor);
	        wp_localize_script('uvq-editor-js', 'URLS', $urls);
	        wp_localize_script('uvq-editor-js', 'DIMENSION', array(
			    'thumbnail' => array(
			      'width' => 392,
			      'height' => 294
			    ),
			    'results' => array(
			      'width' => 448,
			      'height' => 336
			    ),
			    'answers' => array(
			      'width' => 190,
			      'height' => 178
			    ),
			    'questions' => array(
			      'width' => 448,
			      'height' => 336
			    ),
			    'items' => array(
			      'width' => 448,
			      'height' => 336
			    ),
			    'wide_landscape' => array(
			      'width' => 448,
			      'height' => 235
			    ),
			    'avatar' => array(
			      'width' => 150,
			      'height' => 150
				),
			));
	        wp_localize_script('uvq-editor-js', 'AJAX', $ajax);
	        wp_localize_script('uvq-editor-js', 'OPTIONS', $options);
		}

		public function enqueueScripts() {
			// wp_enqueue_style( 'uvq-editor', BESTBUG_UVQ_URL . 'assets/css/editor.css' );
			// 
			// // wp_enqueue_style( 'css', BESTBUG_UVQ_URL . '/assets/css/style.css' );
			// wp_enqueue_script( 'angularjs', BESTBUG_UVQ_URL . '/assets/libs/vender/angularjs/angularjs.min.js', array( 'jquery' ), '1.0', true );
			// wp_enqueue_script( 'uvq-editor-js', BESTBUG_UVQ_URL . '/assets/libs/app.js', array( 'jquery' ), '1.0', true );
		}
        
        public function vc_shortcode() {
		
        }
		
		public function shortcode( $atts, $content = null ) {
			$atts = shortcode_atts( array(
				'id' => '',
                'type' => 'list',
			), $atts );
			if(isset($atts['id']) && !empty($atts['id'])) {
				$id = $atts['id'];
			}
			if(isset($_GET['idQuiz']) && !empty($_GET['idQuiz'])) {
				$id = intval($_GET['idQuiz']);
			}
            if(isset($atts['type']) && !empty($atts['type'])) {
				$type = $atts['type'];
			} else {
                $type = '';
            }
			
			if ( !is_user_logged_in() )
			{
				$url = get_option(BESTBUG_UVQ_PREFIX . 'login_url');
				if($url == '') {
					$url = home_url();
				}
				$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			?>
			<div class="bbuvq-redirect" data-href="<?php echo esc_url($url) .'?url_callback=' . $actual_link ?>"></div>
			<?php
			    return;
			}
			// GAME tags and categories
			$conditions = array(
			        'hide_empty' => false,
			        'orderby' => 'count',
			        'order' => 'DESC',
			        'number' => 20
			    );
			$gametags = get_terms('uvq_tag', $conditions);

			$tag_filter = array();
			foreach ($gametags as $key => $tag) {
			    array_push($tag_filter, array(
			        'tag_id'    => $tag->term_id,
			        'name'      => $tag->name,
			        'isCheck'   => false, 
			    ));
			}
			// Categories
			$conditions = array(
			        'hide_empty' => false,
			        'orderby' => 'count',
			        'order' => 'DESC',
			    );
			$categories = get_terms('uvq_category', $conditions);

			$uvq_categories = array();

			foreach ($categories as $key => $val) {
			    $tmp = array(
			        'cat_id'    => $val->term_id,
			        'name'      => $val->name,
			        );
			    array_push($uvq_categories, $tmp);
			}
			
			$game = array(
				'tags' => $tag_filter,
				'categories' => $uvq_categories,
			);
			wp_localize_script('uvq-editor-js', 'GAME', $game);
			
			// GAMEINFO
			$GAMEINFO = null;
			if(isset($id) && !empty($id)):
				$quiz = get_post($id);
	            $content = get_post_meta($id, 'uvq_quiz_content', true);
	            $thumb = get_post_meta($id, 'uvq_quiz_thumb', true);
	            
	            $gametags = wp_get_post_terms($id, 'uvq_tag');
	            $gamecategory = wp_get_post_terms($id, 'uvq_category');
	            $tag_filter = array();
	            foreach ($gametags as $key => $tag) {
	                array_push($tag_filter, array(
	                    'tag_id'    => $tag->term_id,
	                    'name'      => $tag->name,
	                ));
	            }
				$GAMEINFO = array(
					'gameID' => ($quiz->ID),
				    'thumbImage' => ($thumb),
				    'gameTitle' => ($quiz->post_title),
				    'gameDesc' => ($quiz->post_content),
					'permission' => (get_post_meta($id, 'uvq_quiz_permission', true)),
				    'tags' => ( $tag_filter),
				    'category' => (isset($gamecategory[0])) ? $gamecategory[0]->term_id : 0,
				);
				if($type == 'list') {
					$GAMEINFO['items'] = ($content->items)?($content->items):json_decode('[{title:"", image:"", data:"", caption:"" }]');
		            $GAMEINFO['footerText'] = isset($content->footerText)?($content->footerText):'';
				} elseif($type == 'poll') {
					$GAMEINFO['questions'] =  ($content->questions)?($content->questions):json_decode('[{useImage:!1,totalVoted:0,caption:"",image:"",data:"",answers:[{image:"",data:"",text:"",totalVoted:0,voted:[],},{image:"",data:"",text:"",totalVoted:0,voted:[],}]}]');
				} elseif($type == 'rank') {
					$GAMEINFO['items'] = (isset($content->items) && $content->items)?($content->items):json_decode('[{title:"", image:"", data:"", caption:"", vote_up:0, vote_down:0 }]');
				} elseif($type == 'triviaquiz') {
					$GAMEINFO['questions'] =  ($content->questions)?($content->questions):json_decode('[{useImage:!1,caption:"",image:"",data:"",result:{caption:"",image:"",data:"",},answers:[{image:"",data:"",text:"",correct:"",},{image:"",data:"",text:"",correct:"",}]}]');
					$GAMEINFO['results'] =  ($content->results)?($content->results):json_decode('[{title:"",image:"",data:"",range:{begin:"",end:""}}]');
				} elseif($type == 'personalityquiz') {
					$GAMEINFO['questions'] =  ($content->questions)?($content->questions):json_decode('[{"useImage":!1,"caption":"","image":"","data":"","answers":[{"image":"","data":"","text":"","associate":[{"point":0},{"point":0}],},{"image":"","data":"","text":"","associate":[{"point":0},{"point":0}],}]}]');
					$GAMEINFO['results'] =  ($content->results)?($content->results):json_decode('[{"title":"","image":"","data":"","text":""},{"title":"","image":"","data":"","text":""}]');
				} 
			endif;
			wp_localize_script('uvq-editor-js', 'GAMEINFO', $GAMEINFO);
			// Output shortcode
            ob_start();
			include 'view/add_'.$type.'.view.php';
			$content = ob_get_contents(); 
			ob_end_clean();
			return $content;
		}
        
    }
	
	new UVQ_ADD_QUIZ_SHORTCODE();
}

