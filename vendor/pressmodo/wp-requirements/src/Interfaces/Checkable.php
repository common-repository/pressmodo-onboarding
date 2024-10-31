<?php
/**
 * Checkable interface
 *
 * @package   pressmodo/wp-requirements
 * @author    Pressmodo <hello@pressmodo.com>
 * @copyright 2020 Sematico LTD
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GPL-3.0-or-later
 * @link      https://github.com/pressmodo/wp-requirements
 */

namespace Pressmodo\Requirements\Interfaces;

/**
 * Checkable interface
 */
interface Checkable {

	/**
	 * Gets checker name
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_name();

	/**
	 * Checks if the requirement is met
	 *
	 * @since  1.0.0
	 * @param  mixed $value Value to check against.
	 * @return void
	 */
	public function check( $value );

}
