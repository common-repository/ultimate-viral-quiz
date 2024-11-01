<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'BESTBUG_UVQ_SHORTCODE' ) ) {
	/**
	 * BESTBUG_UVQ_SHORTCODE Class
	 *
	 * @since	1.0
	 */
	class BESTBUG_UVQ_SHORTCODE {

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
			
			add_shortcode( BESTBUG_UVQ_SHORTCODE, array( $this, 'shortcode' ) );
			// if ( defined( 'WPB_VC_VERSION' ) && function_exists( 'vc_add_param' ) ) {
			// 	$this->vc_shortcode();
			// }
			// 
			// if(is_admin()) {
			// 	add_action( 'admin_enqueue_scripts', array( $this, 'adminEnqueueScripts' ) );
			// }
			// add_action( 'wp_enqueue_scripts', array( $this, 'enqueueScripts' ) );

        }

		public function adminEnqueueScripts() {
			wp_enqueue_style( 'css', BESTBUG_UVQ_URL . '/assets/admin/css/style.css' );
			wp_enqueue_script( 'js', BESTBUG_UVQ_URL . '/assets/admin/js/script.js', array( 'jquery' ), '1.0', true );
		}

		public function enqueueScripts() {
			wp_enqueue_style( 'css', BESTBUG_UVQ_URL . '/assets/css/style.css' );
			wp_enqueue_script( 'js', BESTBUG_UVQ_URL . '/assets/js/script.js', array( 'jquery' ), '1.0', true );
		}
        
        public function vc_shortcode() {
		
        }
		
		public function shortcode( $atts, $content = null ) {
			$atts = shortcode_atts( array(
				'id' => '',
			), $atts );
			if(isset($atts['id']) && !empty($atts['id'])) {
				$id = $atts['id'];
				$type = get_post_meta($id, 'uvq_type', true);
				
				$this->post = get_post($id);
				$thumb = get_post_meta($id, 'uvq_quiz_thumb', true);
				$thumbnail = isset($thumb->cropped_url) ? $thumb->cropped_url : BESTBUG_UVQ_URL . '/assets/images/default_thumbnail.jpg';
				$content = get_post_meta($id, 'uvq_quiz_content', true);
				
				if($type == 'poll') {
					$user = wp_get_current_user();
					$userID = isset($user->ID)?$user->ID:'';

					if(($content->questions))
					  foreach ($content->questions as $questionKey => $question) {
					    $content->questions[$questionKey]->totalVoted = 0;
					    $content->questions[$questionKey]->voted = false;
					    foreach ($question->answers as $answerKey => $answer) {
					      if( $answer->voted && in_array($userID, $answer->voted) )
					      {
					        $content->questions[$questionKey]->voted = true;
					        $content->questions[$questionKey]->votedIndex = $answerKey;
					        //break;
					      }
					      $content->questions[$questionKey]->totalVoted += $answer->totalVoted;
					      unset($content->questions[$questionKey]->answers[$answerKey]->voted);
					    } 
					  }
					$questions = json_encode($content->questions);
					$game = array(
						'gameID' => $id,
						'questions' => json_decode($questions),
					);
				    wp_localize_script('uvq-editor-js', 'GAMEINFO', $game);
				}
				else if(in_array($type, array('personalityquiz', 'triviaquiz'))){
					$questions = json_encode($content->questions);
				    $game = array(
						'gameID' => $id,
						'questions' => json_decode($questions),
					);
				    wp_localize_script('uvq-editor-js', 'game', $game);
				} else if($type == 'rank') {
					$voted = array('voted' => false);

					if ( is_user_logged_in() ) :
						$user = wp_get_current_user();
						$userID = isset($user->ID)?$user->ID:'';
						
					    if($content->items)
					        foreach ($content->items as $key => $item) :
					          $content->items[$key]->VotedIndex = $key;
					          if( $item->vote_up && in_array($userID, $item->vote_up) )
					          {
					            $voted = array(
					                          'voted' => true,
					                          'index' => $key,
					                          'type' => 'count_vote_up',
					                          );
					          }
					          if ( $item->vote_down && in_array($userID, $item->vote_down) )
					          {
					            $voted = array(
					                          'voted' => true,
					                          'index' => $key,
					                          'type' => 'count_vote_down',
					                          );
					          }
					        endforeach;
					endif;
				    $items = ($content->items);
				    $voted = ($voted);
					$game = array(
						'gameID' => $id,
						'items' => $items,
						'voted' => $voted,
					);
				    wp_localize_script('uvq-editor-js', 'RANKINFO', $game);
				}
				
				ob_start();
				
				echo '<div class="uvq-quiz-container">';
				include 'view/quiz_'.$type.'.view.php';
				echo '</div>';
				
				$content = ob_get_contents(); 
				ob_end_clean();
				return $content;
			}
		}
    }
	
	new BESTBUG_UVQ_SHORTCODE();
}

