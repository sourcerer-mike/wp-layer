<?php

namespace WP_Layer\Helper;

/**
 * Tools to work with Terms.
 */
class Terms {
	/**
	 * @param \stdClass[] $term_list Ouput of `get_terms()`.
	 *
	 * @return array
	 */
	public static function to_tree( $term_list ) {
		$tmp  = array();
		$tree = array();

		foreach ( $term_list as $category ) {
			if ( ! isset( $category->term_id ) || ! $category->term_id ) {
				continue;
			}

			$category->children = array();

			$tmp[ $category->term_id ] = $category;
		}

		foreach ( $tmp as $category ) {
			if ( ! isset( $category->parent ) || ! $category->parent ) {
				$tree[] = $category;

				continue;
			}

			$tmp[ $category->parent ]->children[] = $category;
		}

		return $tree;
	}
}
