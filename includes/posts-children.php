<?php

add_image_size( 'list-large', 128, 128, true );
add_image_size( 'list-medium', 64, 64, true );
add_image_size( 'list-small', 32, 32, true );

add_action(
	'add_meta_boxes',
	function () {
		add_meta_box(
			'child_posts',
			__( 'Untergeordnet' ),
			function () {
				if ( ! get_post_type_object( get_post_type() )->hierarchical ) {
					return;
				}

				$children = get_children(array(
					'post_type'   => get_post_type(),
						'post_parent' => get_the_ID(),
					));

				require __DIR__ . '/posts-children.phtml';
			}
		);
	}
);
