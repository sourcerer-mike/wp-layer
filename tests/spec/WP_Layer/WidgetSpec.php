<?php

namespace spec\WP_Layer;

use PhpSpec\ObjectBehavior;
use PhpSpec\Wrapper\Subject;
use Prophecy\Argument;

class WidgetSpec extends ObjectBehavior {

	function let() {
		// $this->beAnInstanceOf('spec\WP_Layer\DummyWidget');
		$this->beConstructedWith( false, '' );
	}

	function it_is_initializable() {
		$this->shouldHaveType( '\WP_Layer\Widget' );
		$this->shouldHaveType( '\WP_Widget' );
	}

	public function it_has_text_fields() {
		$this->input_text( 'name', 'description', 'default' )->shouldReturn( null );
	}
}
