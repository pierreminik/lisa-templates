<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 *
 * @link              https://miniup.gl
 * @since             1.0.0
 * @package           Lisa_Templates
 *
 * @wordpress-plugin
 * Plugin Name:       Lisa Templates
 * Plugin URI:        https://templates.lisa.gl
 * Description:       Easily write templates filled with custom data that loads across your site.
 * Version:           1.5.0
 * Author:            Pierre Minik Lynge
 * Author URI:        https://miniup.gl
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       lisa-templates
 * Domain Path:       /languages
 * GitHub Plugin URI: pierreminik/lisa-templates
 * GitHub Plugin URI: https://github.com/pierreminik/lisa-templates
 */

function Lisa_Templates_deactivate() {
  deactivate_plugins( plugin_basename( __FILE__ ) );
}

function Lisa_Templates_dependency_admin_notice() {
  echo '<div class="updated"><p><strong>Lisa Templates</strong> requires the plugin <a href="https://wordpress.org/plugins/timber-library/" target="_blank">Timber</a> to be activated; the plug-in has been <strong>deactivated</strong>.</p></div>';
  if ( isset( $_GET['activate'] ) )
    unset( $_GET['activate'] );
}

function Lisa_Templates_check_dependencies() {
  if ( ! class_exists( '\Timber\Timber' ) ) {
    add_action( 'admin_init', 'Lisa_Templates_deactivate' );
    add_action( 'admin_notices', 'Lisa_Templates_dependency_admin_notice' );
  } else {
  	/**
  	 * The core plugin class that is used to define internationalization,
  	 * admin-specific hooks, and public-facing site hooks.
  	 */
  	require plugin_dir_path( __FILE__ ) . 'includes/class-lisa.php';

  	/**
  	 * Begins execution of the plugin.
  	 *
  	 * Since everything within the plugin is registered via hooks,
  	 * then kicking off the plugin from this point in the file does
  	 * not affect the page life cycle.
  	 *
  	 * @since    1.0.0
  	 */
  	function run_Lisa_Templates() {

  		$plugin = new Lisa_Templates();
  		$plugin->run();

  	}
  	run_Lisa_Templates();
  }
}
add_action( 'plugins_loaded', 'Lisa_Templates_check_dependencies', 2 );
