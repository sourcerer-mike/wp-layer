<?php

global $wpdb;

$wpdb->termmeta = $wpdb->prefix . 'termmeta';

if ( ! function_exists( 'get_term_meta' ) ) {
	function get_term_meta( $term_id, $meta_key = '', $single = null ) {
		return get_metadata( 'term', $term_id, $meta_key, $single );
	}
}

if ( ! function_exists( 'update_term_meta' ) ) {
	function update_term_meta(
		$term_id, $meta_key, $meta_value, $prev_value = ''
	) {
		return update_metadata(
			'term',
			$term_id,
			$meta_key,
			$meta_value,
			$prev_value
		);
	}
}

if ( ! function_exists( 'delete_term_meta' ) ) {
	function delete_term_meta(
		$term_id, $meta_key, $meta_value = '', $delete_all = null
	) {
		return delete_metadata(
			'term',
			$term_id,
			$meta_key,
			$meta_value,
			$delete_all
		);
	}
}

function wp_layer_edit_tag_form_fields( $term ) {
	wp_enqueue_media();

	$image_id = get_term_meta(
		$term->term_id,
		'attachment_id',
		true
	);

	$image_src = '';
	if ( $image_id ) {
		$image_src = wp_get_attachment_thumb_url( $image_id );
	}

	?>

	<tr class="form-field">
		<th scope="row" valign="top">
			<label
				for="extra1"><?php _e( 'Bild' ); ?></label>
		</th>
		<td>
			<a href="#" class="custom_media_upload">
				<img class="custom_media_image" src="<?php echo $image_src ?>"/>
				<br>
				<?php echo __( 'Bild wählen' ) ?>
			</a><br/>
			<a href="#" id="custom_media_remove">
				<?php _e( 'Bild entfernen' ) ?>
			</a>
			<input class="custom_media_url" type="hidden" name="attachment_url"
			       value="<?php echo $image_src; ?>">
			<input class="custom_media_id" type="hidden" name="attachment_id"
			       value="<?php echo $image_id; ?>">
			<br/>
			<span
				class="description"><?php _e( 'Bild für Kategorie' ); ?></span>

			<script>
				jQuery('.custom_media_upload').click(
					function () {

						var send_attachment_bkp = wp.media.editor.send.attachment;

						wp.media.editor.send.attachment =
							function (props, attachment) {

								jQuery('.custom_media_image').attr(
									'src',
									attachment.url
								);
								jQuery('.custom_media_url').val(attachment.url);
								jQuery('.custom_media_id').val(attachment.id);

								wp.media.editor.send.attachment =
									send_attachment_bkp;
							};

						wp.media.editor.open();

						return false;
					}
				);
				jQuery('#custom_media_remove').click(function(e){
					e.preventDefault();
					jQuery('.custom_media_id').val('');
					jQuery('.custom_media_url').val('');
					jQuery('.custom_media_image').attr('src','');


					jQuery('.custom_media_upload').siblings('span.description').after(
						'<br/><div class="notice notice-warning"><p>' +
						'<?php _e('Die Bildzuordnung wird beim Speichern gelöscht.', WP_LAYER_BASE_TEXTDOMAIN ); ?>' +
					    '</p></div>'
					);
					return false;
				});
			</script>
		</td>
	</tr>

<?php
}

add_action( 'edit_category_form_fields', 'wp_layer_edit_tag_form_fields' );
add_action( 'edit_tag_form_fields', 'wp_layer_edit_tag_form_fields' );

add_action(
	'edited_term',
	function ( $term_id ) {
		if ( ! isset( $_POST['attachment_id'] ) ) {
			return;
		}

		update_term_meta(
			$term_id,
			'attachment_id',
			$_POST['attachment_id']
		);
	}
);
