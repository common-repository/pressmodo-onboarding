<?php
/**
 * Database prefixer.
 *
 * This is a copy of https://github.com/iandunn/wp-cli-rename-db-prefix, the code has been
 * modified to fit "general purposes" instead of being restricted to WP CLI.
 *
 * @package   database-prefixer
 * @author    Alessandro Tesoro <hello@pressmodo.com>
 * @copyright 2020 Alessandro Tesoro
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 * @link      https://pressmodo.com
 */

namespace Pressmodo\DB;

use Exception;

/**
 * Rename WP database tables prefix.
 */
class DatabasePrefixer {

	public $oldPrefix;
	public $newPrefix;

	/**
	 * Setup the new prefix to use.
	 *
	 * @param string $newPrefix new prefix to use.
	 * @param string $fromPrefix use a specific prefix to indentify tables that need replacement instead of using the one defined by wpdb.
	 */
	public function __construct( $newPrefix, $fromPrefix = false ) {
		global $wpdb;

		if ( $fromPrefix ) {
			$this->oldPrefix = $fromPrefix;
		} else {
			$this->oldPrefix = $wpdb->base_prefix;
		}
		$this->newPrefix = $newPrefix;
	}

	/**
	 * Rename database tables.
	 *
	 * @return void
	 * @throws Exception When query triggers an error.
	 */
	private function renameTables() {
		global $wpdb;

		$showTableQuery = sprintf(
			'SHOW TABLES LIKE "%s%%";',
			$wpdb->esc_like( $this->oldPrefix )
		);

		//phpcs:ignore
		$tables = $wpdb->get_results( $showTableQuery, ARRAY_N );

		if ( ! $tables ) {
			throw new Exception( 'MySQL error: ' . $wpdb->last_error );
		}

		foreach ( $tables as $table ) {
			$table = substr( $table[0], strlen( $this->oldPrefix ) );

			$renameQuery = sprintf(
				'RENAME TABLE `%s` TO `%s`;',
				$this->oldPrefix . $table,
				$this->newPrefix . $table
			);

			//phpcs:ignore
			if ( false === $wpdb->query( $renameQuery ) ) {
				throw new Exception( 'MySQL error: ' . $wpdb->last_error );
			}
		}
	}

	/**
	 * Update the options table.
	 *
	 * @return void
	 * @throws Exception When query triggers an error.
	 */
	private function updateOptionsTable() {
		global $wpdb;

		//phpcs:disable
		$updateQuery = $wpdb->prepare(
			"
			UPDATE `{$this->newPrefix}options`
			SET   option_name = %s
			WHERE option_name = %s
			LIMIT 1;",
			$this->newPrefix . 'user_roles',
			$this->oldPrefix . 'user_roles'
		);
		//phpcs:enable

		//phpcs:ignore
		if ( ! $wpdb->query( $updateQuery ) ) {
			throw new Exception( 'MySQL error: ' . $wpdb->last_error );
		}
	}

	/**
	 * Update the user meta table.
	 *
	 * @return void
	 * @throws Exception When query triggers an error.
	 */
	private function updateUserMetaTable() {
		global $wpdb;

		//phpcs:ignore
		$rows = $wpdb->get_results( "SELECT meta_key FROM `{$this->newPrefix}usermeta`;" );

		if ( ! $rows ) {
			throw new Exception( 'MySQL error: ' . $wpdb->last_error );
		}

		foreach ( $rows as $row ) {
			$metaKeyPrefix = substr( $row->meta_key, 0, strlen( $this->oldPrefix ) );

			if ( $metaKeyPrefix !== $this->oldPrefix ) {
				continue;
			}

			$newKey = $this->newPrefix . substr( $row->meta_key, strlen( $this->oldPrefix ) );

			//phpcs:disable
			$updateQuery = $wpdb->prepare(
				"
				UPDATE `{$this->newPrefix}usermeta`
				SET meta_key=%s
				WHERE meta_key=%s
				LIMIT 1;",
				$newKey,
				$row->meta_key
			);

			if ( ! $wpdb->query( $updateQuery ) ) {
				throw new Exception( 'MySQL error: ' . $wpdb->last_error );
			}
			//phpcs:enable
		}
	}

	/**
	 * Run prefixing.
	 *
	 * @return void
	 */
	public function init() {
		$this->renameTables();
		$this->updateOptionsTable();
		$this->updateUserMetaTable();
	}

}
