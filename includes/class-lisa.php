<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://miniup.gl
 * @since      1.0.0
 *
 * @package    Lisa_Templates
 * @subpackage Lisa_Templates/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Lisa_Templates
 * @subpackage Lisa_Templates/includes
 * @author     Pierre Minik Lynge <hello@miniup.gl>
 */
class Lisa_Templates {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Lisa_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'lisa-templates';
		$this->version = '1.4.3';

		$this->load_dependencies();
		$this->set_locale();

		$this->define_shortcode();

		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_polylang_hooks();
		$this->define_tailor_hooks();
		$this->define_debug_bar_hooks();


	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Lisa_Loader. Orchestrates the hooks of the plugin.
	 * - Lisa_i18n. Defines internationalization functionality.
	 * - Lisa_Admin. Defines all hooks for the admin area.
	 * - Lisa_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/helpers.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/shortcode.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/widget.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-lisa-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-lisa-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-lisa-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-lisa-public.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'integrations/class-lisa-polylang.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'integrations/class-lisa-tailor.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'integrations/class-lisa-shortcake.php';

		$this->loader = new Lisa_Templates_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Lisa_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Lisa_Templates_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	public function define_shortcode() {
		add_shortcode( 'lisa_template', 'lisa_shortcode' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Lisa_Templates_Admin( $this->get_plugin_name(), $this->get_version() );

		$priority = intval( apply_filters( 'lisa_template_priority', 1984 ) );

		$this->loader->add_action( 'admin_print_scripts-post-new.php', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_print_scripts-post.php', $plugin_admin, 'enqueue_styles' );

		$this->loader->add_action( 'admin_print_scripts-post-new.php', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_print_scripts-post.php', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_filter( 'lisa_prerender_post_types', $plugin_admin, 'prerender_post_types', 1, 1 );

		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_metaboxes' );

		$this->loader->add_action( 'save_post', $plugin_admin, 'save_data', 10, 3 );

		$this->loader->add_action( 'save_post', $plugin_admin, 'save_prerender_template', 0, 3 );

		$this->loader->add_filter( 'save_post', $plugin_admin, 'render_the_prerendered_template', $priority, 3 );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		global $lisa_plugin_public;

		$lisa_plugin_public = new Lisa_Templates_Public( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'tailor_enqueue_sidebar_styles', $lisa_plugin_public, 'enqueue_sidebar_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $lisa_plugin_public, 'enqueue_scripts' );

		$this->loader->add_action( 'init', $lisa_plugin_public, 'register_cpt' );

		$priority = intval( apply_filters( 'lisa_template_priority', 1984 ) );

		$this->loader->add_filter( 'the_content', $lisa_plugin_public, 'content', $priority, 1 );

		$this->loader->add_filter( 'woocommerce_short_description', $lisa_plugin_public, 'wc_short_description', $priority, 1 );

		$this->loader->add_filter( 'get_header', $lisa_plugin_public, 'get_conditions' );

		// $this->loader->add_action( 'woocommerce_before_main_content', $lisa_plugin_public, 'wc_before_content', (-1 * $priority ) );
		//
		// $this->loader->add_action( 'woocommerce_after_main_content', $lisa_plugin_public, 'wc_after_content', $priority );

	}

	private function define_polylang_hooks() {
		$lisa_plugin_polylang = new Lisa_Polylang( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_filter( 'pll_get_post_types', $lisa_plugin_polylang, 'add_cpt_to_pll', 10, 2 );
	}

	private function define_tailor_hooks() {
		$lisa_plugin_tailor = new Lisa_Tailor( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'tailor_load_elements', $lisa_plugin_tailor, 'load_tailor_element', 20 );
		$this->loader->add_action( 'tailor_register_elements', $lisa_plugin_tailor, 'register_tailor_element' );
	}

	private function define_shortcake_hooks() {
		$lisa_plugin_shortcake = new Lisa_Shortcake( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'init', $lisa_plugin_shortcake, 'register_shortcake_element' );
	}

	private function define_debug_bar_hooks() {
		add_filter( 'debug_bar_panels', function( $panels ) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'integrations/class-lisa-debug-bar.php';
      $panels[] = new Lisa_Debug_Bar( $this->plugin_name, $this->version );
      return $panels;
    });
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Lisa_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
