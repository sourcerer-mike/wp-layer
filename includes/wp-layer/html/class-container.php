<?php

namespace WP_Layer\Html;

/**
 * Manage and render a HTML-Container.
 */
class Container extends \ArrayObject {
	/**
	 * @var Container[] Child-Nodes of the DOM.
	 */
	protected $_children = array();

	/**
	 * @var null|string Tag name (e.g. "div" or "a")
	 */
	protected $_tag_name;

	public function __construct( $tag_name = null ) {
		if ( ! $tag_name ) {
			$tag_name = strtolower(
				basename( str_replace( '\\', '//', get_class( $this ) ) )
			);
		}

		$this->_tag_name = $tag_name;
	}

	public function append_child( $node ) {
		$this->_children[] = $node;
	}

	public function render() {
		$tag_name = $this->get_tag_name();

		$attributes = $this->getArrayCopy();

		$html = '<' . $tag_name;

		foreach ( $attributes as $name => $value ) {
			if ( ! $value ) {
				continue;
			}

			$html .= ' ' . $name . '="' . esc_attr( $value ) . '"';
		}

		if ( ! $this->get_children() ) {
			// No children: turn into a tag.
			$html .= '/';
		}

		$html .= '>';

		foreach ( $this->get_children() as $child ) {
			if ( is_scalar( $child ) ) {
				$html .= $child;
				continue;
			}

			$html .= $child->render();
		}

		if ( $this->get_children() ) {
			// Had children: add closing tag.
			$html .= '</' . $tag_name . '>';
		}

		return $html;
	}

	/**
	 * @return string
	 */
	public function get_tag_name() {
		return $this->_tag_name;
	}

	public function get_children() {
		return $this->_children;
	}
}
