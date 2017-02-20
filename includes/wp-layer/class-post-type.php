<?php

namespace WP_Layer;

use WP_Layer\Form\Fieldset;
use WP_Layer\Post_Type\Meta_Box;

/**
 * Class Abstract_Post_Type
 *
 * @package WP_Comfort\Post_Types
 *
 * @property bool     _builtin             ( default: false )
 * @property string   _edit_link           ( default:
 *           'post.php?post=%d' )
 * @property bool     can_export           ( default: true )
 * @property string[] capabilities         ( default: array() )
 * @property string   capability_type      ( default: 'post' )
 * @property bool     delete_with_user     ( default: null )
 * @property string   description          ( default: '' )
 * @property bool     exclude_from_search  ( default: null )
 * @property bool     has_archive          ( default: false )
 * @property bool     hierarchical         ( default: false )
 * @property string[] labels               ( default: array() )
 * @property string   menu_icon            ( default: null )
 * @property int      menu_position        ( default: null )
 * @property int      map_meta_cap         ( default: null )
 * @property bool     public               ( default: false )
 * @property bool     publicly_queryable   ( default: null )
 * @property bool     query_var            ( default: true )
 * @property bool     register_meta_box_cb ( default: null )
 * @property string[] rewrite              ( default: true )
 * @property bool     show_ui              ( default: null )
 * @property bool     show_in_menu         ( default: null )
 * @property bool     show_in_nav_menus    ( default: null )
 * @property bool     show_in_admin_bar    ( default: null )
 * @property string[] supports             ( default: array() )
 * @property string[] taxonomies           ( default: array() )
 *
 * @see     https://developer.wordpress.org/resource/dashicons for menu_icon
 * @see     http://www.kevinleary.net/wordpress-dashicons-list-custom-post-type-icons/
 *          for menu_icon
 */
class Post_Type {
	/**
	 * @var Meta_Box[] Set of used meta-boxes (e.g. for "general information" or "details").
	 */
	protected $_meta_boxes = array();

	/**
	 * @var string[] Set of unwanted meta-boxes added by others, removed in _action_remove_meta_boxes()
	 */
	protected $_unwanted_meta_boxes = array();

	/**
	 * @var string Identifier of the post type (e.g. "product").
	 */
	protected $_post_type;

	/**
	 * @var string Cache for readable name of the post-type.
	 */
	protected $_readable_name;

	/**
	 * Constructor
	 *
	 * @param string $post_type Identifier of the post-type.
	 */
	public function __construct( $post_type ) {
		$this->_post_type = $post_type;

		$readable = $this->_get_readable_name();

		$this->scaffold_labels( $readable, $readable . 's' );

		add_action(
			'save_post_' . $post_type,
			array( $this, 'save_post' ),
			10,
			3
		);
	}

	/**
	 * Turn post-type id into a readable name.
	 *
	 * Makes names like "product" appear as "Product" and "Products".
	 * Or names like "contact-person" appear as "Contact person".
	 *
	 * @issue Not translatable.
	 * @issue Often guesses plural wrong.
	 *
	 * @version <= 3.0.0, shall no longer be used afterwards
	 *
	 * @return string
	 */
	protected function _get_readable_name() {
		if ( ! $this->_readable_name ) {
			$this->_readable_name = ucfirst(
				str_replace( '_', ' ', strtolower( $this->get_post_type() ) )
			);
		}

		return $this->_readable_name;
	}

	/**
	 * @return string
	 */
	public function get_post_type() {
		return (string) $this->_post_type;
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
			'name'               => $plural,
			'singular_name'      => $singular,
			'all_items'          => $plural,
			'add_new'            => $this->translate( 'Add %s', $singular ),
			'add_new_item'       => $this->translate( 'Add new %s', $singular ),
			'edit_item'          => $this->translate( 'Edit %s', $singular ),
			'new_item'           => $this->translate( 'New %s', $singular ),
			'view_item'          => $this->translate( 'View %s', $singular ),
			'search_items'       => $this->translate( 'Search %s', $plural ),
			'not_found'          => $this->translate( '0 %s found', $singular ),
			'not_found_in_trash' => $this->translate( 'No entry in trash' ),
			'parent_item_colon'  => $this->translate( 'Parent %s', $singular ),
			'menu_name'          => $plural,
		);

