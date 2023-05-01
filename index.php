<?php
/*
Plugin Name: DB Features
Plugin URI: https://github.com/bisteinoff/db-features/
Description: The plugin is used for the basic website settings
Version: 1.2
Author: Denis Bisteinov
Author URI: https://bisteinoff.com
License: GPL2
*/

/*  Copyright YEAR  PLUGIN_AUTHOR_NAME  (email : bisteinoff@gmail.com)
 
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.
 
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
 
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

	class dbFeatures

	{

		function dbFeatures()
		{

			add_option( 'db_features_num', 1 ); // number of features
			add_option( 'db_features_img_0' );
			add_option( 'db_features_headline_0' );
			add_option( 'db_features_text_0' );
			add_option( 'db_features_cols', 3 );
			add_option( 'db_features_cols_tablet', 2 );
			add_option( 'db_features_cols_mobile', 1 );

			add_filter( 'plugin_action_links_db-features/index.php', array(&$this, 'db_features_link') );
			add_action( 'admin_menu', array (&$this, 'admin') );

			wp_enqueue_style( 'db-features', plugin_dir_url( __FILE__ ) . 'css/style.css' );
			wp_enqueue_style( 'db-features-custom', plugin_dir_url( __FILE__ ) . 'css/custom.min.css' );

			add_action( 'admin_footer', array (&$this, 'admin_footer_js') );
			add_action( 'admin_footer', function() {
							wp_enqueue_style( 'db-features-admin', plugin_dir_url( __FILE__ ) . 'css/admin.css' );
							wp_enqueue_editor();
							wp_enqueue_script( 'db-features-admin', plugin_dir_url( __FILE__ ) . 'js/admin.js', null, false, true );
						},
						99
			);
			add_action( 'admin_enqueue_scripts', function() {
						},
						99
			);

			if (function_exists ('add_shortcode') )
			{

				add_shortcode('db-features', function() {

					$html = '<div class="db-features">';

					$i = -1;
					$db_features_num = (int) get_option('db_features_num');
					while ( ++$i < $db_features_num ) :

						$html .= 
							'<div class="db-features-box">'.
								'<div class="db-features-box-img">' . wp_get_attachment_image ( (int) get_option( 'db_features_img_' . $i ) , 'medium' ) . '</div>' .
								'<h3 class="db-features-box-headline">' . wp_kses_post ( get_option( 'db_features_headline_' . $i ) ) . '</h3>' .
								'<div class="db-features-box-text">' . wp_kses_post ( get_option( 'db_features_text_' . $i ) ) . '</div>' .
							'</div>';

					endwhile;

					$html .= '</div>';

					return $html;

				});

			}

		}

		function admin() {

			if ( function_exists('add_menu_page') )
			{

				$icon = '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 50 50" enable-background="new 0 0 50 50" xml:space="preserve">
<path fill="#231F20" d="M15.868,28.234l-2.33,9.757c-0.094,0.391,0.057,0.8,0.38,1.038c0.324,0.237,0.757,0.26,1.104,0.054
	l8.762-5.203l8.758,5.203c0.158,0.094,0.335,0.141,0.511,0.141c0.209,0,0.417-0.065,0.593-0.194c0.323-0.238,0.474-0.647,0.38-1.038
	l-2.33-9.757l7.734-6.554c0.309-0.261,0.43-0.683,0.306-1.068c-0.123-0.385-0.467-0.658-0.87-0.691L28.665,19.08l-3.963-9.25
	c-0.157-0.368-0.519-0.606-0.919-0.606s-0.762,0.238-0.919,0.606l-3.966,9.25L8.7,19.921c-0.403,0.033-0.747,0.306-0.87,0.691
	c-0.124,0.385-0.003,0.807,0.306,1.068L15.868,28.234z M19.662,21.023c0.369-0.03,0.691-0.262,0.837-0.603l3.284-7.659l3.28,7.658
	c0.146,0.341,0.468,0.573,0.837,0.603l8.394,0.692l-6.361,5.391c-0.288,0.243-0.414,0.628-0.326,0.995l1.924,8.055l-7.236-4.299
	c-0.314-0.188-0.707-0.188-1.021,0l-7.24,4.3l1.924-8.056c0.088-0.367-0.038-0.752-0.326-0.995l-6.359-5.391L19.662,21.023z"/>
</svg>';

				add_menu_page(
					'DB Features',
					'Features',
					'manage_options',
					'db-features',
					array (&$this, 'admin_page_callback'),
					'data:image/svg+xml;base64,' . base64_encode( $icon ),
					27
					);

			}

		}

		function admin_page_callback()
		{

			require_once('inc/admin.php');

		}

		function db_features_link( $links )
		{

			$url = esc_url ( add_query_arg (
				'page',
				'db-features',
				get_admin_url() . 'index.php'
			) );

			$settings_link = "<a href='$url'>" . __( 'Settings' ) . '</a>';

			array_push(
				$links,
				$settings_link
			);

			return $links;

		}

		function admin_footer_js()
		{
			?>
				<script type="text/javascript">
					let dbFeaturesTexts = Array (
						"<?php _e('Image' , 'db-features') ?>",
						"<?php _e('Headline' , 'db-features') ?>",
						"<?php _e('Text' , 'db-features') ?>"
						);
				</script>
			<?php
			
		}

	}

	$db_features = new dbFeatures();