<?php // Silence is golden.

namespace WP_Layer {
	if ( ! class_exists( '\\WP_Layer\\Loader', false ) ) {

		/**
		 * Load files of classes via SPL.
		 */
		class Loader {

			/**
			 * Possible namespaces
			 *
			 * Usually maps namespaces to a directory.
			 *
			 * @var string[]
			 */
			protected static $_namespaces = array();

			/**
			 * Turn class name into a filename.
			 *
			 * Giving a class name will turn it into a filename fulfilling
			 * the WordPress Coding Standards.
			 *
			 * @since 0.0.0
			 * @link  https://make.wordpress.org/core/handbook/coding-standards/php/#naming-conventions
			 *
			 * @param string $class_name The name of a class.
			 *
			 * @return string
			 */
			public static function class_to_file( $class_name ) {
				$filename = '';

				// Sanitize class name.
				$class_name = ltrim( $class_name, '\\' );

				// WP coding standards: Files should be [...] lowercase letters.
				$class_name = strtolower( $class_name );

				if ( $last_ns_pos = strrpos( $class_name, '\\' ) ) {
					$namespace  = substr( $class_name, 0, $last_ns_pos );
					$class_name = substr( $class_name, $last_ns_pos + 1 );
					$filename   = str_replace(
						'\\',
						DIRECTORY_SEPARATOR,
						$namespace
					);
				}

				// WP coding standards:
				// - Hyphens should separate words.
				// - Class file names should be [...] with class- prepended.
				$filename = (string) str_replace(
					'_',
					'-',
					$filename
					. DIRECTORY_SEPARATOR . 'class-' . $class_name . '.php'
				);

				return $filename;
			}

			/**
			 * Search class in include path and load it.
			 *
			 * @param string $class_name The class name.
			 */
			public static function load_class( $class_name ) {

				$filename = static::class_to_file( $class_name );

				foreach ( static::$_namespaces as $namespace => $base_path ) {
					if ( ! is_int( $namespace )
					     && 0 !== strpos( $class_name, $namespace )
					) {
						// Not the namespace of the class: skip.
						continue;
					}

					$file_path = $base_path . DIRECTORY_SEPARATOR . $filename;

					if ( is_readable( $file_path ) ) {
						require_once $file_path;

						return;
					}
				}
			}

			/**
			 * Let the loader search in a directory for a specific namespace.
			 *
			 * @param string $namespace Namespace to watch.
			 * @param string $dir       Directory to search in.
			 */
			public static function register_namespace( $namespace, $dir ) {
				static::$_namespaces[ $namespace ] = $dir;
			}

			/**
			 * Add a directory to look up classes in WordPress-Style
			 *
			 * @param string $dir Directory to search in.
			 */
			public static function register_directory( $dir ) {
				static::$_namespaces[] = $dir;
			}

			/**
			 * @var bool True, if already registered as SPL loader.
			 */
			protected static $_is_registered = false;

			/**
			 * Register this loader.
			 *
			 * @param array $dir
			 */
			public static function register() {
				if ( static::$_is_registered ) {
					return;
				}

				static::$_is_registered = \spl_autoload_register(
					array( __CLASS__, 'load_class' )
				);

			}
		}

		Loader::register();
	}

	// Automatic class loader are copper!
	\WP_Layer\Loader::register_directory( __DIR__ );
}

