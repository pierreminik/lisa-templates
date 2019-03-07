<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Lisa_Polylang' ) ) {
  class Lisa_Polylang {

    private $plugin_name;
  	private $version;

    public function __construct( $plugin_name, $version ) {

  		$this->plugin_name = $plugin_name;
  		$this->version = $version;

  	}

    public function add_cpt_to_pll( $post_types, $is_settings ) {
      if ( $is_settings ) {
        // hides 'my_cpt' from the list of custom post types in Polylang settings
        // unset( $post_types['my_cpt'] );
        $post_types['lisa_template'] = 'lisa_template';
      }

      return $post_types;
    }

  }
}
