<?php

namespace spec\WP_Layer\Post_Type;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class Meta_BoxSpec extends ObjectBehavior
{
	function it_is_initializable() {

		$this->shouldHaveType( '\WP_Layer\Post_Type\Meta_Box' );
	}

	function it_extends_fieldset() {

		$this->shouldHaveType( '\WP_Layer\Form\Fieldset' );
	}

	public function it_can_have_a_text_field() {
		$this->input_text( 'label', 'name', 'default' )->shouldReturn( null );
	}

	public function it_can_have_a_textarea() {
		$this->input_textarea( 'label', 'name', 'default' )->shouldReturn( null );
	}
}
