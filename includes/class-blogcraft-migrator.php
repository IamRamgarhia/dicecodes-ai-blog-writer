<?php
/**
 * Database schema and migrations.
 *
 * @package Blogcraft
 */

defined( 'ABSPATH' ) || exit;

/**
 * Creates and versions Blogcraft's custom tables.
 */
class Blogcraft_Migrator {

	/**
	 * Option storing the installed schema version.
	 */
	const VERSION_OPTION = 'blogcraft_db_version';

	/**
	 * Build a fully prefixed table name.
	 *
	 * @param string $suffix Table suffix, e.g. 'jobs'.
	 * @return string
	 */
	public static function table_name( $suffix ) {
		global $wpdb;

		return $wpdb->prefix . 'blogcraft_' . $suffix;
	}

	/**
	 * Create or update the schema.
	 *
	 * Uses dbDelta, which is idempotent. Indexed varchar columns are capped at
	 * 191 characters for the utf8mb4 767-byte InnoDB key limit on older MySQL.
	 *
	 * @return void
	 */
	public static function migrate() {
		global $wpdb;

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$charset_collate = $wpdb->get_charset_collate();
		$jobs            = self::table_name( 'jobs' );
		$log             = self::table_name( 'log' );

		$jobs_sql = "CREATE TABLE {$jobs} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			pipeline varchar(64) NOT NULL DEFAULT '',
			stage varchar(64) NOT NULL DEFAULT '',
			status varchar(20) NOT NULL DEFAULT 'pending',
			payload longtext NULL,
			attempts smallint(5) unsigned NOT NULL DEFAULT 0,
			max_attempts smallint(5) unsigned NOT NULL DEFAULT 3,
			available_at datetime NOT NULL,
			locked_at datetime NULL,
			lock_token varchar(64) NULL,
			last_error text NULL,
			created_at datetime NOT NULL,
			updated_at datetime NOT NULL,
			PRIMARY KEY  (id),
			KEY status_available (status, available_at),
			KEY lock_token (lock_token)
		) {$charset_collate};";

		$log_sql = "CREATE TABLE {$log} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			job_id bigint(20) unsigned NULL,
			level varchar(20) NOT NULL DEFAULT 'info',
			message text NOT NULL,
			context longtext NULL,
			created_at datetime NOT NULL,
			PRIMARY KEY  (id),
			KEY job_id (job_id),
			KEY level_created (level, created_at)
		) {$charset_collate};";

		dbDelta( $jobs_sql );
		dbDelta( $log_sql );

		self::fold_up_image_settings();

		if ( self::table_exists( $jobs ) && self::table_exists( $log ) ) {
			update_option( self::VERSION_OPTION, BLOGCRAFT_DB_VERSION, false );
		}
	}

	/**
	 * Carry a site's image setup across the 1.4.0 simplification.
	 *
	 * Pictures used to mean choosing one of five services and then filling in
	 * that service's own key and model id -- ten settings, four of which were
	 * keys to accounts at companies the site had no other reason to deal with.
	 * It is now one question with two answers: the free service, or the same AI
	 * that writes the post.
	 *
	 * So the old values have to be read where they still are, which is the
	 * stored option: the schema no longer defines these keys, and
	 * Blogcraft_Settings::get() answers null for a key it does not know.
	 *
	 * What happens to each old choice:
	 *
	 * - openai, gemini or xai, and the same company wrote the post: 'same',
	 *   keeping the model id. The writing key draws the pictures, which is
	 *   what the separate image key was usually a copy of anyway.
	 * - openai, gemini or xai, but a different company wrote the post: there
	 *   is no longer a way to express that, so it becomes the free service
	 *   rather than quietly drawing with the wrong account.
	 * - fal, pexels or pixabay: the free service. Nothing can be carried over
	 *   -- the model ids and the search APIs do not exist here.
	 *
	 * The four dead keys are deleted rather than left behind, because a stored
	 * secret nothing can reach is a liability and not a feature.
	 *
	 * @return void
	 */
	private static function fold_up_image_settings() {
		$stored = get_option( Blogcraft_Settings::OPTION, array() );

		if ( ! is_array( $stored ) || array() === $stored ) {
			return;
		}

		$was    = isset( $stored['image_provider'] ) ? (string) $stored['image_provider'] : '';
		$writer = isset( $stored['provider_type'] ) ? (string) $stored['provider_type'] : '';
		$models = array(
			'openai' => 'openai_image_model',
			'gemini' => 'image_model_gemini',
			'xai'    => 'image_model_xai',
		);
		$keys   = array(
			'openai' => 'openai_image_key',
			'gemini' => 'image_key_gemini',
			'xai'    => 'image_key_xai',
		);

		$dead = array_merge(
			array_values( $models ),
			array_values( $keys ),
			array( 'fal_api_key', 'fal_model', 'pexels_api_key', 'pixabay_api_key' )
		);

		$remap  = in_array( $was, array( 'fal', 'pexels', 'pixabay', 'openai', 'gemini', 'xai' ), true );
		$strand = (bool) array_intersect( $dead, array_keys( $stored ) );

		// Nothing stored from before and nothing to remap: already folded, or
		// a site that never used any of this. Returning here is what makes the
		// migration safe to run again -- migrate() runs on every version
		// change, and folding a folded option would undo a choice made since.
		if ( ! $remap && ! $strand ) {
			return;
		}

		// The sweep runs even when the provider is not one being remapped.
		// A site that pasted an image key without ever choosing the provider
		// for it would otherwise keep that key in the database with nothing
		// able to reach it, which is a liability rather than a setting.
		if ( $remap && isset( $models[ $was ] ) && $was === $writer ) {
			$stored['image_provider'] = 'same';
			$stored['image_model']    = isset( $stored[ $models[ $was ] ] ) ? (string) $stored[ $models[ $was ] ] : '';

			// Still ciphertext, and still the same cipher, so it moves across
			// without being decrypted and re-encrypted.
			if ( ! empty( $stored[ $keys[ $was ] ] ) ) {
				$stored['image_api_key'] = (string) $stored[ $keys[ $was ] ];
			}
		} elseif ( $remap ) {
			$stored['image_provider'] = 'pollinations';
		}

		foreach ( $dead as $gone ) {
			unset( $stored[ $gone ] );
		}

		update_option( Blogcraft_Settings::OPTION, $stored, false );
	}

	/**
	 * Whether a table exists in the current database.
	 *
	 * Used to confirm dbDelta actually created both tables before the schema
	 * version is recorded — a failed dbDelta must not record itself as
	 * migrated, or it will never be retried.
	 *
	 * @param string $table Fully prefixed table name.
	 * @return bool
	 */
	private static function table_exists( $table ) {
		global $wpdb;

		return $table === $wpdb->get_var( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$wpdb->prepare( 'SHOW TABLES LIKE %s', $table )
		);
	}

	/**
	 * Drop all Blogcraft tables and the version marker.
	 *
	 * @return void
	 */
	public static function drop_tables() {
		global $wpdb;

		foreach ( array( 'jobs', 'log' ) as $suffix ) {
			$table = self::table_name( $suffix );
			// Table name cannot be parameterised; it is built from $wpdb->prefix.
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange
			$wpdb->query( "DROP TABLE IF EXISTS {$table}" );
		}

		delete_option( self::VERSION_OPTION );
	}
}
