<?php

namespace WP_Layer;

/**
 * Class Abstract_Post_Type
 *
 * @package WP_Comfort\Post_Types
 *
 * @property string        label                 - Name of the taxonomy shown
 *           in the menu. Usually plural. If not set, labels['name'] will be
 *           used.
 * @property string[]      labels                - An array of labels for this
 *           taxonomy.
 *     * By default tag labels are used for non-hierarchical types and category
 *     labels for hierarchical ones.
 *     * You can see accepted values in {@link get_taxonomy_labels()}.
 * @property string        description           - A short descriptive summary
 *           of what the taxonomy is for. Defaults to blank.
 * @property bool          public                - If the taxonomy should be
 *           publicly queryable;
 *     * Defaults to true.
 * @property bool          hierarchical          - Whether the taxonomy is
 *           hierarchical (e.g. category). Defaults to false.
 * @property bool          show_ui               - Whether to generate a
 *           default UI for managing this taxonomy in the admin.
 *     * If not set, the default is inherited from public.
 * @property bool          show_in_menu          - Whether to show the taxonomy
 *           in the admin menu.
 *     * If true, the taxonomy is shown as a sub-menu of the object type menu.
 *     * If false, no menu is shown.
 *     * show_ui must be true.
 *     * If not set, the default is inherited from show_ui.
 * @property bool          show_in_nav_menus     - Makes this taxonomy
 *           available for selection in navigation menus.
 *     * If not set, the default is inherited from public.
 * @property bool          show_tagcloud         - Whether to list the taxonomy
 *           in the Tag Cloud Widget.
 *     * If not set, the default is inherited from show_ui.
 * @property bool          show_admin_column     - Whether to display a column
 *           for the taxonomy on its post type listing screens.
 *     * Defaults to false.
 * @property callback      meta_box_cb           - Provide a callback function
 *           for the meta box display.
 *     * If not set, defaults to post_categories_meta_box for hierarchical
 *     taxonomies and post_tags_meta_box for non-hierarchical.
 *     * If false, no meta box is shown.
 * @property string[]      capabilities          - Array of capabilities for
 *           this taxonomy.
 *     * You can see accepted values in this function.
 * @property bool|string[] rewrite               - Triggers the handling of
 *           rewrites for this taxonomy. Defaults to true, using $taxonomy as
 *           slug.
 *     * To prevent rewrite, set to false.
 *     * To specify rewrite rules, an array can be passed with any of these
 *     keys
 *         * 'slug' => string Customize the perma-structure slug. Defaults to
 *         $taxonomy key
 *         * 'with_front' => bool Should the perma-structure be prepended with
 *         WP_Rewrite::$front. Defaults to true.
 *         * 'hierarchical' => bool Either hierarchical rewrite tag or not.
 *         Defaults to false.
 *         * 'ep_mask' => const Assign an endpoint mask.
 *             * If not specified, defaults to EP_NONE.
 * @property string        query_var             - Sets the query_var key for
 *           this taxonomy. Defaults to $taxonomy key
 *     * If false, a taxonomy cannot be loaded at ?{query_var}={term_slug}
 *     * If specified as a string, the query ?{query_var_string}={term_slug}
 *     will be valid.
 * @property callback      update_count_callback - Works much like a hook, in
 *           that it will be called when the count is updated.
 *     * Defaults to _update_post_term_count() for taxonomies attached to post
 *     types, which then confirms that the objects are published before
 *     counting them.
 *     * Defaults to _update_generic_term_count() for taxonomies attached to
 *     other object types, such as links.
 * @property bool          _builtin              - true if this taxonomy is a
 *           native or "built-in" taxonomy. THIS IS FOR INTERNAL USE ONLY!
 */
class Term {
	/**
	 * @var string Name and slug for the taxonomy.
	 */
	protected $_name;

	/**
	 * @var string Label of the taxonomy.
	 */
	protected $_readable_name;

	function __construct( $name ) {
		$this->_name = $name;

		$readable = $this->_get_readable_name();

		$this->scaffold_labels( $readable, $readable . 's' );
	}

	protected function _get_readable_name() {
		if ( ! $this->_readable_name ) {
			$this->_readable_name = ucfirst(
				str_replace( '_', ' ', strtolower( $this->get_name() ) )
			);
		}

		return $this->_readable_name;
	}

	public function get_name() {
		return (string) $this->_name;
	}

	/**
	 * Place singular and plural forms for the labels.
	 *
	 * The translation to each post type will be:
	 *
	 *      array(
	 *          'name'               => $plural,
	 *          'singular_name'      => $singular,
	 *          'all_items'          => $plural,
	 *          'add_new'            => $this->__( 'Add %s', $singular ),
	 *          'add_new_item'       => $this->__( 'Add new %s', $singular ),
	 *          'edit_item'          => $this->__( 'Edit %s', $singular ),
	 *          'new_item'           => $this->__( 'New %s', $singular ),
	 *          'view_item'          => $this->__( 'View %s', $singular ),
	 *          'search_items'       => $this->__( 'Search %s', $plural ),
	 *          'not_found'          => $this->__( '0 %s found', $singular ),
	 *          'not_found_in_trash' => $this->__( 'No entry in trash' ),
	 *          'parent_item_colon'  => $this->__( 'Parent %s', $singular ),
	 *          'menu_name'          => $plural,
	 *      )
	 *
	 * @param $singular
	 * @param $plural
	 */
	public function scaffold_labels(
		$singular,
		$plural
	) {
		$this->labels = array(
			'name'                       => $plural,
			'singular_name'              => $singular,
			'menu_name'                  => $singular,
			'all_items'                  => $plural,
			'edit_item'                  => $this->translate( 'Edit %s', $singular ),
			'view_item'                  => $this->translate( 'View %s', $singular ),
			'update_item'                => $this->translate( 'Update %s', $singular ),
			'add_new_item'               => $this->translate( 'Add new %s',
			$singular ),
			'new_item_name'              => $this->translate( 'New %s name',
			$singular ),
			'parent_item'                => $this->translate( 'Parent %s', $singular ),
			'parent_item_colon'          => $this->translate( 'Parent %s:',
			$singular ),
			'search_items'               => $this->translate( 'Search %s', $plural ),
			'popular_items'              => $this->translate( 'Popular %s', $plural ),
			'separate_items_with_commas' => $this->translate( 'Separate %s with commas',
			$plural ),
			'add_or_remove_items'        => $this->translate( 'Add or remove %s',
			$plural ),
			'choose_from_most_used'      => $this->translate( 'Choose from most used %s',
			$plural ),
			'not_found'                  => $this->translate( 'No %s found', $plural ),
		);
	}

	public function translate( $text ) {
		$text = __( $text, WP_LAYER_BASE_TEXTDOMAIN );

		if ( func_num_args() > 1 ) {
			$text = vsprintf( $text, array_slice( func_get_args(), 1 ) );
		}

		return $text;
	}

	public function register_taxonomy( $post_type ) {
		register_taxonomy( $this->get_name(), $post_type, $this->to_array() );
	}

	public function to_array() {
		return call_user_func( 'get_object_vars', $this );
	}

}
