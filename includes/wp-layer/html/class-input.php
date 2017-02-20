<?php

namespace WP_Layer\Html;

/**
 * Manage and render an input field.
 */
class Input extends Container {
	public function __construct() {
		parent::__construct( 'input' );
	}

	public function render() {
		if ( ! isset( $this['value'] ) && ! empty( $this['name'] ) ) {
			$this['value'] = get_post_meta( get_the_ID(), $this['name'], true );
		}

		$render = '';
		if ( isset( $this['rendertitle'] ) && true === $this['rendertitle'] ) {
			$render .= $this['title'] . ' ';
		}

		return $render . parent::render();
	}
}
