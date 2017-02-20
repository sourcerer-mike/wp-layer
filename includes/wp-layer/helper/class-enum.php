<?php
/**
 * Enum Helper.
 */

namespace WP_Layer\Helper;

/**
 * Help enumerating things.
 */
class Enum {

	/**
	 * @param $the_array
	 * @param $separator
	 * @param $last
	 *
	 * @return string
	 */
	public static function enumerate_array($the_array, $separator, $last) {
		\reset( $the_array );

		if ( ! \count( $the_array ) || ! $the_array ) {
			return '';
		}

		if ( \count( $the_array ) == 1 ) {
			return (string) current( $the_array );
		}

		$end = \array_pop( $the_array );

		return \implode( $separator, $the_array ) . $last . $end;
	}
}
