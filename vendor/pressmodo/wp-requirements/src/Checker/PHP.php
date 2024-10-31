<?php
/**
 * PHP Checker class
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
 * PHP Checker class
 */
class PHP extends Abstracts\Checker {

	/**
	 * Checker name
	 *
	 * @var string
	 */
	protected $name = 'php';

	/**
	 * Checks if the requirement is met
	 *
	 * @since  1.0.0
	 * @throws Exception When provided value is not a string or numeric.
	 * @param  string $value Required PHP version.
	 * @return void
	 */
	public function check( $value ) {

		if ( ! is_string( $value ) && ! is_numeric( $value ) ) {
			throw new Exception( 'PHP Check requires numeric or string parameter' );
		}

		$php_version = phpversion();

		if ( version_compare( $php_version, $value, '<' ) ) {
			$this->add_error( sprintf(
				// Translators: 1. Required PHP version, 2. Used PHP version.
				'Minimum required version of PHP is %1$s. Your version is %2$s',
				$value,
				$php_version
			) );
		}

	}

}
