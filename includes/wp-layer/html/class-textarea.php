<?php

namespace WP_Layer\Html;

class Textarea extends Container {
	public function get_children() {
		return array( (string) $this['value'] );
	}
}