		$this->set_title_placeholder( $singular );
	}

	public function translate( $text ) {
		$text = __( $text, WP_LAYER_BASE_TEXTDOMAIN );

		if ( func_num_args() > 1 ) {
			$text = vsprintf( $text, array_slice( func_get_args(), 1 ) );
		}

		return $text;
	}

	public function set_title_placeholder( $placeholder_text ) {
		$post_type = $this->get_post_type();

		add_filter(
			'enter_title_here',
			function ( $text ) use ( $placeholder_text, $post_type ) {
				$screen = get_current_screen();
				if ( $post_type != $screen->post_type ) {
					return $text;
				}

				return $placeholder_text;
			}
		);
	}

	/**
	 * @param $id
	 * @param $label
	 *
	 * @return Meta_Box
	 */
	public function add_meta_box( $id, $label, $prefix = null ) {
		if ( null === $prefix ) {
			$prefix = $id . '_';
		}

		$box = new Meta_Box( $prefix );
		$box->legend = $label;

		$this->_meta_boxes[ $id ] = $box;

		return $this->_meta_boxes[ $id ];
	}

	public function add_meta_boxes() {
		foreach ( $this->get_meta_boxes() as $id => $meta_box ) {
			\add_meta_box(
				$id,
				$meta_box->legend,
				array( $meta_box, 'dispatch' ),
				$this->get_post_type()
			);
		}
	}

	/**
	 * @return Fieldset[]
	 */
	public function get_meta_boxes() {
		return $this->_meta_boxes;
	}

	public function remove_meta_boxes( $boxes = array() ) {
		$this->_unwanted_meta_boxes = array_merge( $this->_unwanted_meta_boxes, $boxes );
		if ( ! has_action( 'add_meta_boxes', array( $this, '_action_remove_meta_boxes' ) ) ) {
			add_action( 'add_meta_boxes', array( $this, '_action_remove_meta_boxes' ), PHP_INT_MAX );
		}
	}
	public function _action_remove_meta_boxes() {
		foreach ( $this->_unwanted_meta_boxes as $box ) {
			remove_meta_box( $box, $this->get_post_type(), 'normal' );
			remove_meta_box( $box, $this->get_post_type(), 'advanced' );
			remove_meta_box( $box, $this->get_post_type(), 'side' );
		}
	}

	public function register_post_type() {
		$this->menu_position = 30;
		if ( isset( $this->labels )
		     && isset( $this->labels['menu_name'] )
		) {
			$order = mb_substr( ( $this->labels['menu_name'] ), 0, 1 );

			$order = strtr(
				mb_strtolower( $order ),
				array( 'ä' => 'a', 'ö' => 'o', 'ü' => 'u' )
			);

			$this->menu_position = ord( $order ) - 97 + 30;
		}

		register_post_type( $this->get_post_type(), $this->to_array() );

		// Register meta boxes.
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
	}

	public function to_array() {
		return call_user_func( 'get_object_vars', $this );
	}

	public function rename_postimagediv( $title ) {
		$post_type = $this->get_post_type();

		add_action(
			'do_meta_boxes',
			function () use ( $title, $post_type ) {
				remove_meta_box( 'postimagediv', $post_type, 'side' );

				add_meta_box(
					'postimagediv',
					$title,
					'post_thumbnail_meta_box',
					$post_type,
					'side',
					'low'
				);
			}
		);
	}

	public function save_post( $post_id ) {
		$this->save( $post_id, $_POST );
	}

	public function save( $post_id, $data ) {
		/** @var Meta_Box $meta_box */
		foreach ( $this->get_meta_boxes() as $meta_box ) {
			foreach ( $meta_box->get_children() as $field ) {
				if ( isset( $field['name'] ) && ! empty( $field['name'] ) ) {
					$this->write_meta( $post_id, $field['name'], $data );
				}
				if (
					method_exists( $field, 'get_children' )
					&& ( $children = $field->get_children() )
					&& is_array( $children )
				) {
					foreach ( $children as $child ) {
						if ( isset( $child['name'] ) && ! empty( $child['name'] ) ) {
							$this->write_meta( $post_id, $child['name'], $data );
						}
					}
				}
			}
		}

	}

	/**
	 * @param $post_id
	 * @param $name
	 * @param $data[]
	 */
	protected function write_meta( $post_id, $name, $data ) {
		if ( ! isset( $data[ $name ] ) ) {
			delete_post_meta( $post_id, $name );
		} elseif ( is_array( $data[ $name ] ) ) {
			delete_post_meta( $post_id, $name );

			foreach ( $data[ $name ] as $value ) {
				add_post_meta( $post_id, $name, $value );
			}
		} else {
			update_post_meta( $post_id, $name, $data[ $name ] );
		}
	}
}
