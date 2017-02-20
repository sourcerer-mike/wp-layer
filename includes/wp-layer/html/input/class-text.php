<?php

namespace WP_Layer\Html\Input;

use WP_Layer\Html\Input;

/**
 * Manage and render an input-text field.
 */
class Text extends Input {
	public function __construct() {
		parent::__construct();

		// TODO: parent constructor can auto-guess the type from class name
		$this['type'] = 'text';
	}

}
