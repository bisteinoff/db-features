<?php // THE SETTINGS PAGE

	$db_features_num = (int) get_option('db_features_num'); // number of features, default value is 1, after adding or removing features the value changes consequently
	$db_features_img = array(); // images of the features
	$db_features_headline = array(); // headlines of the features
	$db_features_text = array(); // descriptions of the features


	// getting the data for the list of features
	$i = -1;
	while ( ++$i < $db_features_num ) :

		$db_features_img[$i] = (int) get_option( 'db_features_img_' . $i );
		$db_features_headline[$i] = wp_kses_post ( get_option( 'db_features_headline_' . $i ) );
		$db_features_text[$i] = wp_kses_post ( get_option( 'db_features_text_' . $i ) );

	endwhile;


	// getting the data from the form
	if ( isset ( $_POST['submit'] ) ) :

		if ( function_exists('current_user_can') &&
			 !current_user_can('manage_options') )
				die( _e('Error: You do not have the permission to update the value' , 'db-features') );

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

	endif;

?>
<div class='wrap db-features-admin'>

	<h1><?php _e('Features', 'db-features') ?></h1>

	<div class="db-features-description">
		<p><?php _e("The plugin is used to make a block of features for your website.", 'db-features') ?></p>
		<p><?php _e("You will be able to easily implement it anywhere using a shortcode. So if you want to make some changes in the block later, they will be applied everywhere on your website where the block is used.", 'db-features') ?></p>
	</div>

	<h2><?php _e('Settings', 'db-features') ?></h2>

	<form name="db-features" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'] ?>?page=db-features&amp;updated=true">

		<?php
			if (function_exists ('wp_nonce_field') )
				wp_nonce_field('db_features_form');
		?>

		<table class="form-table db-features-table" width="100%">
			<tr valign="top">
				<th scope="col">
					<?php _e('The Block of Features' , 'db-features') ?>
				</th>
			</tr>
			<tr valign="top">
				<td id="db_features_items" class="db-features-items">

					<?php
						$i = -1;
						while ( ++$i < $db_features_num ) :
					?>
						<div id="db_features_item_<?php echo $i ?>" class="db-features-item">

							<h3><?php _e('Image' , 'db-features') ?></h3>

								<div class="db-features-image">
									<div class="db-features-image-inner">
										<?php
											if ( !empty ( $db_features_img[$i] ) ) {
										?>
												<div id="db_features_close_<?php echo $i ?>" class="db-features-close">
													<div class="db-close-1"></div>
													<div class="db-close-2"></div>
													<label><?php _e('delete' , 'db-features') ?></label>
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

							<h3><?php _e('Headline' , 'db-features') ?></h3>

								<input type="text" name="headline_<?php echo $i ?>" id="db_features_headline_<?php echo $i ?>" size="30"
								       value="<?php echo $db_features_headline[$i] ?>" />

							<h3><?php _e('Text' , 'db-features') ?></h3>

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
						<?php _e('Add Feature' , 'db-features') ?>
					</div>
				</td>
			</tr>
		</table>

		<input type="hidden" name="num" id="db_features_num" value="<?php echo $db_features_num ?>" />

		<input type="hidden" name="action" value="update" />

		<input type="hidden" name="page_options" value="db_tagcloud_cols" />

		<?php submit_button(); ?>

	</form>

</div>