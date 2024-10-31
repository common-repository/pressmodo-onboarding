<?php
/**
 * WP Checker class
 *
 * @package   pressmodo/wp-requirements
 * @author    Pressmodo <hello@pressmodo.com>
 * @copyright 2020 Sematico LTD
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GPL-3.0-or-later
 * @link      https://github.com/pressmodo/wp-requirements
 */

namespace Pressmodo\Requirements\Checker;

use Pressmodo\Requirements\Abstracts;
use Pressmodo\Requirements\Requirements;

/**
 * WP Checker class
 */
class WordPress extends Abstracts\Checker {

	/**
	 * Checker name
	 *
	 * @var string
	 */
	protected $name = 'wp';

	/**
	 * Checks if the requirement is met
	 *
	 * @since  1.0.0
	 * @throws \Exception When provided value is not a string or numeric.
	 * @param  mixed $value Value to check against.
	 * @return void
	 */
	public function check( $value ) {

		if ( ! is_string( $value ) && ! is_numeric( $value ) ) {
			throw new \Exception( 'WP Check requires numeric or string parameter' );
		}

		$wp_version = get_bloginfo( 'version' );

		if ( version_compare( $wp_version, $value, '<' ) ) {
			$this->add_error( sprintf(
				// Translators: 1. Required WP version, 2. Current WP version.
				'Minimum required version of WordPress is %1$s. Your version is %2$s',
				$value,
				$wp_version
			) );
		}

	}

}
