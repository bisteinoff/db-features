<?php // THE SETTINGS PAGE

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	$db_features = new dbFeatures();
	$d = $db_features -> thisdir(); // domain for translate.wordpress.org

	require( 'data.php' ); // getting the data of the list of features

	// getting the data from the form
	if ( isset ( $_POST['submit'] ) ) :

		if ( function_exists('current_user_can') &&
			 !current_user_can('manage_options') )
				die( _e( 'Error: You do not have the permission to update the value' , $d ) );

		if ( function_exists('check_admin_referrer') )
			check_admin_referrer('db_features_form');

		// Number of features
		$db_features_num = (int) $_POST['num'];
		update_option ( 'db_features_num', $db_features_num );

		$i = -1;
		while ( ++$i < $db_features_num ) :

			add_option( 'db_features_img_'.$i );
			add_option( 'db_features_headline_'.$i );
			add_option( 'db_features_text_'.$i );

			if ( $_POST['img_id_'.$i] || $_FILES['img_'.$i] || $_POST['headline_'.$i] || $_POST['text_'.$i] )
			{
				// Image: first, we check the value of the hidden input
				$db_features_img[$i] = (int) $_POST['img_id_'.$i];
				update_option ( 'db_features_img_'.$i, $db_features_img[$i] );

				// Image: second, we check if a new image should be uploaded
				if ( !empty ( $_FILES['img_'.$i] ))
				{
					require( ABSPATH . 'wp-load.php' );
					require_once( ABSPATH . 'wp-admin/includes/file.php' );

					$db_upload = wp_handle_upload (
						$_FILES['img_'.$i],
						array( 'test_form' => false )
					);

					if ( empty( $db_upload['error'] ) )
						{
							$db_features_img[$i] = wp_insert_attachment(
								array(
									'guid'           => $db_upload['url'],
									'post_mime_type' => $db_upload['type'],
									'post_title'     => basename( $db_upload['file'] ),
									'post_content'   => '',
									'post_status'    => 'inherit',
								),
								$db_upload['file']
							);

							if( !is_wp_error( $db_features_img[$i] ) && $db_features_img[$i] )
							{
								require_once( ABSPATH . 'wp-admin/includes/image.php' );

								wp_update_attachment_metadata(
									$db_features_img[$i],
									wp_generate_attachment_metadata( $db_features_img[$i], $db_upload['file'] )
								);

								update_option ( 'db_features_img_' . $i, $db_features_img[$i] );
							}

						}

				}

				// Headline
				$db_features_headline[$i] = wp_kses_post ( $_POST['headline_'.$i] );
				update_option ( 'db_features_headline_'.$i, $db_features_headline[$i] );

				// Text
				$db_features_text[$i] = wp_kses_post ( $_POST['text_'.$i] );
				update_option ( 'db_features_text_'.$i, $db_features_text[$i] );
			}

		endwhile;

		/* Type DEFAULT (BIG) */
		// Number of columns: Desktop
		$db_features_cols = (int) $_POST['cols'];
		if ( $db_features_cols < 1 ) $db_features_cols = 1;
		update_option ( 'db_features_cols', $db_features_cols );

		// Number of columns: Tablet
		$db_features_cols_tablet = (int) $_POST['cols_tablet'];
		if ( $db_features_cols_tablet < 1 ) $db_features_cols_tablet = 1;
		update_option ( 'db_features_cols_tablet', $db_features_cols_tablet );

		// Number of columns: Mobile
		$db_features_cols_mobile = (int) $_POST['cols_mobile'];
		if ( $db_features_cols_mobile < 1 ) $db_features_cols_mobile = 1;
		update_option ( 'db_features_cols_mobile', $db_features_cols_mobile );

		/* Type SMALL */
		// Number of columns: Desktop
		$db_features_small_cols = (int) $_POST['small_cols'];
		if ( $db_features_small_cols < 1 ) $db_features_small_cols = 1;
		update_option ( 'db_features_small_cols', $db_features_small_cols );

		// Number of columns: Tablet
		$db_features_small_cols_tablet = (int) $_POST['small_cols_tablet'];
		if ( $db_features_small_cols_tablet < 1 ) $db_features_small_cols_tablet = 1;
		update_option ( 'db_features_small_cols_tablet', $db_features_small_cols_tablet );

		// Number of columns: Mobile
		$db_features_small_cols_mobile = (int) $_POST['small_cols_mobile'];
		if ( $db_features_small_cols_mobile < 1 ) $db_features_small_cols_mobile = 1;
		update_option ( 'db_features_small_cols_mobile', $db_features_small_cols_mobile );

		// HTML tag: Headline
		$db_features_htmltag_headline = sanitize_text_field( $_POST['htmltag_headline'] );
		if ( empty ( $db_features_htmltag_headline ) ) $db_features_htmltag_headline = 'h3';
		update_option ( 'db_features_htmltag_headline', $db_features_htmltag_headline );

		// HTML tag: Text
		$db_features_htmltag_text = sanitize_text_field( $_POST['htmltag_text'] );
		if ( empty ( $db_features_htmltag_text ) ) $db_features_htmltag_text = 'h3';
		update_option ( 'db_features_htmltag_text', $db_features_htmltag_text );

		require_once( plugin_dir_path( __FILE__ ) . '../css/custom.php' );

	endif;

