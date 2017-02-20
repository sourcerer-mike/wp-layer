<?php

add_action( 'admin_head', 'admin_menu_fix' );

function admin_menu_fix() {
	echo '<style>
        #adminmenu {
            transform: translateZ(0);
        }
    </style>';
}

