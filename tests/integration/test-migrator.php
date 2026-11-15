<?php
/**
 * Migrator tests.
 *
 * @package Blogcraft
 */

class Test_Blogcraft_Migrator extends WP_UnitTestCase {

	public function test_table_name_is_prefixed() {
		global $wpdb;
		$this->assertSame( $wpdb->prefix . 'blogcraft_jobs', Blogcraft_Migrator::table_name( 'jobs' ) );
	}

	public function test_migrate_creates_jobs_table() {
		global $wpdb;
		Blogcraft_Migrator::migrate();
		$table = Blogcraft_Migrator::table_name( 'jobs' );
		$found = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table ) );
		$this->assertSame( $table, $found );
	}

	public function test_migrate_creates_log_table() {
		global $wpdb;
		Blogcraft_Migrator::migrate();
		$table = Blogcraft_Migrator::table_name( 'log' );
		$found = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table ) );
		$this->assertSame( $table, $found );
	}

	public function test_migrate_records_db_version() {
		Blogcraft_Migrator::migrate();
		$this->assertSame( BLOGCRAFT_DB_VERSION, get_option( 'blogcraft_db_version' ) );
	}

	public function test_migrate_is_idempotent() {
		Blogcraft_Migrator::migrate();
		Blogcraft_Migrator::migrate();
		$this->assertSame( BLOGCRAFT_DB_VERSION, get_option( 'blogcraft_db_version' ) );
	}

	public function test_drop_tables_removes_tables_and_version() {
		global $wpdb;
		Blogcraft_Migrator::migrate();
		Blogcraft_Migrator::drop_tables();
		$table = Blogcraft_Migrator::table_name( 'jobs' );
		$this->assertNull( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table ) ) );
		$this->assertFalse( get_option( 'blogcraft_db_version' ) );
	}

	// ------------------------------------- the 1.4.0 picture-settings fold.

	/**
	 * Put a pre-1.4.0 settings option in place, run the migration, read back.
	 *
	 * Written straight to the option rather than through Blogcraft_Settings,
	 * because the schema no longer defines these keys and set() refuses one it
	 * does not know -- which is the same reason the migration has to read the
	 * option directly.
	 *
	 * @param array $before Stored settings as an old version left them.
	 * @return array Stored settings afterwards.
	 */
	private function fold( $before ) {
		update_option( Blogcraft_Settings::OPTION, $before, false );
		Blogcraft_Migrator::migrate();

		return (array) get_option( Blogcraft_Settings::OPTION, array() );
	}

	public function test_drawing_with_the_writing_company_keeps_its_model() {
		$after = $this->fold(
			array(
				'image_provider'     => 'gemini',
				'provider_type'      => 'gemini',
				'image_model_gemini' => 'some-gemini-image-model',
				'image_key_gemini'   => 'ciphertext-for-the-image-key',
			)
		);

		$this->assertSame( 'same', $after['image_provider'] );
		$this->assertSame( 'some-gemini-image-model', $after['image_model'] );
		$this->assertSame( 'ciphertext-for-the-image-key', $after['image_api_key'] );
	}

	public function test_drawing_with_a_different_company_falls_back_rather_than_guessing() {
		// Pictures from OpenAI while writing with Groq is no longer something
		// the settings can say. Carrying the model across would mean drawing
		// on a key that is not there, so the free service takes over.
		$after = $this->fold(
			array(
				'image_provider'     => 'openai',
				'provider_type'      => 'groq',
				'openai_image_model' => 'some-openai-image-model',
				'openai_image_key'   => 'ciphertext-for-the-openai-key',
			)
		);

		$this->assertSame( 'pollinations', $after['image_provider'] );
		$this->assertArrayNotHasKey( 'image_model', $after );
		$this->assertArrayNotHasKey( 'image_api_key', $after );
	}

	public function test_the_removed_services_become_the_free_one() {
		foreach ( array( 'fal', 'pexels', 'pixabay' ) as $gone ) {
			$after = $this->fold(
				array(
					'image_provider' => $gone,
					'provider_type'  => 'openai',
					'fal_model'      => 'fal-ai/flux/schnell',
					'fal_api_key'    => 'ciphertext',
					'pexels_api_key' => 'ciphertext',
				)
			);

			$this->assertSame( 'pollinations', $after['image_provider'], $gone );
		}
	}

	public function test_no_stranded_secret_is_left_in_the_database() {
		// A stored key nothing can reach any more is a liability, not a
		// feature. Every one of them goes, whichever branch was taken.
		$after = $this->fold(
			array(
				'image_provider'     => 'xai',
				'provider_type'      => 'xai',
				'image_model_xai'    => 'a-grok-image-model',
				'image_key_xai'      => 'ciphertext',
				'fal_api_key'        => 'ciphertext',
				'fal_model'          => 'fal-ai/flux/schnell',
				'pexels_api_key'     => 'ciphertext',
				'pixabay_api_key'    => 'ciphertext',
				'openai_image_key'   => 'ciphertext',
				'openai_image_model' => 'an-openai-image-model',
				'image_key_gemini'   => 'ciphertext',
				'image_model_gemini' => 'a-gemini-image-model',
			)
		);

		foreach ( array(
			'fal_api_key',
			'fal_model',
			'pexels_api_key',
			'pixabay_api_key',
			'openai_image_key',
			'openai_image_model',
			'image_key_gemini',
			'image_model_gemini',
			'image_key_xai',
			'image_model_xai',
		) as $gone ) {
			$this->assertArrayNotHasKey( $gone, $after, $gone . ' survived the migration' );
		}

		// And the one that mattered came across.
		$this->assertSame( 'same', $after['image_provider'] );
		$this->assertSame( 'a-grok-image-model', $after['image_model'] );
	}

	public function test_a_setting_chosen_since_is_not_overwritten() {
		// migrate() runs on every version change, not only this one, so it has
		// to be safe to run twice. Folding an already-folded option would undo
		// whatever the reader has chosen since.
		$after = $this->fold(
			array(
				'image_provider' => 'same',
				'provider_type'  => 'openai',
				'image_model'    => 'chosen-after-the-upgrade',
			)
		);

		$this->assertSame( 'same', $after['image_provider'] );
		$this->assertSame( 'chosen-after-the-upgrade', $after['image_model'] );
	}

	public function test_a_key_stored_without_a_provider_is_still_swept() {
		// The gap this closes: somebody pasted a fal.ai key and never switched
		// the provider over, so image_provider is still the default. There is
		// nothing to remap, but there is a key in the database that nothing
		// can reach any more, and leaving it there is a liability.
		$after = $this->fold(
			array(
				'image_provider' => 'pollinations',
				'provider_type'  => 'openai',
				'fal_api_key'    => 'ciphertext',
				'fal_model'      => 'fal-ai/flux/schnell',
			)
		);

		$this->assertArrayNotHasKey( 'fal_api_key', $after );
		$this->assertArrayNotHasKey( 'fal_model', $after );

		// And the choice that was already right is left alone.
		$this->assertSame( 'pollinations', $after['image_provider'] );
	}

	public function test_an_already_folded_option_is_left_exactly_as_it_is() {
		// Idempotence, asserted on the whole option rather than a field: this
		// runs on every version change, not only this one.
		$before = array(
			'image_provider' => 'same',
			'provider_type'  => 'gemini',
			'image_model'    => 'a-gemini-image-model',
			'image_api_key'  => 'ciphertext',
		);

		$this->assertSame( $before, $this->fold( $before ) );
	}

	public function test_nothing_is_written_for_a_site_with_no_settings_yet() {
		delete_option( Blogcraft_Settings::OPTION );
		Blogcraft_Migrator::migrate();

		$this->assertFalse( get_option( Blogcraft_Settings::OPTION ) );
	}
}
