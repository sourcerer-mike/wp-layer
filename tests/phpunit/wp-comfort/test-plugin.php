<?php

namespace wp_comfort;

class PluginTest extends \PHPUnit_Framework_TestCase {
	protected $pluginPath = 'wp-comfort/wp-comfort.php';

	public function test_is_activated() {
		$plugins = get_plugins();

		$pluginPath = $this->pluginPath;
		$this->assertArrayHasKey( $pluginPath, $plugins );

		$this->assertTrue( is_plugin_active( $pluginPath ) );
	}
}
