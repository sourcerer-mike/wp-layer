<?php

namespace WP_Layer\Post_Type;

use WP_Layer\Form\Fieldset;
use WP_Layer\Html\Container;
use WP_Layer\Html\Input\Checkbox;
use WP_Layer\Html\Input\Text;
use WP_Layer\Html\Textarea;
use WP_Layer\Media_Select;

/**
 * Manage a meta-box for post-types.
 */
class Meta_Box extends Fieldset {
	private $_prefix;

	function __construct( $id = null ) {
		$this->_prefix = $id;

		parent::__construct();
	}

	function dispatch() {
		echo $this->render();
	}

	/**
	 * @return string
	 */
	public function get_prefix() {
		return $this->_prefix;
	}

	public function render() {
		$table          = new Container( 'table' );
		$table['class'] = 'form-table';

		foreach ( $this->get_children() as $child ) {
			$table->append_child( $tr = new Container( 'tr' ) );

			$tr->append_child( $label_td = new Container( 'td' ) );
			$tr->append_child( $input_td = new Container( 'td' ) );

			$label_td->append_child( $label = new Container( 'label' ) );

			if ( ! empty( $child['title'] ) ) {
				$label->append_child( $child['title'] );
			}

			if ( isset( $child['name'] ) ) {
				$child          = clone $child;
				$child['value'] = get_post_meta( get_the_ID(), $child['name'], true );
			}

			$input_td->append_child( $child );
		}

		return $table->render();
	}


	public function input_text( $name, $label, $default = null ) {
		$input          = new Text();
		$input['name']  = $this->get_prefix() . $name;
		$input['value'] = $default;
		$input['title'] = $label;

		$this->append_child( $input );
	}

	public function input_checkbox( $name, $label, $value = null ) {
		$input          = new Checkbox();
		$input['name']  = $this->get_prefix() . $name;
		$input['value'] = $value;
		$input['title'] = $label;

		$this->append_child( $input );
	}

	public function textarea( $name, $label, $default = null ) {
		$input          = new Textarea();
		$input['name']  = $this->get_prefix() . $name;
		$input['title'] = $label;
		$input['value'] = $default;

		$this->append_child( $input );
	}

	public function media_select( $name, $label ) {
		$selector          = new Media_Select();
		$selector['name']  = $this->get_prefix() . $name;
		$selector['title'] = $label;

		$this->append_child( $selector );
	}

	/**
	 * @param $name string
	 * @param $label string
	 * @param $children node[]
	 */
	public function fieldset( $name, $label, $children ) {
		$fieldset = new Fieldset();
		$fieldset['title'] = $label;

		if ( is_array( $children ) ) {
			foreach ( $children as $input ) {
				if ( ! empty( $input['name'] ) ) {
					$input['name'] = $this->get_prefix() . $name . '_' . $input['name'];
				}

				$fieldset->append_child( $input );
			}
		}

		$this->append_child( $fieldset );
	}
}
