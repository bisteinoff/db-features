<?php

	$db_css = '';
	$db_link = plugin_dir_path( __FILE__ ) . 'custom.min.css';
	$cols = (int) get_option( 'db_features_cols' );
	$cols_tablet = (int) get_option( 'db_features_cols_tablet' );
	$cols_mobile = (int) get_option( 'db_features_cols_mobile' );

	$db_css = "";

	$width = ( $cols === 1 ? 100 : round ( 100 / $cols , 8 ) );
	$width_tablet = ( $cols_tablet === 1 ? 100 : round ( 100 / $cols_tablet , 8 ) );
	$width_mobile = ( $cols_mobile === 1 ? 100 : round ( 100 / $cols_mobile , 8 ) );
	$db_css .= ".db-features-box {width:calc(" . ( $width - 6 ) . "% - 20px);}"; // 3% margin-left and margin-right, 10px padding-left and padding-right
	$db_css .= "@media(max-width:768px){.db-features-box {width:calc(" . ( $width_tablet - 6 ) . "% - 20px);}}";
	$db_css .= "@media(max-width:480px){.db-features-box {width:calc(" . ( $width_mobile - 6 ) . "% - 20px);}}";

	if ( file_exists ( $db_link ) )
		wp_delete_file ( $db_link );

	$db_css_file = fopen( $db_link, "w" );
	fwrite( $db_css_file, $db_css );
	fclose( $db_css_file );