?>
<div class='wrap db-features-admin'>

	<h1><?php _e( 'Features' , $d ) ?></h1>

	<div class="db-features-description">
		<p><?php _e( 'The plugin is used to make a block of features for your website.' , $d ) ?></p>
		<p><?php _e( 'You will be able to easily implement it anywhere using a shortcode. So if you want to make some changes in the block later, they will be applied everywhere on your website where the block is used.' , $d ) ?></p>
	</div>

	<h2><?php _e( 'Settings' , $d ) ?></h2>

	<form name="db-features" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'] ?>?page=db-features&amp;updated=true">

		<?php
			if (function_exists ('wp_nonce_field') )
				wp_nonce_field('db_features_form');
		?>

		<table class="form-table db-features-table" width="100%">
			<tr valign="top">
				<th scope="col">
					<?php _e( 'The Block of Features' , $d ) ?>
				</th>
			</tr>
			<tr valign="top">
				<td id="db_features_items" class="db-features-items">

					<?php
						$i = -1;
						while ( ++$i < $db_features_num ) :
					?>
						<div id="db_features_item_<?php echo $i ?>" class="db-features-item">

							<h3><?php _e( 'Image' , $d ) ?></h3>

								<div class="db-features-image">
									<div class="db-features-image-inner">
										<?php
											if ( !empty ( $db_features_img[$i] ) ) {
										?>
												<div id="db_features_close_<?php echo $i ?>" class="db-features-close">
													<div class="db-close-1"></div>
													<div class="db-close-2"></div>
													<label><?php _e( 'delete' , $d ) ?></label>
												</div>
										<?php
												echo wp_get_attachment_image ( $db_features_img[$i], 'medium' );
											}
										?>
									</div>
								</div>
								<input type="file" name="img_<?php echo $i ?>" />
								<input type="hidden" name="img_id_<?php echo $i ?>" id="db_features_img_id_<?php echo $i ?>"
								       value="<?php echo $db_features_img[$i] ?>" />

							<h3><?php _e( 'Headline' , $d ) ?></h3>

								<input type="text" name="headline_<?php echo $i ?>" id="db_features_headline_<?php echo $i ?>" size="30"
								       value="<?php echo $db_features_headline[$i] ?>" />

							<h3><?php _e( 'Text' , $d ) ?></h3>

								<?php
									wp_editor( $db_features_text[$i], 'db-features-text-' . $i, array(
										'textarea_name' => 'text_' . $i,
										'textarea_rows' => 10,
										'wpautop' => false,
										'media_buttons' => false
										)
									);
								?>

						</div>

					<?php
						endwhile;
					?>

					<div id="db_features_add" class="db-features-item db-features-add">
						<?php _e( 'Add Feature' , $d ) ?>
					</div>
				</td>
			</tr>
			<tr valign="top">
				<th scope="col">
					<?php _e( 'Styling' , $d ) ?>
				</th>
			</tr>
			<tr valign="top">
				<th scope="col">
					1. <?php _e( 'Type:' , $d ) ?> <?php _e( 'Default', $d ); ?> (<?php _e( 'Big', $d ); ?>)
				</th>
			</tr>
			<tr valign="top">
				<td id="db_features_styling" class="db-features-styling">
					<div class="db-features-styling-item">
						<h3><?php _e( 'Number of columns' , $d ) ?></h3>
						<div class="db-features-param">
							<label for="db_features_cols"><?php _e( 'Desktop' , $d ) ?></label>
							<input type="text" name="cols" id="db_features_cols" size="5" value="<?php echo $db_features_cols ?>" />
						</div>
						<div class="db-features-param">
							<label for="db_features_cols_tablet"><?php _e( 'Tablet' , $d ) ?></label>
							<input type="text" name="cols_tablet" id="db_features_cols_tablet" size="5" value="<?php echo $db_features_cols_tablet ?>" />
						</div>
						<div class="db-features-param">
							<label for="db_features_cols_mobile"><?php _e( 'Mobile' , $d ) ?></label>
							<input type="text" name="cols_mobile" id="db_features_cols_mobile" size="5" value="<?php echo $db_features_cols_mobile ?>" />
						</div>
					</div>
					<div class="db-features-styling-item">

					</div>
					<div class="db-features-styling-item">
						
					</div>
				</td>
			</tr>
			<tr valign="top">
				<th scope="col">
					2. <?php _e( 'Type:' , $d ) ?> <?php _e( 'Small', $d ); ?>
				</th>
			</tr>
			<tr valign="top">
				<td id="db_features_styling_small" class="db-features-styling">
					<div class="db-features-styling-item">
						<h3><?php _e( 'Number of columns' , $d ) ?></h3>
						<div class="db-features-param">
							<label for="db_features_small_cols"><?php _e( 'Desktop' , $d ) ?></label>
							<input type="text" name="small_cols" id="db_features_small_cols" size="5" value="<?php echo $db_features_small_cols ?>" />
						</div>
						<div class="db-features-param">
							<label for="db_features_small_cols_tablet"><?php _e( 'Tablet' , $d ) ?></label>
							<input type="text" name="small_cols_tablet" id="db_features_small_cols_tablet" size="5" value="<?php echo $db_features_small_cols_tablet ?>" />
						</div>
						<div class="db-features-param">
							<label for="db_features_small_cols_mobile"><?php _e( 'Mobile' , $d ) ?></label>
							<input type="text" name="small_cols_mobile" id="db_features_small_cols_mobile" size="5" value="<?php echo $db_features_small_cols_mobile ?>" />
						</div>
					</div>
					<div class="db-features-styling-item">
						
					</div>
					<div class="db-features-styling-item">
						
					</div>
				</td>
			</tr>
			<tr valign="top">
				<th scope="col">
					<?php _e( 'All types' , $d ) ?>
				</th>
			</tr>
			<tr valign="top">
				<td id="db_features_styling" class="db-features-styling">
					<div class="db-features-styling-item">
						<h3><?php _e( 'HTML tags' , $d ) ?></h3>
						<div class="db-features-param">
							<label for="db_features_htmltag_headline"><?php _e( 'Headline' , $d ) ?></label>
							<select type="text" name="htmltag_headline" id="db_features_htmltag_headline">
								<option value="h1"  <?php selected( $db_features_htmltag_headline, 'h1'  ); ?>>H1</option>
								<option value="h2"  <?php selected( $db_features_htmltag_headline, 'h2'  ); ?>>H2</option>
								<option value="h3"  <?php selected( $db_features_htmltag_headline, 'h3'  ); ?>>H3</option>
								<option value="h4"  <?php selected( $db_features_htmltag_headline, 'h4'  ); ?>>H4</option>
								<option value="h5"  <?php selected( $db_features_htmltag_headline, 'h5'  ); ?>>H5</option>
								<option value="h6"  <?php selected( $db_features_htmltag_headline, 'h6'  ); ?>>H6</option>
								<option value="div" <?php selected( $db_features_htmltag_headline, 'div' ); ?>>DIV</option>
								<option value="p"   <?php selected( $db_features_htmltag_headline, 'p'   ); ?>>P</option>
							</select>
						</div>
						<div class="db-features-param">
							<label for="db_features_htmltag_text"><?php _e( 'Text' , $d ) ?></label>
							<select type="text" name="htmltag_text" id="db_features_htmltag_text">
								<option value="h1"  <?php selected( $db_features_htmltag_text, 'h1'  ); ?>>H1</option>
								<option value="h2"  <?php selected( $db_features_htmltag_text, 'h2'  ); ?>>H2</option>
								<option value="h3"  <?php selected( $db_features_htmltag_text, 'h3'  ); ?>>H3</option>
								<option value="h4"  <?php selected( $db_features_htmltag_text, 'h4'  ); ?>>H4</option>
								<option value="h5"  <?php selected( $db_features_htmltag_text, 'h5'  ); ?>>H5</option>
								<option value="h6"  <?php selected( $db_features_htmltag_text, 'h6'  ); ?>>H6</option>
								<option value="div" <?php selected( $db_features_htmltag_text, 'div' ); ?>>DIV</option>
								<option value="p"   <?php selected( $db_features_htmltag_text, 'p'   ); ?>>P</option>
							</select>
						</div>
					</div>
					<div class="db-features-styling-item">
						
					</div>
					<div class="db-features-styling-item">
						
					</div>
				</td>
			</tr>
		</table>

		<input type="hidden" name="num" id="db_features_num" value="<?php echo $db_features_num ?>" />

		<input type="hidden" name="action" value="update" />

		<input type="hidden" name="page_options" value="db_tagcloud_cols" />

		<?php submit_button(); ?>

	</form>


	<h2><?php _e( 'Shortcode' , $d ); ?></h2>

	<div class="db-features-description">

		<p><?php _e( 'You will want to copy and paste the shortcode where you need the block of features on your website. You may use it on any page.' , $d ); ?></p>

		<div class="db_features_shortcode">[db-features]</div>

		<h3><?php _e( 'Parameters' , $d ); ?></h3>

		<table class="db-features-table" width="100%">
			<tr valign="top">
				<th scope="col" width="50%">
					<?php _e( 'Shortcode' , $d ) ?>
				</th>
				<th scope="col" width="50%">
					<?php _e( 'Description' , $d ) ?>
				</th>
			</tr>
			<tr valign="top">
				<td>
					<p>[db-features]</p>
				</td>
				<td>
					<p><?php _e( 'Default block of features', $d ); ?>. <?php _e( 'Good for the home page.', $d ); ?></p>
				</td>
			</tr>
			<tr valign="top">
				<td>
					<p>[db-features type="big"]</p>
				</td>
				<td>
					<p><?php _e( 'Same as', $d ); ?> [db-features]</p>
				</td>
			</tr>
			<tr valign="top">
				<td>
					<p>[db-features type="small"]</p>
				</td>
				<td>
					<p><?php _e( 'Smaller version of the block of features', $d ); ?>. <?php _e( 'Good for the inner pages under the header or above the footer.', $d ); ?></p>
				</td>
			</tr>
		</table>

	</div>


	<h2><?php _e( 'Preview', $d ); ?></h2>

	<div class="db-features-description">

		<p><?php _e( "This is how your block of features looks like. Don't forget to save changes before you leave the page!", $d ); ?></p>

	</div>
    
	<h3><?php _e( 'Default', $d ); ?> (<?php _e( 'Big', $d ); ?>)</h3>

	<div class="db_features_preview"><?php echo do_shortcode('[db-features]') ?></div>
    
	<h3><?php _e( 'Small', $d ); ?></h3>

	<div class="db_features_preview"><?php echo do_shortcode('[db-features type="small"]') ?></div>

</div>