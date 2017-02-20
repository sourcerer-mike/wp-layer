<?php

namespace WP_Layer\Html\Input;

use WP_Layer\Html\Input;

/**
 * Manage and render an input-text field.
 */
class Checkbox extends Input {
	public function __construct() {
		parent::__construct();

		// TODO: parent constructor can auto-guess the type from class name
		$this['type'] = 'checkbox';
	}

	public function render() {
		if ( ! isset( $this['value'] ) && ! empty( $this['name'] ) ) {
			$this['value'] = get_post_meta( get_the_ID(), $this['name'], true );
		}
		$attributes = $this->getArrayCopy();

		$html = '';
		if ( isset( $attributes['rendertitle'] ) && true === $attributes['rendertitle'] ) {
			$html .= $attributes['title'] . ' ';
			unset( $attributes['rendertitle'] );
		}
		$html .= '<input';

		foreach ( $attributes as $name => $value ) {
			if ( ! $value ) {
				continue;
			}

			$html .= ' ' . $name . '="' . esc_attr( $value ) . '"';
			if ( 'value' == $name ) {
				$html .= ' checked="checked"';
			}
		}

		$html .= '/>';

		return $html;
	}
}
