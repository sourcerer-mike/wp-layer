<?php

namespace spec\WP_Layer\Html\Input;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TextSpec extends ObjectBehavior
{
	function it_is_initializable() {

		$this->shouldHaveType( '\WP_Layer\Html\Input\Text' );
	}

	public function it_extends_input() {
		$this->shouldHaveType( '\WP_Layer\Html\Input' );
	}
}
