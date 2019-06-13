<?php
/**
 * Class GetTranslations
 *
 * @package T15S\Tests
 */

namespace WP_Translations\T15S_Registry\Tests;

use \WP_UnitTestCase;
use const WP_Translations\T15S_Registry\TRANSIENT_KEY_PLUGIN;
use function \WP_Translations\T15S_Registry\get_translations;

/**
 *  Test cases for the get_translations() function.
 */
class GetTranslations extends WP_UnitTestCase {
	/**
	 * Verifies that translation information is loaded from GlotPress.
	 */
	public function test_plugin_translations() {
		$api_url  = 'https://translationspress.com/api/translations/wp-translations/wp-transaltions-tester/';
		$expected = [ 'foo' => 'bar' ];

		add_filter(
			'pre_http_request',
			function ( $result, $args, $url ) use ( $api_url, $expected ) {
				if ( $api_url === $url ) {
					return [
						'headers'  => [],
						'body'     => json_encode( $expected ),
						'response' => [],
					];
				}

				return $result;
			},
			10,
			3
		);

		$actual = get_translations( 'plugin', 'fo-plugin', $api_url );

		$this->assertSame( $expected, $actual );
	}

	/**
	 * Verifies that API results are cached in a transient.
	 */
	public function test_data_is_stored_in_transient() {
		$api_url  = 'https://translationspress.com/api/translations/wp-translations/wp-transaltions-tester/';
		$expected = [ 'foo' => 'bar' ];

		add_filter(
			'pre_http_request',
			function ( $result, $args, $url ) use ( $api_url, $expected ) {
				remove_filter( 'pre_http_request', __FUNCTION__ );

				if ( $api_url === $url ) {
					return [
						'headers'  => [],
						'body'     => json_encode( $expected ),
						'response' => [],
					];
				}

				return $result;
			},
			10,
			3
		);

		get_translations( 'plugin', 'bar-plugin', $api_url );

		$transient = get_site_transient( TRANSIENT_KEY_PLUGIN );
		self::assertNotFalse( $transient->{'bar-plugin'} );
	}

	/**
	 * Verifies that subsequent requests are served from cache.
	 */
	public function test_return_cached_data_on_subsequent_requests() {
		$api_url  = 'https://translationspress.com/api/translations/wp-translations/wp-transaltions-tester/';
		$expected = [ 'foo' => 'bar' ];

		add_filter(
			'pre_http_request',
			function ( $result, $args, $url ) use ( $api_url, $expected ) {
				remove_filter( 'pre_http_request', __FUNCTION__ );

				if ( $api_url === $url ) {
					return [
						'headers'  => [],
						'body'     => json_encode( $expected ),
						'response' => [],
					];
				}

				return $result;
			},
			10,
			3
		);

		get_translations( 'plugin', 'bar-plugin', $api_url );
		$actual = get_translations( 'plugin', 'bar-plugin', $api_url );

		$this->assertSame( $expected, $actual );
	}
}
