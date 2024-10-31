<?php
/**
 * Theme Checker class
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
 * Theme Checker class
 */
class Theme extends Abstracts\Checker {

	/**
	 * Checker name
	 *
	 * @var string
	 */
	protected $name = 'theme';

	/**
	 * Checks if the requirement is met
	 *
	 * @since  1.0.0
	 * @throws Exception When provided value is not an array with keys: slug, name.
	 * @param  mixed $value Value to check against.
	 * @return void
	 */
	public function check( $value ) {

		if ( ! is_array( $value ) ) {
			throw new Exception( 'Theme Check requires array parameter with keys: slug, name' );
		}

		if ( ! array_key_exists( 'slug', $value ) || ! array_key_exists( 'name', $value ) ) {
			throw new Exception( 'Theme Check requires array parameter with keys: slug, name' );
		}

		$theme = wp_get_theme();

		if ( $theme->get_template() !== $value['slug'] ) {
			$this->add_error( sprintf( 'Required theme: %s', $value['name'] ) );
		}

	}

}
