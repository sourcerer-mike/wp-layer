<?php

namespace spec\WP_Layer\Html;

use WP_Layer\Html\Container;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ContainerSpec extends ObjectBehavior {
	public function it_can_be_rendered_into_html() {
		$this->render()->shouldBeString();
	}

	public function it_child_nodes_can_be_appended() {
		$child = 'nuu';

		$this->append_child( $child )->shouldReturn( null );
		$this->get_children()->shouldReturn(
			[
				$child
			]
		);
	}

	public function it_has_a_list_of_child_nodes() {
		$this->get_children()->shouldBeArray();
		$this->get_children()->shouldReturn( [] );
	}

	public function it_has_a_tag_name() {
		$this->get_tag_name()->shouldReturn( 'container' );
	}

	public function it_can_have_a_different_tag_name() {
		$this->beConstructedWith( 'coffee-pot' );

		$this->get_tag_name()->shouldReturn( 'coffee-pot' );
		$this->render()->shouldStartWith( '<coffee-pot' );
	}

	public function it_is_a_tag_when_no_children_are_present() {
		$this->get_children()->shouldReturn( [] );

		$this->render()->shouldEndWith( '/>' );
	}

	function it_is_initializable() {
		$this->shouldHaveType( '\WP_Layer\Html\Container' );
	}

	public function it_stays_a_container_when_children_are_present() {
		$this->append_child( new Container() )->shouldReturn( null );
		$this->get_children()->shouldHaveCount( 1 );

		$this->render()->shouldNotEndWith( '/>' );
		$this->render()->shouldEndWith( '</container>' );
	}
}
