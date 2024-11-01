<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'BESTBUG_UVQ_POSTTYPES' ) ) {
	/**
	 * BESTBUG_UVQ_POSTTYPES Class
	 *
	 * @since	1.0
	 */
	class BESTBUG_UVQ_POSTTYPES {


		/**
		 * Constructor
		 *
		 * @return	void
		 * @since	1.0
		 */
		function __construct() {
			//$this->init();
			add_filter( 'bb_register_posttypes', array( $this, 'register_posttypes' ), 10, 1 );
		}

		public function init() {

			if(is_admin()) {
				add_action( 'admin_enqueue_scripts', array( $this, 'adminEnqueueScripts' ) );
			}
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueueScripts' ) );

        }

		public function adminEnqueueScripts() {
		
		}

		public function enqueueScripts() {
		
        }
        
		public function register_posttypes($posttypes) {
			if( empty($posttypes) ) {
				$posttypes = array();
			}
			
			$labels = array(
				'name'               => _x( 'UVQ Quiz', 'UVQ Quiz', 'ultimate-viral-quiz' ),
				'singular_name'      => _x( 'UVQ Quiz', 'UVQ Quiz', 'ultimate-viral-quiz' ),
				'menu_name'          => esc_html__( 'UVQ Quiz', 'ultimate-viral-quiz' ),
				'name_admin_bar'     => esc_html__( 'UVQ Quiz', 'ultimate-viral-quiz' ),
				'parent_item_colon'  => esc_html__( 'Parent Menu:', 'ultimate-viral-quiz' ),
				'all_items'          => esc_html__( 'All UVQ Quizs', 'ultimate-viral-quiz' ),
				'add_new_item'       => esc_html__( 'Add New UVQ Quiz Content', 'ultimate-viral-quiz' ),
				'add_new'            => esc_html__( 'Add New', 'ultimate-viral-quiz' ),
				'new_item'           => esc_html__( 'New UVQ Quiz Content', 'ultimate-viral-quiz' ),
				'edit_item'          => esc_html__( 'Edit UVQ Quiz Content', 'ultimate-viral-quiz' ),
				'update_item'        => esc_html__( 'Update UVQ Quiz Content', 'ultimate-viral-quiz' ),
				'view_item'          => esc_html__( 'View UVQ Quiz Content', 'ultimate-viral-quiz' ),
				'search_items'       => esc_html__( 'Search UVQ Quiz Content', 'ultimate-viral-quiz' ),
				'not_found'          => esc_html__( 'Not found', 'ultimate-viral-quiz' ),
				'not_found_in_trash' => esc_html__( 'Not found in Trash', 'ultimate-viral-quiz' ),
			);
			$args   = array(
				'label'               => esc_html__( 'UVQ Quiz', 'lamblue' ),
				'description'         => esc_html__( 'UVQ Quiz', 'lamblue' ),
				'labels'              => $labels,
				'supports'            => array( 'title', 'editor', ),
				'hierarchical'        => true,
				'public'              => true,
				'show_ui'             => false,
				'show_in_menu'        => false,
				'menu_position'       => 13,
				'menu_icon'           => 'dashicons-schedule',
				'show_in_admin_bar'   => false,
				'show_in_nav_menus'   => true,
				'can_export'          => true,
				'has_archive'         => true,
				'exclude_from_search' => true,
				'publicly_queryable'  => true,
				'rewrite'             => true,
				'capability_type'     => 'post',
			);
			$posttypes[BESTBUG_UVQ_POSTTYPE] = $args;
			
			return $posttypes;
		}
        
    }
	
	new BESTBUG_UVQ_POSTTYPES();
}

