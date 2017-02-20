<?php

namespace spec\WP_Layer\Form;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FieldsetSpec extends ObjectBehavior {
	function it_is_initializable() {
		$this->shouldHaveType( '\WP_Layer\Form\Fieldset' );
	}

	public function it_is_a_container() {
		$this->shouldHaveType( '\WP_Layer\Html\Container' );
	}
}
