<?php
/**
 * Plugin Name:       Rehman Assesment Task for IKONICS
 * Description:       ... 
 * Version:           1.0.0
 * Author:            Rehman
 * Developed By:      Rehman
 * Author URI:        http://#
 * Support:           http://#
 * Domain Path:       /languages
 * Text Domain:       woo-reh-dm
 *
 * @package woo-reh-dm
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (! class_exists('Reh_Projects_Main_Class')) {
	class Reh_Projects_Main_Class {

		public function __construct() {
			$this->global_constant_vars();
			if (is_admin() ) {
				include_once REH_CONSTANT_DIR . '/admin/class-admin.php';
			} else {
				include_once REH_CONSTANT_DIR . '/front/class-front.php';
			}

			add_action( 'wp_loaded', array( $this, 'load_text_domain' ) );

			add_action( 'init', array( $this, 'create_post_type' ) );

		}

		public function load_text_domain() {
			if ( function_exists( 'load_plugin_textdomain' ) ) {
				load_plugin_textdomain( 'woo-reh-dm', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
			}
		}

		public function global_constant_vars() {
			if ( ! defined( 'REH_CONSTANT_URL' ) ) {
				define( 'REH_CONSTANT_URL', plugin_dir_url( __FILE__ ) );
			}

			if ( ! defined( 'REH_CONSTANT_BASENAME' ) ) {
				define( 'REH_CONSTANT_BASENAME', plugin_basename( __FILE__ ) );
			}
			if ( ! defined( 'REH_CONSTANT_DIR' ) ) {
				define( 'REH_CONSTANT_DIR', plugin_dir_path( __FILE__ ) );
			}
		}

		public function create_post_type() {
			$pt_label = array(
				'name'        => __('Projects', 'woo-reh-dm'),
				'singular_name'    => __('Projects', 'woo-reh-dm'),
				'add_new'       => __('Add New Project', 'woo-reh-dm'),
				'add_new_item'    => __('Add Project', 'woo-reh-dm'),
				'edit_item'      => __('Edit Project', 'woo-reh-dm'),
				'new_item'      => __('New Project', 'woo-reh-dm'),
				'view_item'      => __('View Project', 'woo-reh-dm'),
				'search_items'    => __('Search Project', 'woo-reh-dm'),
				'exclude_from_search' => true,
				'not_found'      => __('No Project found', 'woo-reh-dm'),
				'not_found_in_trash' => __('No Project found in trash', 'woo-reh-dm'),
				'parent_item_colon'  => '',
				'all_items'      => __('All Projects', 'woo-reh-dm'),
				'menu_name'      => __('Projects', 'woo-reh-dm'),
			);
			$pt_args  = array(
				'labels' => $pt_label,
				'public' => false,
				'publicly_queryable' => false,
				'show_ui' => true,
				'show_in_menu' => true,
				'query_var' => true,
				'rewrite' => true,
				'capability_type' => 'post', 
				'has_archive' => true,
				'hierarchical' => false,
				'menu_position' => 30,
				'rewrite' => array('slug' => 'woo-reh-dm-rule', 'with_front'=>false ),
			);
			register_post_type( 'reh_projects_post', $pt_args );



			$tax_labels = array(
				'name' => __( 'Project Types', 'woo-reh-dm' ),
				'singular_name' => __( 'Project Type', 'woo-reh-dm' ),
				'search_items' =>  __( 'Search Project Types' ),
				'all_items' => __( 'All Project Types' , 'woo-reh-dm' ),
				'parent_item' => __( 'Parent Project Type' , 'woo-reh-dm' ),
				'parent_item_colon' => __( 'Parent Project Type:' , 'woo-reh-dm' ),
				'edit_item' => __( 'Edit Project Type' , 'woo-reh-dm' ), 
				'update_item' => __( 'Update Project Type' , 'woo-reh-dm' ),
				'add_new_item' => __( 'Add New Project Type' , 'woo-reh-dm' ),
				'new_item_name' => __( 'New Project Type Name' , 'woo-reh-dm' ),
				'menu_name' => __( 'Project Types' , 'woo-reh-dm' )
			);    
			$tax_args   = array(
				'hierarchical' => true,
				'labels' => $tax_labels,
				'show_ui' => true,
				'show_in_rest' => true,
				'show_admin_column' => true,
				'query_var' => true,
				'rewrite' => array( 'slug' => 'subject' )
			);
			register_taxonomy( 'project-types', array('reh_projects_post'), $tax_args );


			if ( get_page_by_title('Projects')==null ) {
				$new_page = array(
					'post_title' => 'Projects',
					'post_content' => '[reh_assesment_tasks_shortcode]',
					'post_status' => 'publish',
					'post_type' => 'page'
				);
				$post_id  = wp_insert_post($new_page);
			}

			if ( get_page_by_title('Quotes')==null ) {
				$new_page = array(
					'post_title' => 'Quotes',
					'post_content' => '[reh_at_quotes_shortcode]',
					'post_status' => 'publish',
					'post_type' => 'page'
				);
				$post_id  = wp_insert_post($new_page);
			}
		}

	}
	new Reh_Projects_Main_Class();
}
