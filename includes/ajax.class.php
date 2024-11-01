<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'BESTBUG_UVQ_AJAX' ) ) {
	/**
	 * BESTBUG_UVQ_AJAX Class
	 *
	 * @since	1.0
	 */
	class BESTBUG_UVQ_AJAX {


		/**
		 * Constructor
		 *
		 * @return	void
		 * @since	1.0
		 */
		function __construct() {
			add_action('init', array(&$this, 'init'));
		}

		public function init() {
            add_action('wp_ajax_username_exist', array(&$this, 'username_exist'));
            add_action('wp_ajax_nopriv_username_exist', array(&$this, 'username_exist'));
            add_action('wp_ajax_email_exist', array(&$this, 'email_exist'));
            add_action('wp_ajax_nopriv_email_exist', array(&$this, 'email_exist'));
            
            add_action('wp_ajax_lost_password', array(&$this, 'lost_password'));
            add_action('wp_ajax_nopriv_lost_password', array(&$this, 'lost_password'));
            add_action('wp_ajax_nopriv_user_login', array(&$this, 'user_login'));
            add_action('wp_ajax_nopriv_user_signup', array(&$this, 'user_signup'));
            add_action('wp_ajax_change_password', array(&$this, 'change_password'));
            add_action('wp_ajax_save_user_information', array(&$this, 'save_user_information'));

            add_action('wp_ajax_trivia_result', array(&$this, 'trivia_result'));
            add_action('wp_ajax_nopriv_trivia_result', array(&$this, 'trivia_result'));
            
            add_action('wp_ajax_poll_vote', array(&$this, 'poll_vote'));
            add_action('wp_ajax_rank_vote', array(&$this, 'rank_vote'));
			
            add_action('wp_ajax_calc_result', array(&$this, 'calc_result'));
            add_action('wp_ajax_nopriv_calc_result', array(&$this, 'calc_result'));
            
            add_action('wp_ajax_save_game', array(&$this, 'save_game'));
            
            add_action('wp_ajax_set_type_buzz', array(&$this, 'set_type_buzz'));
            
            add_action('wp_ajax_upload_images', array(&$this, 'upload_images'));
			
			add_action('wp_ajax_fix_images', array(&$this, 'fix_images'));
        }
		
		function fix_images(){
			$quizzes = get_posts(array(
				'numberposts' => -1,
				'post_type' => BESTBUG_UVQ_POSTTYPE,
			));
			foreach ($quizzes as $id => $quiz) {
				$thumb = (get_post_meta($quiz->ID, 'uvq_quiz_thumb', true));
				$type = (get_post_meta($quiz->ID, 'uvq_type', true));
				$content = get_post_meta($quiz->ID, 'uvq_quiz_content', true);
				
				if(isset($thumb->public_id)) {
					$thumb->public_id = base64_encode($thumb->url);
					$cropped_url = explode('&id=', $thumb->cropped_url);
					$thumb->cropped_url = $cropped_url[0].'&id='.$thumb->public_id;
					update_post_meta($quiz->ID, 'uvq_quiz_thumb', $thumb);
					echo 'fix: ' . $thumb->url . '<br>';
				}
				if($type == 'list' || $type == 'rank' && isset($content->items)) {
					foreach ($content->items as $key => $item) {
						if(isset($item->data)) {
							$thumb = $item->data;
							$thumb->public_id = base64_encode($thumb->url);
							$cropped_url = explode('&id=', $thumb->cropped_url);
							$thumb->cropped_url = $cropped_url[0].'&id='.$thumb->public_id;
							echo 'fix: ' . $thumb->url . '<br>';
							
							$content->items[$key]->data = $thumb;
						}
					}
				} else if( ($type == 'poll' || $type == 'triviaquiz' || $type == 'personalityquiz')  && isset($content->questions)) {
					foreach ($content->questions as $key => $question) {
						if(isset($question->data)) {
							$thumb = $question->data;
							$thumb->public_id = base64_encode($thumb->url);
							$cropped_url = explode('&id=', $thumb->cropped_url);
							$thumb->cropped_url = $cropped_url[0].'&id='.$thumb->public_id;
							echo 'fix: ' . $thumb->url . '<br>';
							
							$content->questions[$key]->data = $thumb;
							
							foreach ($content->questions[$key]->answers as $k => $answer) {
								if(isset($answer->data)) {
									$thumb = $answer->data;
									$thumb->public_id = base64_encode($thumb->url);
									$cropped_url = explode('&id=', $thumb->cropped_url);
									$thumb->cropped_url = $cropped_url[0].'&id='.$thumb->public_id;
									echo 'fix: ' . $thumb->url . '<br>';
									
									$content->questions[$key]->answers[$k]->data = $thumb;
									
								}
							}
						}
					}
				}
				update_post_meta($quiz->ID, 'uvq_quiz_content', $content);
			}
			exit;
		}
        
        function email_exist()
    	{
    		if (isset($_POST['current_email']) && isset($_POST['email']) && $_POST['current_email'] == $_POST['email'] && $_POST['email'] != '') {
    			echo 'true';
    		} else if (!isset($_POST['email']) || !$_POST['email']) {
    			echo 'false';
    		} else if (email_exists($_POST['email'])) {
    			echo 'false';
    		} else {
    			echo 'true';
    		}
    		exit;
    	}
        
        function username_exist()
    	{
    		if (isset($_POST['current_username']) && isset($_POST['username']) && $_POST['current_username'] == $_POST['username'] && $_POST['username'] != '') {
    			echo 'true';
    		} else if (!isset($_POST['username']) || !$_POST['username']) {
    			echo 'false';
    		} else if (username_exists($_POST['username'])) {
    			echo 'false';
    		} else {
    			echo 'true';
    		}
    		exit;
    	}
        
        function upload_images()
    	{
    		$message_dimension_conditions = esc_html__("Image minsize is : ", 'ultimate-viral-quiz');
    		$dimension_conditions = array(
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
									);
    		$data = json_decode(file_get_contents('php://input'));
    		if (isset($_FILES['file']) || (isset($data->imgurl) && $data->imgurl) && ((isset($_POST['type']) && array_key_exists($_POST['type'], $dimension_conditions)) || (isset($data->type) && array_key_exists($data->type, $dimension_conditions)))) {
    			$type = ($_POST['type']) ? $_POST['type'] : $data->type;
    			$tmp_name = ($data->imgurl) ? $data->imgurl : $_FILES['file']['tmp_name'];
    			list($width, $height, $typeimg, $attr) = getimagesize($tmp_name);
    			if ($width >= $dimension_conditions[$type]['width'] && $height >= $dimension_conditions[$type]['height']) {
    				if (true) { // 'upload-local'
    					$movefile = null;
    					if ($_FILES['file']) {
    						$movefile = wp_handle_upload($_FILES['file'], array(
    							'test_form' => false
    						) , date("Y") . '/' . date("m"));
    					}
    					else {
    						$timeout_seconds = 60;
    						$temp_file = download_url($data->imgurl, $timeout_seconds);
    						if (!is_wp_error($temp_file)) {
    							$file = array(
    								'name' => basename($data->imgurl) ,
    								'type' => wp_check_filetype($data->imgurl) ['type'],
    								'tmp_name' => $temp_file,
    								'error' => 0,
    								'size' => filesize($temp_file) ,
    							);
    							$overrides = array(
    								'test_form' => false,
    								'test_size' => true,
    								'test_upload' => true,
    							);
    							$movefile = wp_handle_sideload($file, $overrides);
    						}
    					}
						
    					if ($movefile && !isset($movefile['error'])) {
    						$result = array(
    							'status' => 'ok',
    							'width' => $width,
    							'height' => $height,
    							// 'path' 	 => $movefile['file'],
    							'public_id' => base64_encode($movefile['url']),
    							'url' => $movefile['url'],
    							'type' => $movefile['type'],
    							'upload_type' => 'upload-local'
    						);
    					}
    					else {
    						$result = array(
    							'status' => 'error',
    							'message' => esc_html__("Can't upload", 'ultimate-viral-quiz') ,
								'detail' => $movefile,
    						);
    					}

    					echo json_encode($result);
    					exit;
    				}
    				else {
    					do_action('_d_owl_upload_image_hook', $tmp_name);
    				}
    			}
    			else {
    				$result['status'] = 'error';
    				$result['message'] = $message_dimension_conditions . $dimension_conditions[$type]['width'] . "x" . $dimension_conditions[$type]['height'];
    			}

    			echo json_encode($result);
    		}

    		exit;
    	}
        
        function save_user_information()
    	{
    		$change_username = 'false';
    		$user = wp_get_current_user();
    		$data = json_decode(file_get_contents('php://input'));
    		$userdata = array(
    			'ID' => $user->ID,
    			'user_url' => $data->website,
    			'nickname' => $data->fullname,
    			'description' => $data->aboutme,
    		);
    		$count_edit = get_user_meta($user->ID, UVQ_USER_COUNT_EDIT, true);
    		if (!$count_edit) {
    			$count_edit = new stdClass;
    			$count_edit->username = 0;
    			$count_edit->email = 0;
    			update_user_meta($user->ID, UVQ_USER_COUNT_EDIT, $count_edit);
    		}

    		$able_edit_username = true;
    		$able_edit_email = true;
    		if ($able_edit_username && $user->user_login != $data->username) {

    			// $userdata['user_login'] = $data->username;

    			global $wpdb;
    			$users_table = $wpdb->prefix . "users";
    			if ($wpdb->update($users_table, array(
    				'user_login' => $data->username
    			) , array(
    				'ID' => $user->ID
    			))) {

    				// _d_set_logged_in($user->ID, $user->user_email);

    				$count_edit->username+= 1;
    				$change_username = 'true';
    			}
    		}

    		if ($able_edit_email && $user->user_email != $data->email) {
    			$userdata['user_email'] = $data->email;
    			$count_edit->email+= 1;
    		}

    		update_user_meta($user->ID, UVQ_USER_COUNT_EDIT, $count_edit);
    		$social = new stdClass;
    		$social->facebook = $data->facebook;
    		$social->twitter = $data->twitter;
    		$social->google = $data->google;
    		update_user_meta($user->ID, UVQ_USER_SOCIAL, $social);
    		update_user_meta($user->ID, UVQ_USER_AVATAR, $data->avatar);
    		if (is_wp_error(wp_update_user($userdata))) {
    			echo json_encode(array(
    				'status' => 'error',
    				'msg' => esc_html__("Can't update", 'ultimate-viral-quiz')
    			));
    		}
    		else {
    			echo json_encode(array(
    				'status' => 'ok',
    				'change_username' => $change_username,
    				'msg' => esc_html__("Update successful", 'ultimate-viral-quiz')
    			));
    		}

    		exit;
    	}
        
        function set_type_buzz()
    	{
    		$current_user = wp_get_current_user();
    		$data = json_decode(file_get_contents('php://input'));
    		$permission = false;
    		if (current_user_can(UVQ_CAN_EDIT_ALLGAME_CAPABILITY)) {
    			$permission = true;
    		}
    		if (current_user_can(UVQ_CAN_EDIT_GAME_CAPABILITY)) {
    			$current_post = get_post($data->gameID);
    			if ($current_user->ID == $current_post->post_author) {
    				$permission = true;
    			}
    		}

    		if (!$permission) {
    			exit;
    		}
    		$game = array(
    			'ID' => $data->gameID,
    			'tax_input' => array(
    				UVQ_TAXONOMY_GAMETYPE => array(
    					intval($data->gameType)
    				) ,
    			) ,
    		);
    		$post_id = wp_update_post($game, $wp_error);
    		if ($post_id) {
    			echo json_encode(array(
    				'status' => 'ok',
    				'msg' => esc_html__("Update successful", 'ultimate-viral-quiz')
    			));
    			exit;
    		}

    		echo json_encode(array(
    			'status' => 'error',
    			'msg' => esc_html__("Can't update", 'ultimate-viral-quiz')
    		));
    		exit;
    	}

        
        function save_game()
    	{
    		$current_user = wp_get_current_user();
    		// if (!_d_allow_createbuzz_today($current_user->ID)):
    		// 	echo json_encode(array(
    		// 		'status' => 'error',
    		// 		'message' => sprintf(__("Maximum posts per day is <b> %s </b>", 'ultimate-viral-quiz') , 3)
    		// 	));
    		// 	exit;
    		// endif;
    		$update = false;
    		$data = json_decode(file_get_contents('php://input'));
    		$game = array(
    			'post_type' => BESTBUG_UVQ_POSTTYPE,
    			'post_title' => wp_filter_nohtml_kses($data->gameTitle) ,
    			'post_content' => wp_filter_nohtml_kses($data->gameDesc) ,
				'post_name' => sanitize_title($data->gameTitle),
    			'post_author' => $current_user->ID,
    			'comment_status' => get_option('default_comment_status', 'closed'),
    			'tax_input' => array(
    				'uvq_tag' => $data->tags,
    				'uvq_category' => intval($data->category),
    			) ,
				'post_status' => 'publish'
    		);
    		if (isset($data->gameID) && $data->gameID) {
    			$game['ID'] = $data->gameID;
    			$update = true;
    		}
			
			if($data->permission == 2 ) {
				$game['post_status'] = 'private';
			}
			if($data->post_status == 'draft' ) {
				$game['post_status'] = 'draft';
			}

    		$post_id = wp_insert_post($game, $wp_error);
			
    		if ($post_id) {
				BESTBUG_HELPER::update_meta($post_id, 'uvq_quiz_content', $data->content);
				BESTBUG_HELPER::update_meta($post_id, 'uvq_quiz_permission', $data->permission);
				BESTBUG_HELPER::update_meta($post_id, 'uvq_quiz_thumb', $data->thumbImage);
				BESTBUG_HELPER::update_meta($post_id, 'uvq_type', $data->gameType);

    			$result = array(
    				'status' => 'notice',
    				'data' => $data,
    				'id' => $data->gameID,
    				'gameID' => $post_id,
					'message' => '',
    			);
    			if ($update) {
    				$result['title'] = sprintf(__("Updated Quiz", 'ultimate-viral-quiz') , $date);
    			}
    			else {
    				$result['title'] = esc_html__("Created Quiz", 'ultimate-viral-quiz');
    			}
    			echo json_encode($result);
    			exit;	
    		}

    		if ($update) {
				echo json_encode(array(
					'status' => 'error',
					'title' => esc_html("Can't Update Quiz", 'ultimate-viral-quiz'),
					'message' => '',
				));
			} else {
				echo json_encode(array(
					'status' => 'error',
					'title' => esc_html("Can't Create Quiz", 'ultimate-viral-quiz'),
					'message' => '',
				));
    		}

    		exit;
    	}
        
        function trivia_result()
    	{
    		$data = json_decode(file_get_contents('php://input'));
    		if (!$data) exit;
    		$gameID = $data->gameID;
    		$content = get_post_meta($gameID, 'uvq_quiz_content', true);
    		$questions = ($content->questions);
    		$results = ($content->results);
    		$finalResult = null;
    		foreach($results as $key => $result):
    			if ($result->range->begin <= $data->count_true_answer && $result->range->end >= $data->count_true_answer) {
    				$finalResult = $result;
    				break;
    			}

    		endforeach;
    		if ($finalResult) $result = array(
    			'status' => 'ok',
    			'result' => $finalResult,
    			'msg' => esc_html__("Successful")
    		);
    		else $result = array(
    			'status' => 'error',
    			'result' => $finalResult,
    			'msg' => esc_html__("Fail")
    		);
    		echo json_encode($result);
    		exit;
    	}
    
        function poll_vote()
        {
            $data = json_decode(file_get_contents('php://input'));
            if (!$data) exit;
            $user = wp_get_current_user();
            $gameID = $data->gameID;
            $indexAnswer = $data->answerIndex;
            $indexQuestion = $data->questionIndex;
            $userID = $user->data->ID;
            $isVoted = false;
            $content = get_post_meta($gameID, 'uvq_quiz_content', true);
            $questions = ($content->questions);
            foreach($content->questions as $questionKey => $question) {
                if ($indexQuestion != $questionKey) continue;
                foreach($question->answers as $answerKey => $answer) {
                    if ($answer->voted && in_array($userID, $answer->voted)) exit;
                    if ($answerKey != $indexAnswer) continue;
                    if ($answerKey == $indexAnswer) {
                        $content->questions[$questionKey]->answers[$answerKey]->totalVoted++;
                        array_push($content->questions[$questionKey]->answers[$answerKey]->voted, $userID);
                    }
                }
            }

            $voted = update_post_meta($gameID, 'uvq_quiz_content', $content);
            if ($voted === true) $result = array(
                'status' => 'ok',
                'msg' => esc_html__("Vote successful")
            );
            else $result = array(
                'status' => 'error',
                'msg' => esc_html__("Vote fail")
            );
            echo json_encode($result);
            exit;
        }
    
        function rank_vote()
    	{
    		$data = json_decode(file_get_contents('php://input'));
    		if (!$data) exit;
    		$user = wp_get_current_user();
    		$gameID = $data->gameID; //= $data->gameID;
    		$index = $data->index;
    		$userID = $user->data->ID;
    		$voteType = $data->voteType; //'count_vote_up';
    		$voted = ($voteType == 'count_vote_up') ? 'vote_up' : 'vote_down';
    		$isVoted = false;
    		$content = get_post_meta($gameID, 'uvq_quiz_content', true);
    		$items = ($content->items);
    		foreach($content->items as $key => $item) {
    			if (($item->vote_up && in_array($userID, $item->vote_up)) || ($item->vote_down && in_array($userID, $item->vote_down))) {
    				exit;
    			}

    			if ($index == $key) {
    				$content->items[$key]->{$voteType}++;
    				array_push($content->items[$key]->{$voted}, $userID);
    			}
    		}

    		$voted = update_post_meta($gameID, 'uvq_quiz_content', $content);
    		if ($voted === true) $result = array(
    			'status' => 'ok',
    			'msg' => esc_html__("Vote successful")
    		);
    		else $result = array(
    			'status' => 'error',
    			'msg' => esc_html__("Vote fail")
    		);
    		echo json_encode($result);
    		exit;
    	}
        
        function lost_password()
    	{
    		$data = json_decode(file_get_contents('php://input'));
    		if (isset($data->email) && email_exists($data->email)) {
				
    			$user = get_user_by('email', $data->email);
				
    			if (isset($user->ID)) {
    				$secret_key = base64_encode($user->ID . '-' . time());
    				add_user_meta($user->ID, 'uvq_lost_password', '', true);
    				$result = update_user_meta($user->ID, 'uvq_lost_password', $secret_key);
    				$link = 'UVQ_RESETPASSWORD_URI' . '?secret_key=' . $secret_key;
    				if ($result) {
    					$to = $data->email;
    					$msg = "<p>Hi,</p>
    					 	<p>We received a password reset request for your %s account. To reset your password, use the URL below:</p>
    						<b>Reset your password:</b>
    						<br />
    						%s
    					 	<p>If you didn't request a password reset, you can ignore this message and your password will not be changed -- someone probably typed in your username or email address by accident.</p>
    						<p>%s Team</p>
    						";
    					$headers[] = sprintf(__('From: %s %s', 'ultimate-viral-quiz') , get_bloginfo('name'), get_option('admin_email'));
    					$subject = sprintf(__('[%s] Confirm reset password', 'ultimate-viral-quiz') , get_bloginfo('name'));
    					$message = sprintf(__($msg, 'ultimate-viral-quiz') , get_bloginfo('name') , $link, get_bloginfo('name'));

    					// wp_mail( $to, $subject, $message, $headers, $attachments );

    					$sent = wp_mail($to, $subject, $message, $headers);
    					if ($sent) {
    						$response = array(
    							'status' => 'ok',
    							'msg' => esc_html__('The Confirm email sent to your email address. Please check your email.', 'ultimate-viral-quiz') ,
    							'link' => $link,
    						);
    						echo json_encode($response);
    						exit;
    					} else {
							$response = array(
				    			'status' => 'error',
				    			'msg' => esc_html__("Can not send email! Please contact Administrator.", 'ultimate-viral-quiz') ,
				    		);
				    		echo json_encode($response);
							exit;
						}
    				}
    			} 
    		}

    		$response = array(
    			'status' => 'error',
    			'msg' => esc_html__('Not found', 'ultimate-viral-quiz') ,
    		);
    		echo json_encode($response);
    		exit;
    	}
        
        function user_login()
    	{
    		$data = json_decode(file_get_contents('php://input'));
    		$response = array(
    			'status' => 'error',
    			'msg' => esc_html__("Username/Email or password is not exactly", 'ultimate-viral-quiz') ,
    		);
    		if (is_email($data->username)) {
    			$user = get_user_by_email($data->username);
    			if (!empty($user->user_login)) {
    				$data->username = $user->user_login;
    			}
    		}

    		if (username_exists($data->username)) {
    			$user = get_user_by('login', $data->username);
    			if (in_array('banned_user', $user->roles)) {
    				$response['msg'] = esc_html__("This account is banned!", 'ultimate-viral-quiz');
    				echo json_encode($response);
    				exit;
    			}
    		}

    		$creds = array();
    		$creds['user_login'] = $data->username;
    		$creds['user_password'] = $data->password;
    		$creds['remember'] = true;
    		$loged = wp_signon($creds, false);

    		// if(wp_login( $data->username, $data->password))

    		if (!is_wp_error($loged)) {
    			$response = array(
    				'status' => 'ok',
    			);
    		}

    		echo json_encode($response);
    		exit;
    	}
        
        function user_signup()
    	{
    		$data = json_decode(file_get_contents('php://input'));
    		$response = array(
    			'status' => 'error',
    			'msg' => esc_html__('Can not signup', 'ultimate-viral-quiz') ,
    		);
    		$userdata = array(
    			'user_login' => $data->username,
    			'user_email' => $data->email,
    			'nickname' => $data->fullname,
    			'user_pass' => $data->password,
    			'role' => get_option('default_role'),
    		);
    		$user_id = wp_insert_user($userdata);
    		if (!is_wp_error($user_id)) {
				// Login
				wp_set_current_user($user_id, $data->email);
        		wp_set_auth_cookie($user_id);
    			$response = array(
    				'status' => 'ok',
    				'msg' => esc_html__('Signup successful', 'ultimate-viral-quiz') ,
    			);
    		}

    		echo json_encode($response);
    		exit;
    	}
    
        function change_password()
    	{
    		$data = json_decode(file_get_contents('php://input'));
    		if (isset($data->password) && $data->password) {
    			$user = wp_get_current_user();
    			if (wp_check_password($data->oldpassword, $user->data->user_pass, $user->ID)) {

    				// $result = wp_set_password( $data->password, $user->ID );

    				$userdata = array(
    					'ID' => $user->ID,
    					'user_pass' => $data->password,
    				);
    				$result = wp_update_user($userdata);
    				if (!is_wp_error($result)) {
    					$response = array(
    						'status' => 'ok',
    						'msg' => esc_html__("Update password successful", 'ultimate-viral-quiz') ,
    					);
    					echo json_encode($response);
    					exit;
    				}
    			}
    			else {
    				$response = array(
    					'status' => 'error',
    					'msg' => esc_html__("Old password is not exactly", 'ultimate-viral-quiz') ,
    				);
    				echo json_encode($response);
    				exit;
    			}
    		}

    		$response = array(
    			'status' => 'error',
    			'msg' => esc_html__("Update password error", 'ultimate-viral-quiz') ,
    		);
    		echo json_encode($response);
    		exit;
    	}
    
        function calc_result()
    	{
    		$data = json_decode(file_get_contents('php://input'));
    		if (isset($data->chooseAnswerData) && $data->chooseAnswerData && isset($data->gameID) && $data->gameID) {
    			$content = get_post_meta($data->gameID, 'uvq_quiz_content', true);
    			$questions = $content->questions;
    			$results = $content->results;
    			$calcResults = array();
    			foreach($questions as $key => $question) {
    				$choose = $data->chooseAnswerData[$key];
    				foreach($question->answers[$choose]->associate as $key => $associate) {
    					if (!$calcResults[$key]) $calcResults[$key] = 0;
    					$calcResults[$key]+= ($associate->point);
    				}
    			}

    			$idResult = array_search(max($calcResults) , $calcResults);
    			$result = ($results[$idResult]);
    			$response = array(
    				'status' => 'ok',
    				'result' => $result,
    			);
    			do_action('_d_countplay_action', $data->gameID);
    			echo json_encode($response);
    			exit;
    		}

    		$response = array(
    			'status' => 'error',
    			'message' => esc_html__("Can't calculating", 'ultimate-viral-quiz') ,
    		);
    		echo json_encode($response);
    		exit;
    	}
    
    }
	
	new BESTBUG_UVQ_AJAX();
}

