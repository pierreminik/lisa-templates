<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Lisa_Widget' ) ) {

  class Lisa_Widget extends WP_Widget {

    /**
     *
     * @since    1.1.0
     *
     * @var      string
     */
    protected $widget_slug = 'lisa-template';

  	public function __construct() {

  		parent::__construct(
  			$this->get_widget_slug(),
  			__( 'Lisa Template', $this->get_widget_slug() ),
  			array(
  				'classname'  => $this->get_widget_slug().'-class',
  				'description' => __( 'Renders a Lisa Template.', 'lisa-templates' )
  			)
  		);

  		// Refreshing the widget's cached output with each new post
  		add_action( 'save_post',    array( $this, 'flush_widget_cache' ) );
  		add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
  		add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );

  	} // end constructor


      /**
       * Return the widget slug.
       *
       * @since    1.1.0
       *
       * @return    Plugin slug variable.
       */
      public function get_widget_slug() {
          return $this->widget_slug;
      }

  	/*--------------------------------------------------*/
  	/* Widget API Functions
  	/*--------------------------------------------------*/

  	/**
  	 * Outputs the content of the widget.
  	 *
  	 * @param array args  The array of form elements
  	 * @param array instance The current instance of the widget
  	 */
  	public function widget( $args, $instance ) {


  		// Check if there is a cached output
  		$cache = wp_cache_get( $this->get_widget_slug(), 'widget' );

  		if ( ! is_array( $cache ) )
  			$cache = array();

  		if ( ! isset ( $args['widget_id'] ) )
  			$args['widget_id'] = $this->id;

  		if ( isset ( $cache[ $args['widget_id'] ] ) )
  			return print $cache[ $args['widget_id'] ];

  		$widget_string = $args['before_widget'];
  		$widget_string .= lisa_shortcode( array( 'id' => $instance['template_id'] ), '', NULL );
  		$widget_string .= $args['after_widget'];

  		$cache[ $args['widget_id'] ] = $widget_string;

  		wp_cache_set( $this->get_widget_slug(), $cache, 'widget' );

  		print $widget_string;

  	} // end widget


  	public function flush_widget_cache()
  	{
      	wp_cache_delete( $this->get_widget_slug(), 'widget' );
  	}
  	/**
  	 * Processes the widget's options to be saved.
  	 *
  	 * @param array new_instance The new instance of values to be generated via the update.
  	 * @param array old_instance The previous instance of values before the update.
  	 */
  	public function update( $new_instance, $old_instance ) {

  		$instance = $old_instance;
  		$instance['template_id'] = intval( $new_instance['template_id'] );
  		return $instance;

  	} // end widget

  	/**
  	 * Generates the administration form for the widget.
  	 *
  	 * @param array instance The array of keys and values for the widget.
  	 */
  	public function form( $instance ) {

  		$instance = wp_parse_args(
        $instance,
  			array(
          'template_id' => 0
        )
  		);

      $upper_limit = intval( apply_filters( 'lisa_upper_limit', 200 ) );

      $args = array(
        'post_type'				=> array( 'lisa_template' ),
        'post_status'			=> array( 'publish' ),
        'order'						=> 'ASC',
        'orderby'					=> 'menu_order',
        'posts_per_page' 	=> $upper_limit,
      );

      $query = get_posts( $args );

      $posts = array(
        '0' => __( 'Please select a template', 'lisa-templates' )
      );

      foreach( $query as $post ) {
        $posts[$post->ID] = $post->post_title;
      }

      ?>
      <p>
        <label for="<?php echo $this->get_field_id( 'template_id' ); ?>"><?php _e( 'Template' ); ?></label>
        <select id="<?php echo $this->get_field_id( 'template_id' ); ?>" name="<?php echo $this->get_field_name( 'template_id' ); ?>">
          <?php
            foreach ( $posts as $value => $label ) :
              if ( $value === $instance['template_id'] ) $selected = ' selected="selected"';
              else $selected = '';
              printf( '<option value="%1d$"%2$s>%3$s</option>', $value, $selected, $label );
            endforeach; ?>
        </select>
      </p>
      <?php

  	} // end form

  } // end class
}

add_action( 'widgets_init', function() { register_widget("Lisa_Widget"); } );
