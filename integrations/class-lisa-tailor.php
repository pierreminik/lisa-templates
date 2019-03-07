<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Lisa_Tailor' ) ) {
  class Lisa_Tailor {

    private $plugin_name;
  	private $version;

    public function __construct( $plugin_name, $version ) {

  		$this->plugin_name = $plugin_name;
  		$this->version = $version;

  	}

    public function load_tailor_element() {
      include 'class-lisa-template-element.php';
    }

    public function register_tailor_element( $element_manager ) {
      $element_manager->add_element( 'lisa_template', array(
    		'label'       =>  __( 'Lisa Template', 'lisa-templates' ),
    		'description' =>  __( 'Renders a Lisa Template.', 'lisa-templates' ),
    		'type'        =>  'content',
    		'badge'       =>  __( 'Template', 'lisa-templates' ),
        'dynamic'     =>  true
    	) );
    }

  }
}
