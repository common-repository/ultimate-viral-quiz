<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'BESTBUG_UVQ_TAXONOMIES' ) ) {
	/**
	 * BESTBUG_UVQ_TAXONOMIES Class
	 *
	 * @since	1.0
	 */
	class BESTBUG_UVQ_TAXONOMIES {


		/**
		 * Constructor
		 *
		 * @return	void
		 * @since	1.0
		 */
		function __construct() {
			//$this->init();
			add_filter( 'bb_register_taxonomies', array( $this, 'register_taxonomies' ), 10, 1 );
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
        
		public function register_taxonomies($taxonomies) {
			if( empty($taxonomies) ) {
				$taxonomies = array();
			}
			
			$labels = array(
				'name'                       => _x( 'UVQ Categories', 'UVQ Categories', 'ultimate-viral-quiz' ),
				'singular_name'              => _x( 'UVQ Category', 'UVQ Category', 'ultimate-viral-quiz' ),
				'search_items'               => esc_html__( 'Search UVQ Categories', 'ultimate-viral-quiz' ),
				'popular_items'              => esc_html__( 'Popular UVQ Categories', 'ultimate-viral-quiz' ),
				'all_items'                  => esc_html__( 'All UVQ Categories', 'ultimate-viral-quiz' ),
				'parent_item'                => null,
				'parent_item_colon'          => null,
				'edit_item'                  => esc_html__( 'Edit UVQ Category', 'ultimate-viral-quiz' ),
				'update_item'                => esc_html__( 'Update UVQ Category', 'ultimate-viral-quiz' ),
				'add_new_item'               => esc_html__( 'Add New UVQ Category', 'ultimate-viral-quiz' ),
				'new_item_name'              => esc_html__( 'New UVQ Category Name', 'ultimate-viral-quiz' ),
				'separate_items_with_commas' => esc_html__( 'Separate UVQ Categories with commas', 'ultimate-viral-quiz' ),
				'add_or_remove_items'        => esc_html__( 'Add or remove UVQ Categories', 'ultimate-viral-quiz' ),
				'choose_from_most_used'      => esc_html__( 'Choose from the most used UVQ Categories', 'ultimate-viral-quiz' ),
				'not_found'                  => esc_html__( 'No UVQ Categories found.', 'ultimate-viral-quiz' ),
				'menu_name'                  => esc_html__( 'UVQ Categories', 'ultimate-viral-quiz' ),
			);

			$args = array(
				'hierarchical'          => true,
				'labels'                => $labels,
				'show_ui'               => true,
				'show_admin_column'     => true,
				'update_count_callback' => '_update_post_term_count',
				'query_var'             => true,
				'rewrite'               => array( 'slug' => 'uvq_category' ),
			);

			$taxonomies['uvq_category'] = array(
				'args' => $args,
				'posttypes' => BESTBUG_UVQ_POSTTYPE,
			);
			
			
			$labels = array(
				'name'                       => _x( 'UVQ Tags', 'UVQ Tags', 'ultimate-viral-quiz' ),
				'singular_name'              => _x( 'UVQ Tag', 'UVQ Tag', 'ultimate-viral-quiz' ),
				'search_items'               => esc_html__( 'Search UVQ Tags', 'ultimate-viral-quiz' ),
				'popular_items'              => esc_html__( 'Popular UVQ Tags', 'ultimate-viral-quiz' ),
				'all_items'                  => esc_html__( 'All UVQ Tags', 'ultimate-viral-quiz' ),
				'parent_item'                => null,
				'parent_item_colon'          => null,
				'edit_item'                  => esc_html__( 'Edit UVQ Tag', 'ultimate-viral-quiz' ),
				'update_item'                => esc_html__( 'Update UVQ Tag', 'ultimate-viral-quiz' ),
				'add_new_item'               => esc_html__( 'Add New UVQ Tag', 'ultimate-viral-quiz' ),
				'new_item_name'              => esc_html__( 'New UVQ Tag Name', 'ultimate-viral-quiz' ),
				'separate_items_with_commas' => esc_html__( 'Separate UVQ Tags with commas', 'ultimate-viral-quiz' ),
				'add_or_remove_items'        => esc_html__( 'Add or remove UVQ Tags', 'ultimate-viral-quiz' ),
				'choose_from_most_used'      => esc_html__( 'Choose from the most used UVQ Tags', 'ultimate-viral-quiz' ),
				'not_found'                  => esc_html__( 'No UVQ Tags found.', 'ultimate-viral-quiz' ),
				'menu_name'                  => esc_html__( 'UVQ Tags', 'ultimate-viral-quiz' ),
			);

			$args = array(
				'hierarchical'          => false,
				'labels'                => $labels,
				'show_ui'               => true,
				'show_admin_column'     => true,
				'update_count_callback' => '_update_post_term_count',
				'query_var'             => true,
				'rewrite'               => array( 'slug' => 'uvq_tag' ),
			);

			$taxonomies['uvq_tag'] = array(
				'args' => $args,
				'posttypes' => BESTBUG_UVQ_POSTTYPE,
			);
			
			return $taxonomies;
		}
		
    }
	
	new BESTBUG_UVQ_TAXONOMIES();
}

