<?php
/*
Plugin Name: Ultimate Viral Quiz
Description: Create awesome and viral quizzes on your WP site in frontend and backend, as Buzzfeed and Playbuzz does, but with more features ! It’s the best and the simplest wordpress quiz plugin ever!
Author: Hameha
Version: 1.0
Author URI: http://ultimate-viral-quiz.lamblue.com/
Text Domain: ultimate-viral-quiz
Domain Path: /languages
Tags: buzzfeed, BuzzFeed Quiz, BuzzFeed Quiz Builder, playbuzz, quiz, quizz, quizzes, viral plugin, viral quizz, wordpress game, wordpress quiz, wordpress quizzes, wordpress test, wordpress trivia, wordpress viral
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

defined( 'BESTBUG_UVQ_URL' ) or define('BESTBUG_UVQ_URL', plugins_url( '/', __FILE__ ));
defined( 'BESTBUG_UVQ_PATH' ) or define('BESTBUG_UVQ_PATH', basename( dirname( __FILE__ )));
defined( 'BESTBUG_UVQ_TEXTDOMAIN' ) or define('BESTBUG_UVQ_TEXTDOMAIN', plugins_url( '/', __FILE__ ));

defined( 'BESTBUG_UVQ_SHORTCODE' ) or define('BESTBUG_UVQ_SHORTCODE', 'uvq_quiz');
defined( 'BESTBUG_UVQ_CATEGORY' ) or define('BESTBUG_UVQ_CATEGORY', 'Ultimate Viral Quiz');

// Prefix
defined( 'BESTBUG_UVQ_PREFIX' ) or define('BESTBUG_UVQ_PREFIX', 'uvq_');

// Posttype
defined( 'BESTBUG_UVQ_POSTTYPE' ) or define('BESTBUG_UVQ_POSTTYPE', 'uvq_quiz');

// Slug
defined( 'BESTBUG_UVQ_SLUG_ALL_QUIZZES' ) or define('BESTBUG_UVQ_SLUG_ALL_QUIZZES', 'uvq-all-quizzes');
defined( 'BESTBUG_UVQ_SLUG_ADD_QUIZZ' ) or define('BESTBUG_UVQ_SLUG_ADD_QUIZZ', 'uvq-add-quiz');
defined( 'BESTBUG_UVQ_SLUG_SETTINGS' ) or define('BESTBUG_UVQ_SLUG_SETTINGS', 'uvq-settings');

/**
 * BESTBUG_UVQ_CLASS Class
 *
 * @since	1.0
 */
class BESTBUG_UVQ_CLASS {
	/**
	 * Constructor
	 *
	 * @return	void
	 * @since	1.0
	 */
	function __construct() {
		// Load core
		if(!class_exists('BESTBUG_CORE_CLASS')) {
			include_once 'bestbugcore/index.php';
		}
		BESTBUG_CORE_CLASS::support('vc-params');
		BESTBUG_CORE_CLASS::support('options');
		BESTBUG_CORE_CLASS::support('posttypes');
		
		include_once 'includes/index.php';
		if(is_admin()) {
			include_once 'includes/admin/index.php';
		}
		
		$this->loadShortcodes();
		add_action( 'init', array( $this, 'init' ) );
	}

	public function init() {
		
		// Load enqueueScripts
		if(is_admin()) {
			add_action( 'admin_enqueue_scripts', array( $this, 'adminEnqueueScripts' ) );
		}
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueueScripts' ) );

	}

	public function adminEnqueueScripts() {
		wp_enqueue_script( 'sweetalert', BESTBUG_CORE_URL . '/assets/admin/js/sweetalert.min.js', array( 'jquery' ), null, true );
		wp_enqueue_script( 'ultimate-viral-quiz', BESTBUG_UVQ_URL . '/assets/admin/js/script.js', array( 'jquery' ), '1.0', true );
	}

	public function enqueueScripts() {
		wp_enqueue_style( 'uvq-css', BESTBUG_UVQ_URL . '/assets/css/style.css' );
		wp_enqueue_script( 'sticky', BESTBUG_UVQ_URL . '/assets/libs/vender/sticky.min.js', array( 'jquery' ), '1.0', true );
		wp_enqueue_script( 'jquery-validation', BESTBUG_UVQ_URL . '/assets/libs/vender/jquery-validation/jquery.validate.min.js', array( 'jquery' ), '1.0', true );
		wp_enqueue_script( 'additional-methods', BESTBUG_UVQ_URL . '/assets/libs/vender/jquery-validation/additional-methods.min.js', array( 'jquery' ), '1.0', true );
		
		wp_enqueue_script( 'sweetalert', BESTBUG_CORE_URL . '/assets/admin/js/sweetalert.min.js', array( 'jquery' ), null, true );
		
		wp_enqueue_script( 'jquery-matchHeight', BESTBUG_UVQ_URL . '/assets/libs/vender/jquery.matchHeight-min.js', array( 'jquery' ), '0.5.2', true );
		
		wp_enqueue_script( 'ultimate-viral-quiz', BESTBUG_UVQ_URL . '/assets/js/script.js', array( 'jquery' ), '1.0', true );
		
	}

	public function loadTextDomain() {
		load_plugin_textdomain( BESTBUG_UVQ_TEXTDOMAIN, false, BESTBUG_UVQ_PATH . '/languages/' );
	}

	public function loadShortcodes() {
		include_once 'includes/shortcodes/index.php';
	}
}
new BESTBUG_UVQ_CLASS();
