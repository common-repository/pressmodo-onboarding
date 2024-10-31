<?php
/**
 * PHP Extensions Checker class
 *
 * @package   pressmodo/wp-requirements
 * @author    Pressmodo <hello@pressmodo.com>
 * @copyright 2020 Sematico LTD
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GPL-3.0-or-later
 * @link      https://github.com/pressmodo/wp-requirements
 */

namespace Pressmodo\Requirements\Checker;

use Exception;
use Pressmodo\Requirements\Abstracts;
use Pressmodo\Requirements\Requirements;

/**
 * PHP Extensions Checker class
 */
class PHPExtensions extends Abstracts\Checker {

	/**
	 * Checker name
	 *
	 * @var string
	 */
	protected $name = 'php_extensions';

	/**
	 * Checks if the requirement is met
	 *
	 * @since  1.0.0
	 * @throws Exception When provided value is not an array.
	 * @param  array $value Array of extensions.
	 * @return void
	 */
	public function check( $value ) {

		if ( ! is_array( $value ) ) {
			throw new Exception( 'PHP Extensions Check requires array parameter' );
		}

		$missing_extensions = array();

		foreach ( $value as $extension ) {
			if ( ! extension_loaded( $extension ) ) {
				$missing_extensions[] = $extension;
			}
		}

		if ( ! empty( $missing_extensions ) ) {
			$this->add_error( sprintf(
				// Translators: PHP extensions.
				'Missing PHP extension: %s',
				implode( ', ', $missing_extensions )
			) );
		}

	}

}
