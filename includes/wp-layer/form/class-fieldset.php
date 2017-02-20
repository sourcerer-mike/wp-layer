<?php

namespace WP_Layer\Form;

use WP_Layer\Html\Container;

/**
 * Manage and render form-fieldsets.
 *
 * @package WP_Layer\Form
 */
class Fieldset extends Container {
	/**
	 * @var string Reads "disabled", if it shall not be used.
	 */
	public    $disabled;

	/**
	 * @var string
	 */
	public    $form;

	/**
	 * @var string Title of the field-set.
	 */
	public    $legend;

	/**
	 * @var string Name of the field-set.
	 */
	public    $name;
}
