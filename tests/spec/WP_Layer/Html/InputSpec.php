<?php

namespace spec\WP_Layer\Html;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class InputSpec extends ObjectBehavior
{
	function it_is_initializable() {

		$this->shouldHaveType( '\WP_Layer\Html\Input' );
	}

	function it_extends_container() {

		$this->shouldHaveType( '\WP_Layer\Html\Container' );
	}
}
