<?php

namespace WP_Layer\Helper;

/**
 * Tools to work with arrays.
 */
class Arr {
	public static function transpose( $arr ) {
		$out = array();

		foreach ( $arr as $n => $row ) {
			foreach ( $row as $m => $value ) {
				$out[ $m ][ $n ] = $value;
			}
		}

		return $out;
	}

	/**
	 * Check whether the input is an array whose keys are all integers.
	 *
	 * @param array $input_array Input array.
	 *
	 * @return bool True if the input is an array whose keys are all integers.
	 */
	function is_array_all_key_int( $input_array ) {
		if ( ! is_array( $input_array ) ) {
			return false;
		}

		if ( count( $input_array ) <= 0 ) {
			return true;
		}

		$array_unique = array_unique(
			array_map( 'is_int', array_keys( $input_array ) )
		);

		return array( true ) === $array_unique;
	}

	function is_all_key_string( $input_array ) {
		if ( ! is_array( $input_array ) ) {
			return false;
		}

		if ( count( $input_array ) <= 0 ) {
			return true;
		}

		$array_unique = array_unique(
			array_map( 'is_string', array_keys( $input_array ) )
		);

		return array( true ) === $array_unique;
	}

	function is_numeric_sequential_zero_based( $input_array ) {
		if ( ! is_array( $input_array ) ) {
			return false;
		}

		if ( count( $input_array ) <= 0 ) {
			return true;
		}

		$range = range( 0, count( $input_array ) - 1 );

		return array_keys( $input_array ) === $range;
	}
}
