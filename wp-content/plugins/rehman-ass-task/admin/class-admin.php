<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if (! class_exists('Reh_Projects_Admin_Class')) {
	class Reh_Projects_Admin_Class {

		public function __construct() {
			add_action('wp_ajax_get_reh_projects', array( $this, 'get_reh_projects_cb' ) );
			add_action('wp_ajax_nopriv_get_reh_projects', array( $this, 'get_reh_projects_cb' ) );

		}

		public function get_reh_projects_cb() {
			if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash(  isset($_POST['nonce']) ? $_POST['nonce'] : '' ) ) , 'ajax-nonce' ) ) {
				die ( 'Destroy!');
			}
			$user            = sanitize_text_field( wp_unslash(  isset($_POST['user']) ? $_POST['user'] : 'no' ) );
			$no_of_posts     = ( 'yes' == $user ) ? 6 : 3;
			$projects        = get_posts(array(
				'post_type' => 'reh_projects_post',
				'posts_per_page' => $no_of_posts,
				'fields' => 'ids',
				'orderby' => 'date',
				'order' => 'ASC',
				'tax_query' => array(
					array(
							'taxonomy' => 'project-types',
							'field' => 'name',
							'terms' => 'Architecture'
						)
					)
				 )
			);
			$return_projects = array();
			foreach ($projects as $key => $id) {
				$return_projects[] = (object) array( 'id' => $id , 'title' => get_the_title($id) , 'link' => get_permalink($id) );
			}

			wp_send_json( array( 'success' => true , 'data' => $return_projects ) );
		}

	}
	new Reh_Projects_Admin_Class();
}
