<?php // THE SETTINGS PAGE

	$db_features_img_0 = (int) get_option('db_features_img_0');
	$db_features_headline_0 = wp_kses_post ( get_option('db_features_headline_0') );
	$db_features_text_0 = wp_kses_post ( get_option('db_features_text_0') );

	if ( isset ( $_POST['submit'] ) )
	{

		if ( function_exists('current_user_can') &&
			 !current_user_can('manage_options') )
				die( _e('Error: You do not have the permission to update the value' , 'db-features') );

		if ( function_exists('check_admin_referrer') )
			check_admin_referrer('db_features_form');

		// HTML
		if ( $_FILES['img_0'] || $_POST['headline_0'] || $_POST['text_0'] )
		{
			// Image
			if ( !empty ( $_FILES['img_0'] ))
				{
					require( ABSPATH . 'wp-load.php' );
					require_once( ABSPATH . 'wp-admin/includes/file.php' );

					$db_upload = wp_handle_upload (
						$_FILES['img_0'],
						array( 'test_form' => false )
					);

					if ( empty( $db_upload['error'] ) )
						{
							$db_img_id = wp_insert_attachment(
								array(
									'guid'           => $db_upload['url'],
									'post_mime_type' => $db_upload['type'],
									'post_title'     => basename( $db_upload['file'] ),
									'post_content'   => '',
									'post_status'    => 'inherit',
								),
								$db_upload['file']
							);

							if( !is_wp_error( $db_img_id ) && $db_img_id )
							{
								require_once( ABSPATH . 'wp-admin/includes/image.php' );

								wp_update_attachment_metadata(
									$db_img_id,
									wp_generate_attachment_metadata( $db_img_id, $db_upload['file'] )
								);

								update_option ( 'db_features_img_0', $db_img_id );
							}

						}

				}

			// Headline
			$db_features_headline_0 = wp_kses_post ( $_POST['headline_0'] );
			update_option ( 'db_features_headline_0', $db_features_headline_0 );

			// Text
			$db_features_text_0 = wp_kses_post ( $_POST['text_0'] );
			update_option ( 'db_features_text_0', $db_features_text_0 );
		}

	}

?>
<div class='wrap db-features-admin'>

	<h1><?php _e('Features', 'db-features') ?></h1>

	<div class="db-features-description">
		<p><?php _e("The plugin is used to make a block of features for your website", 'db-features') ?></p>
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
				<td class="db-features-items">
					<div class="db-features-item">

						<h3><?php _e('Image' , 'db-features') ?></h3>
						<?php if ( !empty ( $db_features_img_0 ) ) echo wp_get_attachment_image ( $db_features_img_0, 'medium' ); ?>
						<input type="file" name="img_0" />

						<h3><?php _e('Headline' , 'db-features') ?></h3>
						<input type="text" name="headline_0" id="db_features_headline_0" size="30" value="<?php echo $db_features_headline_0; ?>" />

						<h3><?php _e('Text' , 'db-features') ?></h3>
						<?php
							wp_editor( $db_features_text_0, 'db-features-text-0', array(
								'textarea_name' => 'text_0',
								'textarea_rows' => 10,
								'wpautop' => false,
								'media_buttons' => false
								)
							);
						?>

					</div>
					<div class="db-features-item">x
					</div>
					<div class="db-features-item">x
					</div>
					<div class="db-features-item">x
					</div>
					<div class="db-features-item">x
					</div>
				</td>
			</tr>
		</table>

		<input type="hidden" name="action" value="update" />

		<input type="hidden" name="page_options" value="db_tagcloud_cols" />

		<?php submit_button(); ?>

	</form>

</div>