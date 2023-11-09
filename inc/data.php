<?php // THE DATA FOR THE LIST OF FEATURES

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	$db_features_num = (int) get_option( 'db_features_num' ); // number of features, default value is 1, after adding or removing features the value changes consequently
	$db_features_img = array(); // images of the features
	$db_features_headline = array(); // headlines of the features
	$db_features_text = array(); // descriptions of the features


	// getting the data
	$i = -1;
	while ( ++$i < $db_features_num ) :

		$db_features_img[$i] = (int) get_option( 'db_features_img_' . $i );
		$db_features_headline[$i] = wp_kses_post ( get_option( 'db_features_headline_' . $i ) );
		$db_features_text[$i] = wp_kses_post ( get_option( 'db_features_text_' . $i ) );

	endwhile;

	// getting the styling
	$db_features_cols = (int) get_option( 'db_features_cols' );
	$db_features_cols_tablet = (int) get_option( 'db_features_cols_tablet' );
	$db_features_cols_mobile = (int) get_option( 'db_features_cols_mobile' );
	$db_features_small_cols = (int) get_option( 'db_features_small_cols' );
	$db_features_small_cols_tablet = (int) get_option( 'db_features_small_cols_tablet' );
	$db_features_small_cols_mobile = (int) get_option( 'db_features_small_cols_mobile' );
	$db_features_htmltag_headline = esc_html ( sanitize_text_field( get_option( 'db_features_htmltag_headline' ) ) );
	$db_features_htmltag_text = esc_html ( sanitize_text_field( get_option( 'db_features_htmltag_text' ) ) );