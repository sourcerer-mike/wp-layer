<?php

namespace spec\WP_Layer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class Post_TypeSpec extends ObjectBehavior {
	public function it_has_meta_boxes() {
		$this->get_meta_boxes()->shouldBeArray();
		$this->get_meta_boxes()->shouldReturn( [] );
	}

	public function it_meta_box_is_a_fieldset() {
		$this->add_meta_box(
			'id',
			__( 'ID' )
		)->shouldHaveType( '\\WP_Layer\\Form\\Fieldset' );
	}

	function it_is_initializable() {
		$this->shouldHaveType( '\WP_Layer\Post_Type' );
	}

	public function let() {
		$this->beConstructedWith( 'cpt_test' );
	}
}
