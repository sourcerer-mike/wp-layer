<?php

namespace WP_Layer;

use WP_Layer\Html\Container;
use WP_Layer\Html\Input\Text;
use WP_Layer\Html\Textarea;

/**
 * Manage a new Widget and concurrent shortcode.
 */
class Widget extends \WP_Widget
{
	/**
	 * @var Container[] Field to configure the widget.
	 */
	protected $_fields = array();

	public function __construct (
		$id_base, $name, $widget_options = array(), $control_options = array()
	) {

		add_shortcode(
			$id_base . '_widget',
			array( $this, 'do_shortcode' )
		);

		parent::__construct(
			$id_base,
			$name,
			$widget_options,
			$control_options
		);
	}

	/**
	 * Front-end
	 *
	 * @param array $instance
	 *
	 * @return string|void
	 */
	public function form ($instance) {

		foreach ( $this->_fields as $name => $single_field ) {
			if ( isset( $instance[ $name ] ) ) {
				$single_field['value'] = esc_attr( $instance[ $name ] );
				if ( $single_field instanceof Textarea ) {
					$single_field->set_value( $single_field['value'] );
					unset( $single_field['value'] );
				}
			}

			$single_field['id'] = $this->get_field_id(
				$single_field['name']
			);

			$single_field['name'] = $this->get_field_name(
				$single_field['name']
			);

			$the_p = new Container( 'p' );

			$the_label        = new Container( 'label' );
			$the_label['for'] = $single_field['id'];

			$the_label->append_child( $single_field['data-label'] );

			$the_p->append_child( $the_label );
			$the_p->append_child( $single_field );

			echo $the_p->render();
		}

	}

	public function input_text ($name, $label, $default = null) {

		$text               = new Text();
		$text['name']       = $name;
		$text['value']      = $default;
		$text['data-label'] = $label;
		$text['class']      = 'widefat';

		$this->_fields[ $name ] = $text;
	}

	public function textarea ($name, $label, $default = null) {

		$textarea         = new Textarea();
		$textarea['name'] = $name;

		$textarea->set_value( $default );

		$textarea['data-label'] = $label;
		$textarea['class']      = 'widefat';

		$this->_fields[ $name ] = $textarea;
	}

	// Widget Backend.
	public function update ($new_instance, $old_instance) {

		$instance = $old_instance;
		foreach ( array_keys( $this->_fields ) as $name ) {
			if ( ! isset( $new_instance[ $name ] ) ) {
				$instance[ $name ] = '';
				continue;
			}

			$instance[ $name ] = $new_instance[ $name ];
		}

		return $instance;
	}

	// Updating widget replacing old instances with new.
	public function widget ($args, $instance) {

		$title = apply_filters( 'widget_title', isset( $instance['title'] ) ? $instance['title'] : '' );

		// Before and after widget arguments are defined by themes.
		echo $args['before_widget'];

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		$this->render( $instance );

		echo $args['after_widget'];
	}
}
