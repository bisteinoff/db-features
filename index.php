<?php
/*
Plugin Name: DB Features
Plugin URI: https://github.com/bisteinoff/db-features/
Description: The plugin is used for the basic website settings
Version: 1.3.4
Author: Denis Bisteinov
Author URI: https://bisteinoff.com
License: GPL2
*/

/*  Copyright 2023  Denis BISTEINOV  (email : bisteinoff@gmail.com)
 
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

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class dbFeatures

	{

		public function thisdir()
		{
			return basename( __DIR__ );
		}

		public function __construct()
		{

			add_option( 'db_features_num', 1 ); // number of features
			add_option( 'db_features_img_0' );
			add_option( 'db_features_headline_0' );
			add_option( 'db_features_text_0' );
			add_option( 'db_features_cols', 3 );
			add_option( 'db_features_cols_tablet', 2 );
			add_option( 'db_features_cols_mobile', 1 );
			add_option( 'db_features_small_cols', 6 );
			add_option( 'db_features_small_cols_tablet', 3 );
			add_option( 'db_features_small_cols_mobile', 2 );
			add_option( 'db_features_htmltag_headline', 'h3' );
			add_option( 'db_features_htmltag_text', 'div' );

			add_filter( 'plugin_action_links_' . $this->thisdir() . '/index.php', array(&$this, 'db_features_link') );
			add_action( 'admin_menu', array (&$this, 'admin') );

			add_action( 'wp_enqueue_scripts', function() {
							wp_enqueue_style( $this->thisdir(), plugin_dir_url( __FILE__ ) . 'css/style.css' );
							wp_enqueue_style( $this->thisdir() . '-custom', plugin_dir_url( __FILE__ ) . 'css/custom.min.css' );
						},
						99
			);

			add_action( 'admin_footer', array (&$this, 'admin_footer_js') );
			add_action( 'admin_footer', function() {
							wp_enqueue_style( $this->thisdir() . '-admin', plugin_dir_url( __FILE__ ) . 'css/admin.css' );
							wp_enqueue_editor();
							wp_enqueue_script( $this->thisdir() . '-admin', plugin_dir_url( __FILE__ ) . 'js/admin.js', null, false, true );
						},
						99
			);

			if (function_exists ('add_shortcode') )
			{
				add_shortcode('db-features', array(&$this, 'shortcode') );
			}

		}

		public function shortcode( $attr ) {

			$attr = shortcode_atts( [
				'type' => 'big'
			], $attr );

			$type = $attr['type'];

			$html = '<div class="db-features">';

			$valid_htmltags = array('h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'p');
			$db_features_htmltag_headline = esc_html ( sanitize_text_field( get_option( 'db_features_htmltag_headline' ) ) );
			$db_features_htmltag_text     = esc_html ( sanitize_text_field( get_option( 'db_features_htmltag_text'     ) ) );
			$tag_headline = ( in_array( $db_features_htmltag_headline, $valid_htmltags ) ? $db_features_htmltag_headline : 'h3'  );
			$tag_text     = ( in_array( $db_features_htmltag_text,     $valid_htmltags ) ? $db_features_htmltag_text     : 'div' );

			$i = -1;
			$db_features_num = (int) get_option('db_features_num');
			while ( ++$i < $db_features_num ) :

				$html .= 
					'<div class="db-features-box db-features-type-' . $type . '">'.
						'<div class="db-features-box-img">' . wp_get_attachment_image ( (int) get_option( 'db_features_img_' . $i ) , 'medium' ) . '</div>' .
						'<' . $tag_headline . ' class="db-features-box-headline">' . wp_kses_post ( get_option( 'db_features_headline_' . $i ) ) . '</' . $tag_headline . '>' .
						'<' . $tag_text . ' class="db-features-box-text">' . wp_kses_post ( get_option( 'db_features_text_' . $i ) ) . '</' . $tag_text . '>' .
					'</div>';

			endwhile;

			$html .= '</div>';

			return $html;

		}

		public function admin() {

			if ( function_exists('add_menu_page') )
			{

				$svg = new DOMDocument();
				$svg -> load( plugin_dir_path( __FILE__ ) . 'img/icon.svg' );
				$icon = $svg -> saveHTML( $svg -> getElementsByTagName('svg')[0] );

				add_menu_page(
					__('DB Features' , $this->thisdir() ),
					__('Features' , $this->thisdir() ),
					'manage_options',
					$this->thisdir(),
					array (&$this, 'admin_page_callback'),
					'data:image/svg+xml;base64,' . base64_encode( $icon ),
					27
					);

			}

		}

		public function admin_page_callback() {

			require_once('inc/admin.php');

		}

		public function db_features_link( $links ) {

			$url = esc_url ( add_query_arg (
				'page',
				$this->thisdir(),
				get_admin_url() . 'index.php'
			) );

			$settings_link = "<a href='$url'>" . __( 'Settings' ) . '</a>';

			array_push(
				$links,
				$settings_link
			);

			return $links;

		}

		public function admin_footer_js() {
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