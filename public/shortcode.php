<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! function_exists( 'lisa_shortcode' ) ) :

  function lisa_shortcode( $atts, $content = null, $tag, $class = null ) {
    $atts = shortcode_atts( array(
      'id'          => NULL,
      'conditions'  => array(),
      'hook'        => 'the_content'
  	), $atts, $tag );

    // Let's not make an endless loop?!
    if ( is_object( $class ) ) {
      $priority = intval( apply_filters( 'lisa_template_priority', 1984 ) );
  		remove_all_filters( 'the_content', $priority );
      remove_all_filters( 'save_post', $priority );
    }

    // WP_Query arguments
		$args = array(
			'post_type'				=> array( 'lisa_template' ),
			'post_status'			=> array( 'publish' ),
			'order'						=> 'ASC',
			'orderby'					=> 'menu_order',
			'posts_per_page' 	=> 1
		);

    if ( empty( $atts['id'] ) ) {
      // ID not specified, autoload it is..
			$autoload = true;

      // Check if conditions are present.
      if ( empty( $atts['conditions'] ) && $tag !== NULL ) {
        return sprintf( '<p>%s</p>', __( 'Please select a template to render.', 'lisa-templates' ) );
      } elseif ( empty( $atts['conditions'] && $tag === NULL ) ) {
				return $content;
			}

      $meta_query = array(
  			'key' => '_lisa_attribute_method',
  			'value' => 'autoload'
  		);

      $args['meta_query'][] = $meta_query;

  		$meta_query = array(
  			'key' => '_lisa_condition',
  			'compare' => 'IN',
  			'value' => $atts['conditions']
  		);

  		$args['meta_query'][] = $meta_query;

      $meta_query = array(
  			'key' => '_lisa_attribute_hook',
  			'value' => $atts['hook']
  		);

  		$args['meta_query'][] = $meta_query;
    } else {
      // An ID was specified, load that template.
      $args['p'] = $atts['id'];
      $autoload = false;
    }

		// The Query
		$query = new WP_Query( $args );

		// The Loop
		if ( $query->have_posts() ) {

			// Setup Timber before we change the loop.
			$context = Timber::get_context();
			$post = new TimberPost();

			while ( $query->have_posts() ) : $query->the_post();
        $source = lisa_allowed_data_sources( get_post_meta( get_the_ID(), '_lisa_data_source', true ) );

        $code = lisa_kses( get_post_meta( get_the_ID(), '_lisa_template_code', true ) );

        $placement = get_post_meta( get_the_ID(), '_lisa_attribute_placement', true );

				$matched_conditions = get_post_meta( get_the_ID(), '_lisa_condition', false );

        $template_name = get_the_title( get_the_ID() );

        $template_id = get_the_ID();

        if ( $source == 'single' ) {

          $context['post'] = $post;
          $context['content'] = $content;

        } elseif ( $source == 'query' ) {
          $defaults = array(
            'post_type'				=> 'post',
            'posts_per_page'	=> 5,
            'post_status'			=> 'publish'
          );

          $data_query = (array) get_post_meta( get_the_ID(), '_lisa_data_query', true );

          $args = array_merge( $defaults, $data_query );

          $context['posts'] = new Timber\PostQuery( $args );
        }

        $code = Timber::compile_string( $code, $context );

				do_action( 'lisa_rendering', array(
					'post_title'		=> $post->title,
					'template_name'	=> $template_name,
					'template_id'		=> $template_id,
          'autoload'      => $autoload,
          'conditions'    => $matched_conditions
				) );

        if ( $placement === 'prepend' ) {
          $content = $code . $content;
        } elseif ( $placement === 'append' ) {
          $content .= $code;
        } else {
          $content = $code;
        }
			endwhile;
		}

		// Restore original Post Data
		wp_reset_postdata();

    // Restore our filter
    if ( is_object( $class ) ) {
      if ( method_exists( $class, 'content' ) ) {
        add_filter( 'the_content', array( $class, 'content' ), $priority );
      }
      if ( method_exists( $class, 'wc_short_description' ) ) {
        add_filter( 'woocommerce_short_description', array( $class, 'content' ), $priority );
      }
      // if ( method_exists( $class, 'wc_before_content' ) && method_exists( $class, 'wc_after_content' ) ) {
      //   add_action( 'woocommerce_before_main_content', array( $class, 'wc_before_content' ), (-1 * $priority) );
      //   add_action( 'woocommerce_after_main_content', array( $class, 'wc_after_content' ), $priority );
      // }
    }

    return $content;
  }

endif;
