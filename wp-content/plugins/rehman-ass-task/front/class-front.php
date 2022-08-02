<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if (! class_exists('Reh_Projects_Front_Class')) {
	class Reh_Projects_Front_Class {

		public function __construct() {

			add_action('wp_loaded', array( $this, 'check_users_ip' ) );
			
			add_action('wp_enqueue_scripts', array( $this, 'add_scripts' ) );

			add_shortcode( 'reh_assesment_tasks_shortcode', array($this , 'projects_listing_fn' ) );

			add_shortcode( 'reh_at_quotes_shortcode', array($this , 'quotes_listing_fn' ) );

			add_filter( 'wp_nav_menu_items', array($this, 'new_nav_menu_items') );

		}

		public  function check_users_ip() {
			$current_ip = '';
			if ( isset($_SERVER['HTTP_CLIENT_IP']) ) {
				$current_ip = sanitize_text_field( wp_unslash( isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : '' ) );
			} elseif ( isset($_SERVER['HTTP_CLIENT_IP']) ) {
				$current_ip = sanitize_text_field( wp_unslash( isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : '' ) );
			} elseif ( isset($_SERVER['HTTP_CLIENT_IP']) ) {
				$current_ip = sanitize_text_field( wp_unslash( isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '' ) );
			}
			$user_ip    = apply_filters( 'wpb_get_ip', $current_ip );
			$start_with = '77.29';
			if ( substr( $user_ip, 0, 5 ) == $start_with  ) {
				wp_redirect( esc_url('google.com') );
				exit;
			}
		}

		public function add_scripts() {
			wp_enqueue_style( 'reh-projects-scripts_css', plugins_url( 'assets/css/front-styles.css' , __DIR__ ) , false, '1.0.0' );
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'reh-projects-scripts_script', plugins_url( 'assets/js/front-script.js' , __DIR__ ) , false, '1.0.0' , $in_footer = false );
			wp_localize_script(
				'reh-projects-scripts_script',
				'reh_proj_object',
				array( 
					'ajax_url' => admin_url('admin-ajax.php'),
					'nonce' => wp_create_nonce('ajax-nonce'),
					'user' => is_user_logged_in() ? 'yes' : 'no'
					) 
			);
		
		}
	
		public function projects_listing_fn() {
			$the_query = new WP_Query( 
							array(
								'posts_per_page'=> 6,
								'post_type'=>'reh_projects_post',
								'paged' =>  get_query_var('paged') ? get_query_var('paged') : 1) 
					); 
			?>
				<div class="reh-projects-listing">
					<?php
					if ( $the_query -> have_posts() ) {
						while ( $the_query -> have_posts() ) : 
							$the_query -> the_post(); 
							?>
									<div class="reh-singleproject">
										<a href="<?php the_permalink(); ?>" target="_blank"><?php echo esc_html__( get_the_title() , 'woo-reh-dm' ); ?></a>
										<div class="file-description"><?php the_content(); ?></div>
									</div>
								<?php
							endwhile;
					} else {
						?>
								<div class="reh-singleproject">
									<p ><?php echo esc_html__( 'No Project Found' , 'woo-reh-dm' ); ?></p>
								<div>    
							<?php
					}
					?>
				</div>
				<div class="reh-project-pagination">
					<?php
						echo wp_kses_post( paginate_links( array(
							'base' => str_replace( PHP_INT_MAX , '%#%', get_pagenum_link( PHP_INT_MAX ) ),
							'format' => '?paged=%#%',
							'current' => max( 1, get_query_var('paged') ),
							'total' => $the_query->max_num_pages
						) ));
						wp_reset_postdata();
					?>
				</div>
			<?php
		}

		public function quotes_listing_fn() {
			?>
				<div class="reh-projects-listing">
					<?php
					for ($increment = 1 ; $increment < 6; $increment++) { 
						$api_resp = ( wp_remote_get( 'https://api.kanye.rest/' ) );
						if ( isset($api_resp['body']) ) {
							$quote_obj = json_decode( $api_resp['body']);
							?>
								<div class="reh-singleproject">
									<a href="#" target="_blank"><?php echo esc_html__( 'Quotes ' . $increment , 'woo-reh-dm' ); ?></a>
									<div class="file-description"><?php echo esc_html__( $quote_obj->quote , 'woo-reh-dm' ); ?></div>
								</div>
							<?php
						}
					}
					?>
				</div>
			<?php		
		}

		
		public function new_nav_menu_items( $items) {
			$project = get_page_by_title('Projects');
			if ( is_object($project) ) {
				$items .= '<li class="home"><a href="' . esc_url( get_permalink($project->ID) ) . '">' . __('Projects') . '</a></li>';
			}
			$quote = get_page_by_title('Quotes');
			if ( is_object($quote) ) {
				$items .= '<li class="home"><a href="' . esc_url( get_permalink($quote->ID) ) . '">' . __('Quotes') . '</a></li>';
			}
			return $items;
		}

	}
	new Reh_Projects_Front_Class();
}
