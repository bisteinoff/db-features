<?php

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	$db_css = '';
	$db_link = plugin_dir_path( __FILE__ ) . 'custom.min.css';
	$cols = (int) get_option( 'db_features_cols' );
	$cols_tablet = (int) get_option( 'db_features_cols_tablet' );
	$cols_mobile = (int) get_option( 'db_features_cols_mobile' );
	$small_cols = (int) get_option( 'db_features_small_cols' );
	$small_cols_tablet = (int) get_option( 'db_features_small_cols_tablet' );
	$small_cols_mobile = (int) get_option( 'db_features_small_cols_mobile' );

	$db_css = "";

	$width = ( $cols === 1 ? 100 : round ( 100 / $cols , 8 ) );
	$width_tablet = ( $cols_tablet === 1 ? 100 : round ( 100 / $cols_tablet , 8 ) );
	$width_mobile = ( $cols_mobile === 1 ? 100 : round ( 100 / $cols_mobile , 8 ) );
	$width_small = ( $small_cols === 1 ? 100 : round ( 100 / $small_cols , 8 ) );
	$width_small_tablet = ( $small_cols_tablet === 1 ? 100 : round ( 100 / $small_cols_tablet , 8 ) );
	$width_small_mobile = ( $small_cols_mobile === 1 ? 100 : round ( 100 / $small_cols_mobile , 8 ) );

	// Desktop
	$db_css .= ".db-features-box{width:" . ( $width - 6 ) . "%}" . // 3% margin-left and margin-right
	           ".db-features-box.db-features-type-small{width:" . ( $width_small - 2 ) . "%}"; // 1% margin-left and margin-right

	// Tablet
	$db_css .= "@media(max-width:768px){" .
	           ".db-features-box{width:" . ( $width_tablet - 6 ) . "%}" .
	           ".db-features-box.db-features-type-small{width:" . ( $width_small_tablet - 2 ) . "%}" .
	           "}";

	// Mobile
	$db_css .= "@media(max-width:480px){" .
	           ".db-features-box{width:" . ( $width_mobile - 6 ) . "%}" .
	           ".db-features-box.db-features-type-small{width:" . ( $width_small_mobile - 2 ) . "%}" .
	           "}";

/*	if ( $fontsize > 0 )
		$db_css .= "font-size:" . $fontsize . "px;";

	if ( $fontweight > 0 ) {

		switch ( $fontweight ) {

			case 1 : $db_css .= "font-weight:700;";
			break;

			case 2 : $db_css .= "font-style:italic;";
			break;

			case 3 : $db_css .= "font-weight:700;font-style:italic;";
			break;

		}

	}

	if ( $borderwidth !== '' && $borderwidth >= 0 )
		$db_css .= "border-width:" . $borderwidth . "px;";

	if ( $color !== '' )
		$db_css .= "border-color:{$color};color:{$color}";*/

	if ( file_exists ( $db_link ) )
		wp_delete_file ( $db_link );

	$db_css_file = fopen( $db_link, "w" );
	fwrite( $db_css_file, $db_css );
	fclose( $db_css_file );